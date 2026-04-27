#!/bin/bash
# =============================================================================
# Amazon Product Planner — Deployment Script
# Führt alle nötigen Schritte für ein sauberes Deployment aus.
# Usage: bash deploy.sh
# =============================================================================

set -e

echo "🚀 Deployment gestartet..."

# 1. Wartungsmodus aktivieren
echo "⏸️  Wartungsmodus aktivieren..."
php artisan down --retry=30

# 2. Git Pull (falls Git genutzt wird)
echo "📥 Code aktualisieren..."
git pull origin main 2>/dev/null || echo "   (Kein Git-Remote oder bereits aktuell)"

# 3. Composer Dependencies
echo "📦 Composer Dependencies installieren..."
composer install --no-dev --optimize-autoloader --no-interaction

# 4. NPM Build
echo "🏗️  Frontend Assets bauen..."
npm ci --production=false
npm run build

# 5. Migrationen
echo "🗄️  Datenbank migrieren..."
php artisan migrate --force

# 6. Caches leeren und neu aufbauen
echo "🔄 Caches aktualisieren..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Datenbank-Backup
echo "💾 Datenbank-Backup erstellen..."
php artisan db:backup

# 8. Wartungsmodus deaktivieren
echo "✅ Wartungsmodus deaktivieren..."
php artisan up

echo ""
echo "🎉 Deployment abgeschlossen!"
echo "   App: $(php artisan --version)"
echo "   URL: $(grep APP_URL .env | cut -d= -f2)"
