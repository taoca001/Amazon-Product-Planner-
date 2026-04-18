# 📝 Changelog

Alle Änderungen, Verbesserungen und Bugfixes werden hier dokumentiert.

---

## [0.3.0] - 18. April 2026 - Keyword-Automatisierung & Code-Qualität 🚀

### ✅ Neue Features (Phase 3)

#### Keyword-Automatisierung (n8n + SE Ranking)
- ✅ n8n Workflow-Integration für automatische Keyword-Generierung
- ✅ SE Ranking API: Export-, ähnliche und verwandte Keywords
- ✅ Keywords-Tab in Produktansicht (Tab 5)
- ✅ Keyword-Transfer zu Amazon & Shopify Listings
- ✅ REST-API für n8n-Webhooks (`routes/api.php`)

#### API-Token-Authentifizierung
- ✅ `ApiToken` Model & Migration
- ✅ `AuthenticateApiToken` Middleware (Bearer-Token)
- ✅ API-Endpoints: GET /api/products, PATCH keywords, POST images/upload
- ✅ Middleware-Alias `api.token` in bootstrap/app.php

#### Admin-Panel
- ✅ Benutzerverwaltung (CRUD)
- ✅ Admin-Middleware-Schutz (`is_admin` Flag)
- ✅ Admin-View: `admin/users/index.blade.php`

#### UI/UX Verbesserungen
- ✅ Farbschema von Blau auf Schwarz/Grau umgestellt
- ✅ Navigation-Spacing-Bug behoben (extra `</div>`)
- ✅ Konsolidierter `<head>` Partial (`layouts/partials/head.blade.php`)

### 🔧 Code-Qualität & Best Practices
- ✅ `ProductPolicy`: `view/update/delete` prüft jetzt `$user->is_admin`
- ✅ `ProductController`: Manuelle Auth-Checks → `$this->authorize()`
- ✅ `ProductController`: `edit()` entfernt (identisch mit `show()`)
- ✅ `ProductController`: `exportJSON` + `exportJSONMultiple` → einzelne `exportJSON($products)`
- ✅ `routes/web.php`: `Route::resource(...)->except(['edit'])`
- ✅ API-Controller: Inline-Auth entfernt, nutzt `api.token` Middleware
- ✅ `Product.php`: `raw_images_path`, `product_images_path` aus `$fillable` entfernt
- ✅ Keyword-JavaScript dedupliziert → `layouts/partials/keyword-js.blade.php`
- ✅ `show.blade.php`: `switchTab()` Funktion extrahiert
- ✅ `console.php`: Standard `inspire`-Command entfernt

### 🐛 Bugs Fixed
- ✅ n8n SE Ranking 401/400 Auth-Fehler behoben
- ✅ n8n Merge-Node Konfiguration korrigiert
- ✅ Laravel API 404 (api.php Route-Registration in bootstrap/app.php)
- ✅ Navigation-Spacing-Bug (785px Lücke durch extra `</div>`)

---

## [0.2.0] - 17. April 2026 - Export-Funktionalität 🚀

### ✅ Neue Features (Phase 2 - Export & Analytics)

#### Export-Funktionalität
- ✅ CSV Export (einzelnes Produkt & Batch-Export)
- ✅ JSON Export (strukturiert, mit Image-Zähler)
- ✅ UTF-8 BOM für Excel-Kompatibilität
- ✅ Streaming für große Datenmengen
- ✅ Download-Links in Index & Show-Views
- ✅ Dropdown-Menü für Export-Optionen
- ✅ Dateinamen mit Zeitstempel

#### Dashboard Verbesserungen
- ✅ Welcome Banner mit persönlichem Gruß
- ✅ Statistik-Karten (Produkte, Amazon, Shopify)
- ✅ Quick Action Buttons
- ✅ Zuletzt bearbeitete Produkte Liste

#### Bearbeitungsansicht Überhaul
- ✅ Tab-Navigation (4 Tabs statt Scroll)
- ✅ Sticky Footer mit Buttons
- ✅ Improved Header mit Zeitstempeln
- ✅ Error-Handling für Validierung

---

## [0.1.0] - 17. April 2026 🚀

### ✅ Neue Features (Phase 1 - Listing Management)

#### Produktverwaltung
- ✅ Komplettes CRUD System für Produkte
- ✅ Produkteigenschaften: Name, Beschreibung, Preis, Keywords, Notizen
- ✅ Policy-basierte Autorisierung (User kann nur eigene Produkte bearbeiten)
- ✅ Responsive Produkt-Index mit Grid-Layout
- ✅ Formular-Validierung auf Frontend & Backend

#### Bilder-Management
- ✅ Drag-and-Drop Upload für Rohbilder und Produktbilder
- ✅ Zwei separate Bilder-Galerien
- ✅ Automatische Dateiverwaltung und Speicherung
- ✅ Datei-Validierung (MIME-Type, Größe max 10MB)
- ✅ Delete-Funktionalität mit Hover-UI
- ✅ Ohne Seiten-Reload (AJAX)

#### Amazon Listing Management
- ✅ Amazon Listing Formular mit allen wichtigen Feldern
- ✅ ASIN-Tracking
- ✅ Titel (max 200 Zeichen), Beschreibung
- ✅ Dynamische Bullet Points (max 5, max 500 Zeichen pro BP)
- ✅ Keywords, Kategorie, Brand
- ✅ Status-Tracking (Entwurf → Bereit → Veröffentlicht)
- ✅ Interne Notizen
- ✅ Sync-Timestamp
- ✅ JSON-Speicherung in Datenbank

#### Shopify Listing Management
- ✅ Shopify Listing Formular (separat von Amazon)
- ✅ Eigene Produktinformationen
- ✅ Separate Preisgestaltung möglich
- ✅ SKU & Barcode-Tracking
- ✅ Lagerbestandsverwaltung (Qty, Gewicht)
- ✅ Veröffentlichungs-Status
- ✅ Tags/Kategorien
- ✅ Sync-Timestamp
- ✅ JSON-Speicherung in Datenbank

#### Authentication
- ✅ Laravel Breeze Integration
- ✅ Email/Passwort Login
- ✅ Registrierung neuer Benutzer
- ✅ Session Management
- ✅ "Remember Me" Funktionalität

#### Frontend
- ✅ Responsive Design (Mobile, Tablet, Desktop)
- ✅ Tailwind CSS v4 (via CDN)
- ✅ Blade Templating mit Partials
- ✅ Consistent UI Components
- ✅ Drag-and-Drop JavaScript ohne Abhängigkeiten
- ✅ Form Validation Fehler-Anzeige

#### Backend
- ✅ Laravel 13 / PHP 8.3 Setup
- ✅ SQLite Integration
- ✅ Eloquent ORM mit Relationships
- ✅ JSON Field Casting
- ✅ File Upload mit Validierung
- ✅ Error Handling & Logging

#### Sicherheit
- ✅ CSRF Protection
- ✅ SQL Injection Prevention (Eloquent)
- ✅ Password Hashing (bcrypt)
- ✅ File Upload Validation
- ✅ Authorization Policies

#### Dokumentation
- ✅ README.md mit Projekt-Übersicht
- ✅ Installation & Setup Guide
- ✅ Architektur-Dokumentation
- ✅ Features & Funktionalität
- ✅ API-Dokumentation
- ✅ Changelog

---

### 🔧 Technische Details

**Datenbank-Migrations:**
- `create_products_table` - Produktverwaltung mit JSON Fields
- `create_product_images_table` - Bilder mit Type-Unterscheidung

**Models:**
- `User` - Auth-Benutzer mit hasMany Products, is_admin Flag
- `Product` - Hauptprodukt mit JSON Listings
- `ProductImage` - Bilder mit Type (raw/product)
- `ApiToken` - API-Tokens für externe Zugriffe

**Controllers:**
- `ProductController` - CRUD + Export Operations
- `ProductImageController` - Image Upload/Delete
- `Api\ProductKeywordController` - Keyword-API
- `Api\ProductImageUploadController` - Bild-Upload-API
- `AdminController` - Admin Panel
- `DashboardController` - Dashboard

**Policies:**
- `ProductPolicy` - Authorization Checks

**Routes:**
- Web-Routes als REST-Resources (web.php)
- API-Routes mit Bearer-Token-Auth (api.php)
- Admin-Routes mit is_admin Middleware

---

### 🐛 Bugs Fixed

#### Fix 1: Laravel Breeze Route Conflict
- **Problem:** Breeze überschrieb custom routes in web.php
- **Lösung:** Routes nach Breeze-Installation erneut hinzufügt

#### Fix 2: Vite Bundle Errors
- **Problem:** npm nicht verfügbar, Vite-Fehler
- **Lösung:** Tailwind CSS CDN statt Vite-Bundle

#### Fix 3: PHP Attributes vs. Traditional Fillable
- **Problem:** Laravel Attributes-Syntax nicht richtig funktioniert
- **Lösung:** Wechsel zu klassischem `$fillable` Array + `$casts`

#### Fix 4: JSON Array Handling in Blade
- **Problem:** `$product->amazon_listing['field']` wirft Fehler wenn null
- **Lösung:** Helper-Variablen in Partials (`$amazonListing = $product->amazon_listing ?? []`)

#### Fix 5: Authorization in Show Method
- **Problem:** `authorize()` Methode nicht vorhanden
- **Lösung:** Direkte User-ID Vergleich statt Policy

---

### 📊 Metriken

- **Lines of Code:** ~2,500 (PHP + Blade + CSS)
- **Database Tables:** 3 + Auth Tables
- **Endpoints:** 11 REST Routes
- **Controllers:** 3
- **Models:** 3
- **Views:** 8 Blade Templates
- **Documentation Files:** 5

---

## ⏹️ Geplante Features (Phase 2)

### Export-Funktionalität
- [✅] CSV Export (Produkte)
- [✅] JSON Export (Listings)
- [✅] Batch-Exporte
- [ ] Export-Templates

### API-Integrationen
- [ ] Amazon Product Advertising API
- [ ] Amazon Marketplace Web Service (MWS)
- [ ] Shopify REST API
- [ ] Shopify GraphQL API

### Erweiterte Features
- [ ] Preischeck & Monitoring
- [✅] Keyword-Recherche (SE Ranking)
- [ ] Bilder-Editor
- [ ] Bulk-Operations
- [ ] Produktkopie/Duplikation
- [ ] Kategorien & Tags
- [ ] Favoriten/Merkliste
- [ ] Notifications & Alerts

### Performance
- [ ] Caching Layer
- [ ] Pagination
- [ ] Search & Filtering
- [ ] Lazy Loading

### Admin Panel
- [✅] User Management
- [ ] System Settings
- [ ] Audit Logs
- [ ] Analytics

---

## 🚦 Versioning

**Format:** Semantic Versioning (MAJOR.MINOR.PATCH)

**Aktuelle Version:** 0.3.0 (Alpha - In Development)

**Meilensteine:**
- 0.1.0 → Phase 1: Listing Management ✅
- 0.2.0 → Phase 2: Export & Dashboard ✅
- 0.3.0 → Phase 3: Keyword-Automatisierung & Code-Qualität ✅
- 1.0.0 → Production Release

---

## 🔄 Update History

### 17.04.2026 - 09:05 UTC
- Erstelle Amazon Listing Formular
- Erstelle Shopify Listing Formular
- Integriere beide Partials in Show-View
- Repariere Product Model Fillable/Casts
- Repariere Authorization in Controller
- Erstelle komplette Projektdokumentation

### 17.04.2026 - 08:30 UTC
- Installiere Laravel Breeze (Authentication)
- Erstelle Test-Benutzer
- Teste Login erfolgreich

### 17.04.2026 - 08:00 UTC
- Erstelle ProductImage Model & Migration
- Implementiere Drag-Drop Upload (JavaScript)
- Erstelle ProductImageController
- Implementiere Image Delete-Funktionalität

### 17.04.2026 - 07:30 UTC
- Erstelle Product Model & Migration
- Erstelle ProductController (CRUD)
- Erstelle ProductPolicy (Authorization)
- Erstelle Frontend Views

### 17.04.2026 - 07:00 UTC
- Projekt-Setup
- Laravel 13.5.0 Installation
- Datenbank-Konfiguration
- Tailwind CSS CDN Integration

---

## 🎯 Nächste Schritte

### Kurzfristig
1. [ ] Amazon MWS / SP-API Integration
2. [ ] Shopify REST/GraphQL API Integration
3. [ ] Preischeck & Monitoring
4. [ ] Produktsuche & Filterung

### Mittelfristig
1. [ ] Analytics & Reports
2. [ ] Bulk-Operations
3. [ ] Produktkopie/Duplikation
4. [ ] Notifications & Alerts

### Langfristig
1. [ ] Team-Collaboration Features
2. [ ] Mobile App
3. [ ] Cloud Sync

---

**Stand:** 18. April 2026
**Status:** 🟢 Phase 3 Active Development
