# Installation & Setup

Vollständige Anleitung zum Aufsetzen des Amazon Product Planners in Ihrer lokalen Umgebung.

## 📋 Anforderungen

- **PHP:** 8.5 oder höher
- **Datenbank:** SQLite (Standard)
- **Package Manager:** Composer 2.0+
- **Node.js:** 18+ (für Vite-Build — Pflicht)
- **Git:** Zum Klonen des Repositories
- **n8n:** 2.8+ (optional, für Keyword-Automatisierung via Docker)

### System-Check
```bash
php -v          # PHP-Version überprüfen (≥ 8.5)
composer -v     # Composer-Version überprüfen
node -v         # Node.js-Version überprüfen (≥ 18)
sqlite3 --version  # SQLite-Version überprüfen
```

---

## 🔧 Installation (Schritt für Schritt)

### 1. Repository klonen
```bash
git clone https://github.com/username/amazon-product-planner.git
cd amazon-product-planner
```

### 2. Dependencies installieren
```bash
composer install
npm install
```

### 3. Umgebungsvariablen konfigurieren
```bash
cp .env.example .env
```

Bearbeite `.env` mit deinen Konfigurationen:
```env
APP_NAME="Amazon Product Planner"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Datenbank (SQLite Standard)
DB_CONNECTION=sqlite
# DB_DATABASE wird automatisch auf database/database.sqlite gesetzt

# Für MySQL/PostgreSQL stattdessen:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=amazon_product_planner
# DB_USERNAME=root
# DB_PASSWORD=your_password

# SE Ranking API (optional, für Keyword-Automatisierung)
SE_RANKING_API_KEY=your_se_ranking_api_key

# Mail (optional)
MAIL_MAILER=log
```

### 4. Application Key generieren
```bash
php artisan key:generate
```

### 5. Datenbank erstellen
```bash
# SQLite-Datei wird automatisch erstellt
touch database/database.sqlite
```

### 6. Migrations ausführen
```bash
php artisan migrate
```

### 7. Storage-Link erstellen
```bash
php artisan storage:link
```

Dies erstellt einen Symlink von `public/storage` zu `storage/app/public`.

### 8. Test-Daten seeden (optional)
```bash
php artisan db:seed
```

Erzeugt einen Test-Benutzer:
- Email: `test@example.com`
- Passwort: `password`

### 9. Frontend-Assets bauen
```bash
npm run build
```

### 10. API-Token erstellen (für n8n)
```bash
php artisan api:token 1 --name="n8n"
# Token wird nur einmal angezeigt — kopieren!
```

---

## 🚀 Anwendung starten

### Entwicklungsserver
```bash
php artisan serve
```

Die App ist dann verfügbar unter: `http://localhost:8000`

### Logs ansehen (in separatem Terminal)
```bash
tail -f storage/logs/laravel.log
```

### Datenbank-Konsole (Laravel Tinker)
```bash
php artisan tinker

# Beispiele:
> App\Models\User::all()
> App\Models\Product::find(1)
> exit()
```

---

## 🐛 Häufige Probleme

### Problem: "SQLSTATE[HY000]: General error: 1 UNIQUE constraint failed"
**Lösung:**
```bash
php artisan migrate:fresh
php artisan db:seed
```

### Problem: "Allowed memory size exceeded"
**Lösung:** Erhöhe PHP Memory Limit in `.env`:
```env
PHP_MEMORY_LIMIT=256M
```

### Problem: Storage Link funktioniert nicht
**Lösung:**
```bash
# Symlink manuell erstellen
rm -rf public/storage
php artisan storage:link
```

### Problem: Permissions (permission denied)
**Lösung:**
```bash
chmod -R 775 storage/
chmod -R 775 public/storage
```

---

## 📁 Wichtige Ordner

| Ordner | Beschreibung |
|--------|-------------|
| `app/Models` | Eloquent Models (Product, User, etc.) |
| `app/Http/Controllers` | Controller-Logik |
| `app/Http/Controllers/Api` | API-Controller (Keyword, Image-Upload) |
| `app/Console/Commands` | Artisan-Commands (Backup, Token) |
| `app/Policies` | Autorisierungs-Policies |
| `database/migrations` | Datenbank-Migrationen |
| `database/seeders` | Test-Daten Seeder |
| `resources/views` | Blade Templates |
| `resources/views/errors` | Custom Error Pages (404, 500, etc.) |
| `routes/web.php` | Web-Route-Definitionen |
| `routes/api.php` | API-Route-Definitionen (Bearer-Token-Auth) |
| `routes/console.php` | Scheduler (tägliches Backup) |
| `storage/app/public` | Hochgeladene Dateien |
| `storage/backups` | SQLite-Datenbank-Backups |
| `storage/logs` | Laravel Logs (daily rotation) |
| `public/build` | Vite-Build-Assets (CSS, JS) |
| `public/storage` | Öffentlicher Symlink zu storage/app/public |

---

## 🧪 Testen

### PHPUnit Tests ausführen
```bash
php artisan test
```

### Spezifischen Test ausführen
```bash
php artisan test tests/Feature/ProductTest.php
```

---

## 🔄 Development Workflow

### 1. Änderungen machen
```bash
# Code editieren...
```

### 2. Neue Migration erstellen
```bash
php artisan make:migration create_new_table
# Edit: database/migrations/XXXX_XX_XX_create_new_table.php
php artisan migrate
```

### 3. Neues Model erstellen
```bash
php artisan make:model ModelName -m  # mit Migration
```

### 4. Controller erstellen
```bash
php artisan make:controller ModelNameController -r  # resource
```

### 5. Testen
```bash
php artisan test
```

---

## 📦 Production Deployment

### Quick-Deploy (empfohlen)
```bash
bash deploy.sh
```

Das Script führt automatisch aus:
1. Wartungsmodus aktivieren
2. Git Pull
3. `composer install --no-dev --optimize-autoloader`
4. `npm ci && npm run build`
5. `php artisan migrate --force`
6. Config/Route/View-Cache aufbauen
7. Datenbank-Backup
8. Wartungsmodus deaktivieren

### Manuelle Schritte
```bash
# .env anpassen:
APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=warning
SESSION_ENCRYPT=true
```

### Backup & Scheduler
```bash
# Manuelles Backup
php artisan db:backup

# Crontab einrichten für tägliches Auto-Backup (02:00 Uhr)
crontab -e
* * * * * cd /pfad/zu/amazon-product-planner && php artisan schedule:run >> /dev/null 2>&1
```

### Artisan-Befehle
| Befehl | Beschreibung |
|--------|-------------|
| `php artisan db:backup` | SQLite-Backup erstellen |
| `php artisan db:backup --keep=14` | Backups 14 Tage aufbewahren |
| `php artisan api:token {user_id}` | Neuen API-Token erstellen |
| `php artisan api:token 1 --name=n8n` | Token mit Namen erstellen |

---

## 🔗 n8n Setup (optional)

Für automatische Keyword-Generierung:

```bash
# n8n via Docker starten
docker run -d --name n8n -p 5678:5678 n8nio/n8n

# n8n öffnen: http://localhost:5678
```

In n8n den Workflow `n8n_google_drive_to_product_image_upload.json` importieren und konfigurieren.

---

**Zuletzt aktualisiert:** 18. April 2026
