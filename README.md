# 🛒 Amazon Product Planner

Eine umfassende Web-Anwendung für Amazon-Seller zum Planen und Verwalten von Produkten mit Bilder-Upload, Keyword-Automatisierung und Multi-Channel-Listing (Amazon & Shopify).

## 📋 Überblick

**Version:** 0.3.0  
**Framework:** Laravel 13 / PHP 8.3  
**Datenbank:** SQLite  
**Frontend:** Blade Templates + Tailwind CSS 3 + Alpine.js  
**Automatisierung:** n8n + SE Ranking API

### Zielgruppe
Amazon- und Shopify-Seller, die ihre Produktverwaltung professionalisieren möchten.

### Hauptziel
Seller können **Produkte mit ihrem eigenen Profil erstellen**, Rohmaterial-Bilder und finale Produktbilder verwalten, Keywords automatisch generieren lassen, sowie separate Listings für Amazon und Shopify pflegen und exportieren.

---

## 🚀 Quick Start

### Anforderungen
- PHP 8.3+
- Composer
- Node.js (optional, für Vite-Build)
- n8n (optional, für Keyword-Automatisierung)

### Installation
```bash
# Repository klonen
git clone https://github.com/taoca001/Amazon-Product-Planner-.git
cd amazon-product-planner

# Dependencies installieren
composer install

# Umgebungsdatei kopieren
cp .env.example .env

# App-Key generieren
php artisan key:generate

# Datenbank migrieren
php artisan migrate

# Storage-Link erstellen
php artisan storage:link

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
- Admin-Flag pro Benutzer

### 8. **Authentifizierung & Autorisierung** ✅
- Laravel Breeze (Email/Passwort)
- Policy-basierte Autorisierung (Owner + Admin)
- API-Token-Auth für n8n-Webhooks
- Rate Limiting auf Login

---

## 🔗 API (für n8n / Automatisierung)

Alle API-Endpoints sind via Bearer-Token geschützt (Middleware `api.token`).

| Methode | Endpoint | Beschreibung |
|---------|----------|-------------|
| `GET` | `/api/products` | Alle Produkte mit Keywords |
| `PATCH` | `/api/products/{id}/keywords` | Keywords aktualisieren |
| `POST` | `/api/products/{id}/images/upload` | Bild hochladen |

```bash
curl -H "Authorization: Bearer pplan_..." http://localhost:8000/api/products
```

---

## 🔐 Sicherheit

- CSRF-Protection auf allen Formularen
- SQL-Injection-Schutz durch Eloquent ORM
- File-Upload-Validierung (MIME-Type, Größe)
- Policy-basierte Autorisierung (Owner + Admin)
- Password-Hashing mit bcrypt
- API-Token-Middleware für externe Zugriffe

---

## 🏗️ Tech-Stack

| Komponente | Technologie |
|-----------|-------------|
| Backend | Laravel 13, PHP 8.3 |
| Datenbank | SQLite (dev) / MySQL (prod-ready) |
| Frontend | Blade, Tailwind CSS 3, Alpine.js |
| Automatisierung | n8n 2.8.3 (Docker) |
| Keyword-API | SE Ranking |
| Auth | Laravel Breeze, API-Tokens |
| Storage | Public Disk (lokaler Speicher) |

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
| Keyword-Automatisierung (n8n) | ✅ |
| API-Token-Auth | ✅ |
| Amazon API Sync | ⏳ Geplant |
| Shopify API Sync | ⏳ Geplant |

---

## 📄 Lizenz

Dieses Projekt ist privat.

**Zuletzt aktualisiert:** 18. April 2026 — v0.3.0
