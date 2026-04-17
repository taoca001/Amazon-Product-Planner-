# 🛒 Amazon Product Planner

Eine umfassende Web-Anwendung für Amazon-Seller zum einfachen und übersichtlichen Planen und Verwalten von Produkten mit Bilder-Upload, Preischeck und Multi-Channel-Listing (Amazon & Shopify).

## 📋 Überblick

**Version:** 0.1.0 (In Entwicklung)  
**Framework:** Laravel 13.5.0  
**Datenbank:** PostgreSQL  
**Frontend:** Blade Templates + Tailwind CSS v4

### Zielgruppe
Amazon- und Shopify-Seller, die ihre Produktverwaltung professionalisieren möchten.

### Hauptziel
Seller können **Produkte mit ihrem eigenen Profil erstellen**, Rohmaterial-Bilder und finale Produktbilder verwalten, sowie separate Listings für Amazon und Shopify pflegen und später exportieren.

---

## 🚀 Quick Start

### Anforderungen
- PHP 8.5+
- PostgreSQL 13+
- Composer
- Laravel 13.5

### Installation
```bash
# Repository klonen
git clone <repo-url>
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
- ✅ Produkt erstellen/bearbeiten/löschen
- ✅ Basis-Informationen: Name, Beschreibung, Preis
- ✅ Keywords & SEO-Metadaten
- ✅ Interne Notizen

### 2. **Bilder-Management** ✅
- ✅ Drag-and-Drop Upload (Rohbilder & Produktbilder)
- ✅ Zwei separate Galerien
- ✅ Automatische Dateiverwaltung
- ✅ Datei-Größe & Format-Validierung (bis 10MB)

### 3. **Amazon Listing** ✅
- ✅ ASIN-Tracking
- ✅ Titel, Beschreibung, Bullet Points (max 5)
- ✅ Keywords & Kategorie
- ✅ Status-Tracking (Entwurf → Bereit → Veröffentlicht)
- ⏳ Sync mit echtem Amazon-Account (geplant)

### 4. **Shopify Listing** ✅
- ✅ Produktinformationen (separat von Amazon)
- ✅ Separate Preisgestaltung
- ✅ SKU & Barcode-Tracking
- ✅ Lagerbestandsverwaltung
- ⏳ Sync mit echtem Shopify-Store (geplant)

### 5. **Export-Funktionalität** ⏹️
- ⏹️ CSV/JSON Export für Amazon-Import
- ⏹️ CSV/JSON Export für Shopify-Import
- ⏹️ Batch-Exporte mehrerer Produkte

### 6. **Authentifizierung & Autorisierung** ✅
- ✅ Email/Passwort Login (Laravel Breeze)
- ✅ Benutzerregistrierung
- ✅ Produkt-Ownership-Validierung (Policy-basiert)

---

## 🔐 Sicherheit

- ✅ CSRF-Protection auf allen POST-Requests
- ✅ SQL-Injection-Schutz durch Eloquent ORM
- ✅ File-Upload Validierung (MIME-Type, Größe)
- ✅ Policy-basierte Autorisierung
- ✅ Password-Hashing mit bcrypt

---

## 🚦 Status (Phase 1)

| Feature | Status | Beschreibung |
|---------|--------|-------------|
| Produktverwaltung | ✅ | Vollständig implementiert |
| Bilder-Upload | ✅ | Drag-and-Drop funktioniert |
| Amazon Listing | ✅ | Formular integriert |
| Shopify Listing | ✅ | Formular integriert |
| Authentication | ✅ | Laravel Breeze installiert |
| Export | ⏹️ | Geplant für Phase 2 |
| Amazon API | ⏹️ | Geplant für Phase 2 |
| Shopify API | ⏹️ | Geplant für Phase 2 |

---

## 📄 Lizenz

Dieses Projekt ist privat.

**Zuletzt aktualisiert:** 17. April 2026 — Phase 1 (Listing Management) ✅
