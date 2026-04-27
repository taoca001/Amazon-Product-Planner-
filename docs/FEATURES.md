# ✨ Features & Funktionalität

Vollständige Übersicht aller Funktionen mit Beschreibungen und Verwendungsbeispielen.

---

## 1️⃣ Produktverwaltung

### Feature: Neue Produkte erstellen
**Route:** `GET /products/create` → `POST /products`  
**Controller:** `ProductController::create()` & `ProductController::store()`

**Eingabe-Felder:**
- Produktname (required, max 255 Zeichen)
- Beschreibung (optional, Text-Area)
- Preis (optional, Dezimalzahl)
- Notizen (optional)
- Keywords (optional, dynamisch hinzufügbar)

**Automatisch gespeichert:**
- Benutzer-ID (aktueller Login-Benutzer)
- Zeitstempel (created_at, updated_at)

**Validierung:**
```php
'name' => 'required|string|max:255',
'description' => 'nullable|string',
'price' => 'nullable|numeric|min:0',
```

---

### Feature: Produkte bearbeiten
**Route:** `GET /products/{id}` → `PUT /products/{id}`  
**Controller:** `ProductController::show()` & `ProductController::update()`

**Erlaubte Änderungen:**
- Alle Basis-Informationen
- Alle Listing-Felder (Amazon & Shopify)
- Keywords

**Schutz:**
- Nur der Owner kann bearbeiten
- Authorization-Policy Check

---

### Feature: Produkte löschen
**Route:** `DELETE /products/{id}`  
**Controller:** `ProductController::destroy()`

**Was passiert:**
- Produkt aus Datenbank gelöscht
- Alle verknüpften Bilder aus Dateisystem gelöscht
- ProductImage-Records gelöscht

**Schutz:**
- Confirmation Dialog im Frontend
- Owner-Verification

---

## 2️⃣ Bilder-Management

### Feature: Drag & Drop Upload
**Route:** `POST /products/{id}/images`  
**Controller:** `ProductImageController::store()`

**Funktionsweise:**
1. Benutzer zieht Datei(en) in Dropzone
2. JavaScript sendet Datei(en) via FormData
3. Server validiert und speichert Datei
4. Bild-Galerie wird aktualisiert (ohne Reload)

**Validierung:**
- MIME-Type: image/* (PNG, JPG, GIF, etc.)
- Dateigröße: max 10MB
- Authentifizierung & Autorisierung

**Dateispeicherung:**
```
storage/app/public/products/
  └── {product_id}/
      ├── raw/               (Rohmaterial-Bilder)
      └── product/           (Finale Produktbilder)
```

**Datenbank-Speicherung:**
```php
ProductImage::create([
    'product_id' => $product->id,
    'type' => 'raw' | 'product',
    'file_path' => 'products/1/raw/xyz.jpg',
    'file_name' => 'xyz.jpg',
    'file_size' => 524288,
    'mime_type' => 'image/jpeg',
    'order' => 1,
]);
```

---

### Feature: Bilder-Galerie
**Anzeige:** `resources/views/products/show.blade.php`

**Zwei separate Galerien:**
1. **Rohbilder (Blau)** - Original/Reference-Material
2. **Produktbilder (Grün)** - Finale Verkaufs-Bilder

**Funktionen pro Bild:**
- Thumbnail-Anzeige (160x160px)
- Hover-Effekt zeigt Delete-Button
- Dateinamen unter Thumbnail
- Automatische Bildorder-Verwaltung

---

### Feature: Bilder löschen
**Route:** `DELETE /products/{id}/images/{imageId}`  
**Controller:** `ProductImageController::destroy()`

**Was passiert:**
1. Authorization Check (Owner)
2. Datei aus Dateisystem löschen
3. ProductImage-Record löschen
4. Response: JSON mit Success/Error

**Frontend:**
```javascript
Hover über Bild → ✕ Button → Click → Bild weg
```

---

## 3️⃣ Amazon Listing Management

### Feature: Amazon Listing Form
**Location:** `resources/views/products/sections/amazon-listing.blade.php`  
**Integration:** Partial include in `products/show.blade.php`

**Felder:**

| Feld | Typ | Pflicht | Max Länge | Beschreibung |
|------|-----|---------|-----------|-------------|
| ASIN | Text | ❌ | - | Amazon Standard Identification Number |
| Titel | Text | ✅ | 200 | Amazon Produkttitel |
| Beschreibung | TextArea | ✅ | - | Ausführliche Produktbeschreibung |
| Bullet Points | Array | ❌ | 5x500 | Bis zu 5 Stichpunkte |
| Keywords | TextArea | ❌ | 250 | Komma-getrennte Suchbegriffe |
| Kategorie | Text | ❌ | - | Amazon Kategorisierung |
| Brand | Text | ❌ | - | Hersteller/Marke |
| Status | Select | ❌ | - | draft / ready / published |
| Notizen | TextArea | ❌ | - | Interne Anmerkungen |

**Status-Lifecycle:**
```
📝 Entwurf → ✓ Bereit zum Hochladen → 🟢 Auf Amazon veröffentlicht
```

**JSON-Speicherung:**
```php
// In products table, Spalte amazon_listing
{
    "asin": "B08KQT5SZ9",
    "title": "Wireless Kopfhörer Pro",
    "description": "...",
    "bullet_points": [...],
    "keywords": "...",
    "category": "...",
    "brand": "...",
    "status": "ready",
    "notes": "..."
}
```

**Validierung:**
```php
'amazon_listing.title' => 'required|string|max:200',
'amazon_listing.description' => 'required|string',
'amazon_listing.bullet_points' => 'nullable|array|max:5',
```

---

### Feature: Dynamische Bullet Points
**JavaScript-Location:** `amazon-listing.blade.php` (inline script)

**Funktionen:**
- ➕ Neue Bullet Points hinzufügen
- ❌ Bullet Points löschen
- Max 5 Stichpunkte (Validation)
- Jedes BP max 500 Zeichen

**UX:**
```
Eingabefeld → [Delete Button]
[+ Bullet Point hinzufügen]
```

---

## 4️⃣ Shopify Listing Management

### Feature: Shopify Listing Form
**Location:** `resources/views/products/sections/shopify-listing.blade.php`  
**Integration:** Partial include in `products/show.blade.php`

**Felder:**

| Feld | Typ | Pflicht | Beschreibung |
|------|-----|---------|-------------|
| Product ID | Text | ❌ | Shopify Produkt-ID |
| Titel | Text | ✅ | Shopify Produkttitel |
| Preis | Dezimal | ❌ | Shopify Verkaufspreis (€) |
| Vergleichspreis | Dezimal | ❌ | UVP/Streichpreis |
| Beschreibung | HTML | ✅ | HTML-fähige Beschreibung |
| SKU | Text | ❌ | Stock Keeping Unit |
| Barcode | Text | ❌ | EAN/Barcode |
| Tags | Text | ❌ | Komma-getrennte Tags |
| Lagerbestand | Integer | ❌ | Verfügbare Menge |
| Gewicht | Dezimal | ❌ | Gewicht in Gramm |
| Veröffentlicht | Select | ❌ | Veröffentlicht / Versteckt |
| Status | Select | ❌ | draft / ready / published |
| Notizen | TextArea | ❌ | Interne Anmerkungen |

**Besonderheiten:**
- **Separate Preisgestaltung:** Kann anders als Amazon sein
- **Vergleichspreis:** Für Rabatt-Anzeige im Shop
- **HTML-Support:** Beschreibung kann HTML enthalten
- **Lagerbestandsverwaltung:** Integration geplant

---

## 5️⃣ Authentifizierung

### Feature: Login
**Route:** `GET /login` → `POST /login`  
**Controller:** Laravel Breeze (Auth\AuthenticatedSessionController)

**Eingabe:**
- Email (required, valid email)
- Passwort (required, min 8 Zeichen)
- Remember Me (optional, Checkbox)

**Session:**
- 24h Session-Timeout
- Cookies speichern Session-Token
- CSRF-Token pro Request

---

### Feature: Registrierung
**Route:** `GET /register` → `POST /register`  
**Controller:** Laravel Breeze (Auth\RegisteredUserController)

**Validierung:**
```php
'email' => 'required|email|unique:users',
'password' => 'required|min:8|confirmed',
```

---

## 6️⃣ Dashboard & Übersicht

### Feature: Produkt-Index
**Route:** `GET /products`  
**Controller:** `ProductController::index()`

**Anzeige:**
- Responsive Grid (1/2/3 Spalten)
- Produkt-Karten mit:
  - Thumbnail (erstes Produktbild oder Placeholder)
  - Produktname
  - Preis
  - Keywords-Tags
  - Amazon/Shopify Status-Icons
  - Edit/Delete Buttons

**Sortierung:**
- Standard: Nach Erstellungsdatum (neueste zuerst)

**Filter (geplant):**
- Nach Status (Amazon/Shopify)
- Nach Preis-Range
- Search by Name

---

## 7️⃣ Export-Funktionalität

### Feature: CSV Export
Export von Produktlisting-Daten in CSV-Format (UTF-8 BOM für Excel-Kompatibilität).
- Einzelprodukt- und Batch-Export
- Streaming für große Datenmengen
- Download-Links in Index- & Show-Views

### Feature: JSON Export
Strukturierter Export mit Image-Zähler für API-Integrationen und Backup.
- Dateinamen mit Zeitstempel
- Dropdown-Menü für Export-Optionen

---

## 8️⃣ Keyword-Automatisierung (n8n + SE Ranking)

### Feature: Automatische Keyword-Generierung
**Trigger:** Produkterstellung → n8n Webhook
**Flow:** n8n → SE Ranking API → Keywords → PATCH /api/products/{id}/keywords

**Keyword-Quellen:**
- Export-Keywords (SE Ranking)
- Ähnliche Keywords
- Verwandte Keywords

### Feature: Keywords-Tab
**Location:** `resources/views/products/show.blade.php` (Tab 5)

Anzeige aller generierten Keywords mit:
- Übersichtliche Keyword-Liste
- "Zu Amazon übertragen" Button
- "Zu Shopify übertragen" Button
- Transfer fügt Keywords zu den jeweiligen Listing-Feldern hinzu

### Feature: REST-API für n8n
**Routes:** `routes/api.php` (Bearer-Token-geschützt)
- `GET /api/products` – Alle Produkte mit Keywords
- `PATCH /api/products/{id}/keywords` – Keywords aktualisieren
- `POST /api/products/{id}/images/upload` – Bild hochladen

---

## 9️⃣ Admin-Panel

### Feature: Benutzerverwaltung
**Route:** `GET /admin/users`
**Controller:** `AdminController`

Funktionen:
- Benutzerübersicht (Name, E-Mail, Produkt-Anzahl)
- Admin-Status vergeben/entziehen
- Benutzer löschen
- Admin-Middleware-Schutz (`is_admin` Flag)

---

## 🔟 Operations-Center

### Feature: Keyword-Analyse
**Route:** `POST /operations/keyword-analysis`  
**Controller:** `OperationsController::triggerKeywordAnalysis()`

Funktionen:
- SE Ranking API Integration
- Bulk-Keyword-Analyse für ausgewählte Produkte
- Rate Limiting: 10 Requests/Minute

### Feature: ASIN-Lookup
**Route:** `POST /operations/asin-lookup`  
**Controller:** `OperationsController::asinLookup()`

Status: Vorbereitet, deaktiviert (benötigt Amazon Seller Central Account)

---

## 1️⃣1️⃣ Google Drive Integration

### Feature: Automatischer Bild-Upload via n8n
**Workflow:** `n8n_gdrive_image_upload_workflow.json`

Funktionsweise:
1. Bild in Google Drive Ordner ablegen
2. Ordnername enthält Produkt-ID: `{id}`, `{id} - {name}`, oder `product_{id}`
3. n8n erkennt neues Bild → lädt es herunter → upload via API
4. "raw" im Ordner-/Dateinamen → Rohbild, sonst Produktbild

---

## 1️⃣2️⃣ Backup & Recovery

### Feature: Datenbank-Backup
**Command:** `php artisan db:backup`

Funktionen:
- Erstellt timestamped SQLite-Backup in `storage/backups/`
- Automatische Cleanup: Backups älter als 7 Tage werden gelöscht
- Konfigurierbar: `--keep=14` für 14 Tage Aufbewahrung
- Tägliches Auto-Backup via Scheduler (02:00 Uhr)

### Feature: Deployment
**Script:** `deploy.sh`

Einmaliger Befehl für komplettes Deployment:
Wartungsmodus → Git Pull → Composer → npm build → Migrate → Caches → Backup → Live

---

## 1️⃣3️⃣ Sicherheits-Features

| Feature | Details |
|---------|---------|
| API-Token Hashing | SHA-256, Klartext nur einmalig sichtbar |
| Rate Limiting | Login 5/min, API 60/min, Operations 10/min |
| Session-Verschlüsselung | `SESSION_ENCRYPT=true` |
| Mass-Assignment-Schutz | `user_id` nicht in `$fillable` |
| HTTPS in Produktion | Automatisch via `URL::forceScheme` |
| Custom Error Pages | 404, 419, 500, 503 — kein Stack-Trace-Leak |
| Vite-Build | Keine externen CDN-Abhängigkeiten |
| CSRF-Protection | Auf allen Formularen |
| Pagination | Produktliste limitiert auf 20/Seite |

---

## 🎯 Keyboard Shortcuts (geplant)

- `Ctrl+N` → Neues Produkt
- `Ctrl+S` → Speichern (im Edit-Modus)
- `Esc` → Zurück/Abbrechen

---

## �️ User Access Management (UAM)

### Feature: Konto-Sperrung (is_active)
**Middleware:** `EnsureUserIsActive`  
**Admin-Route:** `PATCH /admin/users/{user}/toggle-active`

**Verhalten:**
- Jeder Benutzer hat ein `is_active`-Flag (Standard: `true`)
- Die Middleware prüft das Flag bei **jedem** Request — gesperrte Nutzer werden sofort ausgeloggt
- Admin kann Konten über die Benutzerliste oder Detailseite sperren/entsperren
- **Selbst-Sperre verhindert:** Admin kann eigenes Konto nicht sperren

```php
// EnsureUserIsActive (Middleware)
if (Auth::check() && !Auth::user()->is_active) {
    Auth::logout();
    return redirect()->route('login')->withErrors(['email' => 'Konto deaktiviert.']);
}
```

---

### Feature: Login-Tracking (last_login_at)
**Controller:** `Auth\AuthenticatedSessionController::store()`  
**Feld:** `users.last_login_at` (timestamp, nullable)

**Verhalten:**
- Bei jedem erfolgreichen Login wird `last_login_at` auf `now()` gesetzt
- Wird im Admin-Panel in der Benutzerliste und Detailseite angezeigt (`diffForHumans()`)

---

### Feature: API-Token-Verwaltung (Profil)
**Route:** `GET /profile/tokens`, `POST /profile/tokens`, `DELETE /profile/tokens/{token}`  
**Controller:** `ApiTokenController`  
**View:** `resources/views/profile/tokens.blade.php`

**Funktionen:**
- Bis zu **10 Tokens** pro Benutzer
- Token-Name (required) + optionales Ablaufdatum
- Neuer Token wird **einmalig** als Flash-Nachricht angezeigt
- Token-Tabelle mit Status (Aktiv / Abgelaufen) und Widerruf-Aktion
- Link im Benutzer-Dropdown: "🔑 API-Tokens"

**Validierung:**
```php
'name'       => 'required|string|max:100',
'expires_at' => 'nullable|date|after:today',
```

---

### Feature: Token-Ablaufdatum (expires_at)
**Feld:** `api_tokens.expires_at` (timestamp, nullable)  
**Middleware:** `AuthenticateApiToken`

**Verhalten:**
- Tokens können beim Erstellen mit einem Ablaufdatum versehen werden
- Die API-Middleware prüft `isExpired()` → 401 bei abgelaufenem Token
- `ApiToken::isValid()` prüft: nicht abgelaufen AND Benutzer ist aktiv

---

### Feature: Admin-Token-Verwaltung
**Route:** `DELETE /admin/users/{user}/tokens/{token}`  
**Controller:** `Admin\UserController::revokeToken()`

**Verhalten:**
- Admin kann alle Tokens eines beliebigen Nutzers auf der Detailseite (`/admin/users/{id}`) einsehen
- Tabelle zeigt: Token-Name, Zuletzt genutzt, Läuft ab, Status, Widerruf-Button
- Token-Zugehörigkeit wird serverseitig geprüft (Ownership-Check → 404)

---

## �📱 Responsive Design

**Breakpoints:**
- 📱 Mobile (< 640px)
- 📱 Tablet (640px - 1024px)
- 💻 Desktop (> 1024px)

**Getestet auf:**
- iPhone 12/13/14
- iPad Pro
- Desktop (Chrome, Firefox, Safari)

---

**Zuletzt aktualisiert:** 28. April 2026
