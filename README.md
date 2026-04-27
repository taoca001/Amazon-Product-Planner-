# 🛒 Amazon Product Planner

Eine umfassende Web-Anwendung für Amazon-Seller zum Planen und Verwalten von Produkten mit Bilder-Upload, Keyword-Automatisierung und Multi-Channel-Listing (Amazon & Shopify).

## 📋 Überblick

**Version:** 0.5.0  
**Framework:** Laravel 13 / PHP 8.5  
**Datenbank:** SQLite  
**Frontend:** Blade Templates + Tailwind CSS 3 (Vite-Build) + Alpine.js  
**Automatisierung:** n8n + DataForSEO API + Google Drive

### Zielgruppe
Amazon- und Shopify-Seller, die ihre Produktverwaltung professionalisieren möchten.

### Hauptziel
Seller können **Produkte mit ihrem eigenen Profil erstellen**, Rohmaterial-Bilder und finale Produktbilder verwalten, Keywords automatisch generieren lassen, sowie separate Listings für Amazon und Shopify pflegen und exportieren.

---

## 🚀 Quick Start

### Anforderungen
- PHP 8.5+
- Composer
- Node.js 18+ (für Vite-Build)
- n8n (optional, für Keyword-Automatisierung)

### Installation
```bash
# Repository klonen
git clone https://github.com/taoca001/Amazon-Product-Planner-.git
cd amazon-product-planner

# Dependencies installieren
composer install
npm install

# Umgebungsdatei kopieren
cp .env.example .env

# App-Key generieren
php artisan key:generate

# Datenbank migrieren
php artisan migrate

# Storage-Link erstellen
php artisan storage:link

# Frontend-Assets bauen
npm run build

# Entwicklungsserver starten
php artisan serve
```

### Test-Benutzer
```
E-Mail: test@example.com
Passwort: password
```

---

## 📚 Dokumentation

- [**Installation & Setup**](docs/INSTALLATION.md)
- [**Architektur & Datenbankschema**](docs/ARCHITECTURE.md)
- [**Features & Funktionalität**](docs/FEATURES.md)
- [**API-Dokumentation**](docs/API.md)
- [**Changelog**](docs/CHANGELOG.md)

---

## ✨ Hauptfunktionen

### 1. **Produktverwaltung** ✅
- Produkt erstellen / bearbeiten / löschen
- Basis-Informationen: Name, Beschreibung, Preis
- Keywords & SEO-Metadaten
- Interne Notizen

### 2. **Keyword-Automatisierung (n8n + SE Ranking)** ✅
- Automatische Keyword-Generierung bei Produkterstellung
- SE Ranking API: Export-Keywords, ähnliche und verwandte Keywords
- Keywords-Tab mit Transfer zu Amazon & Shopify Listings
- REST-API für n8n-Webhook-Integration

### 3. **Bilder-Management** ✅
- Drag-and-Drop Upload (Rohbilder & Produktbilder)
- Zwei separate Galerien
- Automatische Dateiverwaltung
- Datei-Größe & Format-Validierung (bis 10MB)

### 4. **Amazon Listing** ✅
- ASIN-Tracking
- Titel, Beschreibung, Bullet Points (max 5)
- Keywords & Kategorie
- Status-Tracking (Entwurf → Bereit → Veröffentlicht)

### 5. **Shopify Listing** ✅
- Separate Produktinformationen & Preisgestaltung
- SKU & Barcode-Tracking
- Tags & Lagerbestandsverwaltung

### 6. **Export-Funktionalität** ✅
- CSV-Export (Einzel & Batch, UTF-8 BOM für Excel)
- JSON-Export (strukturiert mit Image-Zähler)
- Download-Links in Index- & Show-Views

### 7. **Admin-Panel** ✅
- Benutzerverwaltung (CRUD)
- Admin-Middleware-Schutz
- Konto sperren / aktivieren (Toggle) ohne Selbst-Deaktivierung
- API-Tokens eines Nutzers einsehen & widerrufen
- Produkt- und Token-Anzahl pro Benutzer
- Letzten Login anzeigen

### 8. **User Access Management (UAM)** ✅
- `is_active`-Flag: gesperrte Benutzer werden sofort ausgeloggt (`EnsureUserIsActive`-Middleware)
- `last_login_at`-Timestamp wird bei jedem Login aktualisiert
- Eigene API-Tokens über `/profile/tokens` verwalten (erstellen, widerrufen, max. 10)
- Ablaufdatum (`expires_at`) für API-Tokens konfigurierbar
- Abgelaufene / deaktivierte Tokens werden in der API mit 401/403 abgewiesen
- Neuer Token wird **einmalig** im Klartext angezeigt

### 9. **Authentifizierung & Autorisierung** ✅
- Laravel Breeze (Email/Passwort)
- Policy-basierte Autorisierung (Owner + Admin)
- API-Token-Auth für n8n-Webhooks (SHA-256 gehasht)
- Rate Limiting (Login: 5/min, API: 60/min, Operations: 10/min)
- Session-Verschlüsselung aktiviert

### 10. **Operations-Center** ✅
- Keyword-Metriken, Keywords für Seite/Keywords via DataForSEO API
- Bulk-Operationen mit Rate Limiting
- Korrekte Zeilenumbruch-Erkennung (`preg_split`) für Keyword-Listen

### 10. **Google Drive Integration** ✅
- n8n-Workflow: Google Drive → automatischer Bild-Upload
- Ordner-basiertes Mapping: `{product_id} - {name}`
- Rohbilder & Produktbilder-Erkennung

### 11. **Backup & Deployment** ✅
- `php artisan db:backup` — tägliches SQLite-Backup
- Automatischer Scheduler (02:00 Uhr, 7 Tage aufbewahrt)
- `deploy.sh` — komplettes Deployment-Script
- Custom Error Pages (404, 419, 500, 503)

---

## 🔗 API (für n8n / Automatisierung)

Alle API-Endpoints sind via Bearer-Token geschützt (Middleware `api.token`).  
Tokens werden als SHA-256 Hash gespeichert und können nur einmal angezeigt werden.

| Methode | Endpoint | Beschreibung |
|---------|----------|-------------|
| `GET` | `/api/products` | Alle Produkte mit Keywords |
| `PATCH` | `/api/products/{id}/keywords` | Keywords aktualisieren |
| `POST` | `/api/products/{id}/images/upload` | Bild hochladen |

### Token erstellen
```bash
# Via Artisan (Kommandozeile)
php artisan api:token {user_id} --name="n8n"

# Via Web-UI (eigene Tokens)
http://localhost:8000/profile/tokens
```

### Verwendung
```bash
curl -H "Authorization: Bearer pplan_..." http://localhost:8000/api/products
```

---

## 🔐 Sicherheit

- CSRF-Protection auf allen Formularen
- SQL-Injection-Schutz durch Eloquent ORM
- File-Upload-Validierung (MIME-Type, Größe)
- Policy-basierte Autorisierung (Owner + Admin)
- Password-Hashing mit bcrypt (12 Rounds)
- API-Token-Hashing mit SHA-256 (Klartext nur einmal sichtbar)
- Session-Verschlüsselung (`SESSION_ENCRYPT=true`)
- Rate Limiting: Login/Register 5/min, API 60/min, Operations 10/min
- Mass-Assignment-Schutz (`user_id` nicht in `$fillable`)
- HTTPS-Erzwingung in Produktion
- Vite-Build statt CDN (keine externen Abhängigkeiten)
- Custom Error Pages (kein Stack-Trace-Leak in Produktion)
- CSV-Injection-Schutz: Sonderzeichen (`=`, `+`, `-`, `@`) in Exports escaped
- Konto-Sperrung: `EnsureUserIsActive`-Middleware beendet Sessions gesperrter Nutzer sofort
- API-Token-Ablauf: abgelaufene Tokens werden mit HTTP 401 abgewiesen

---

## 🏗️ Tech-Stack

| Komponente | Technologie |
|-----------|-------------|
| Backend | Laravel 13, PHP 8.5 |
| Datenbank | SQLite |
| Frontend | Blade, Tailwind CSS 3 (Vite), Alpine.js |
| Automatisierung | n8n 2.8.3 (Docker) |
| Keyword-API | DataForSEO (Google Ads) |
| Auth | Laravel Breeze, API-Tokens (SHA-256) |
| Storage | Public Disk (lokaler Speicher) |
| Backup | Artisan-Command + Scheduler |
| Deployment | deploy.sh (Wartungsmodus, Cache, Backup) |

---

## 🚦 Status

| Feature | Status |
|---------|--------|
| Produktverwaltung (CRUD) | ✅ |
| Bilder-Upload (Drag & Drop) | ✅ |
| Amazon Listing | ✅ |
| Shopify Listing | ✅ |
| CSV/JSON Export | ✅ |
| Admin-Panel | ✅ |
| User Access Management | ✅ |
| Konto-Sperrung (is_active) | ✅ |
| API-Token-Verwaltung (UI) | ✅ |
| Token-Ablaufdatum | ✅ |
| Letzter Login (last_login_at) | ✅ |
| CSV-Injection-Schutz | ✅ |
| Keyword-Automatisierung (n8n) | ✅ |
| DataForSEO Keyword-API | ✅ |
| API-Token-Auth (SHA-256) | ✅ |
| Google Drive Bild-Sync | ✅ |
| Operations-Center | ✅ |
| Rate Limiting | ✅ |
| Backup & Recovery | ✅ |
| Custom Error Pages | ✅ |
| Vite-Build (kein CDN) | ✅ |
| Pagination | ✅ |
| Amazon API Sync | ⏳ Geplant |
| Shopify API Sync | ⏳ Geplant |

---

## 🚀 Deployment

### Quick-Deploy
```bash
bash deploy.sh
```

Das Script führt aus: Wartungsmodus → Git Pull → Composer → npm build → Migrate → Caches → Backup → Live.

### Manuelles Deployment
```bash
php artisan down
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan db:backup
php artisan up
```

### Crontab für Scheduler (Backups)
```bash
* * * * * cd /pfad/zu/amazon-product-planner && php artisan schedule:run >> /dev/null 2>&1
```

### Artisan-Befehle
```bash
php artisan db:backup              # Manuelles DB-Backup
php artisan db:backup --keep=14    # Backups 14 Tage aufbewahren
php artisan api:token 1 --name=n8n # Neuen API-Token erstellen
```

---

## 📄 Lizenz

Dieses Projekt ist privat.

**Zuletzt aktualisiert:** 28. April 2026 — v0.5.0
