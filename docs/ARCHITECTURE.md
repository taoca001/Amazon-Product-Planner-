# 🏗️ Architektur & Datenbankschema

Technische Übersicht der Applikation: Datenbankschema, Modell-Beziehungen und Architektur-Patterns.

---

## 📊 Datenbankschema

```
┌─────────────────────────────────────┐
│           USERS                     │
├─────────────────────────────────────┤
│ PK  id                              │
│     name                            │
│     email (UNIQUE)                  │
│     password (hashed)               │
│     is_admin (BOOLEAN, default 0)   │
│     created_at                      │
│     updated_at                      │
└────────────┬────────────────────────┘
             │
             │ 1:N (HasMany)
             ↓
┌─────────────────────────────────────────────────────────────┐
│                    PRODUCTS                                 │
├─────────────────────────────────────────────────────────────┤
│ PK  id                                                      │
│ FK  user_id                                                 │
│     name                           (VARCHAR 255)           │
│     description                    (TEXT, nullable)        │
│     price                          (DECIMAL 10,2)          │
│     keywords                       (JSON array)            │
│     notes                          (TEXT, nullable)        │
│     amazon_listing                 (JSON object)           │
│     shopify_listing                (JSON object)           │
│     amazon_synced_at               (TIMESTAMP, nullable)   │
│     shopify_synced_at              (TIMESTAMP, nullable)   │
│     exported_at                    (TIMESTAMP, nullable)   │
│     created_at                                             │
│     updated_at                                             │
└────────┬────────────────────────────────────────────────────┘
         │
         │ 1:N (HasMany)
         ↓
┌──────────────────────────────────────┐
│      PRODUCT_IMAGES                  │
├──────────────────────────────────────┤
│ PK  id                               │
│ FK  product_id                       │
│     type                 (ENUM)      │
│       - 'raw'                        │
│       - 'product'                    │
│     file_path            (VARCHAR)   │
│     file_name            (VARCHAR)   │
│     file_size            (BIGINT)    │
│     mime_type            (VARCHAR)   │
│     order                 (INT)       │
│     created_at                       │
│     updated_at                       │
└──────────────────────────────────────┘

┌──────────────────────────────────────┐
│      API_TOKENS                      │
├──────────────────────────────────────┤
│ PK  id                               │
│     name          (VARCHAR)          │
│     token         (VARCHAR, UNIQUE)  │
│     last_used_at  (TIMESTAMP, null)   │
│     created_at                       │
│     updated_at                       │
└──────────────────────────────────────┘
```

---

## 📌 Modell-Beziehungen

### User Model
```php
class User extends Model {
    // 1:N Beziehung zu Products
    public function products(): HasMany {
        return $this->hasMany(Product::class);
    }
}
```

### Product Model
```php
class Product extends Model {
    // N:1 Beziehung zu User
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
    
    // 1:N Beziehung zu ProductImages
    public function images(): HasMany {
        return $this->hasMany(ProductImage::class);
    }
    
    // Convenience Methods
    public function rawImages(): HasMany {
        return $this->images()->where('type', 'raw');
    }
    
    public function productImages(): HasMany {
        return $this->images()->where('type', 'product');
    }
}
```

### ProductImage Model
```php
class ProductImage extends Model {
    // N:1 Beziehung zu Product
    public function product(): BelongsTo {
        return $this->belongsTo(Product::class);
    }
}
```

### ApiToken Model
```php
class ApiToken extends Model {
    // Token für externe API-Zugriffe (n8n, etc.)
    // Felder: name, token (unique), last_used_at
}
```

---

## 📁 Ordnerstruktur

```
amazon-product-planner/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ProductController.php       (REST CRUD + Export)
│   │   │   ├── ProductImageController.php  (Upload/Delete)
│   │   │   ├── DashboardController.php     (Dashboard)
│   │   │   ├── AdminController.php         (Admin Panel)
│   │   │   └── Api/
│   │   │       ├── ProductKeywordController.php   (Keyword-API)
│   │   │       └── ProductImageUploadController.php (Bild-Upload-API)
│   │   └── Middleware/
│   │       └── AuthenticateApiToken.php   (Bearer-Token-Auth)
│   │
│   ├── Models/
│   │   ├── User.php                        (Auth-Benutzer)
│   │   ├── Product.php                     (Hauptmodell)
│   │   ├── ProductImage.php                (Bilder)
│   │   └── ApiToken.php                    (API-Tokens)
│   │
│   ├── Policies/
│   │   └── ProductPolicy.php               (Owner + Admin)
│   │
│   └── Services/
│       └── SeRankingService.php            (SE Ranking API)
│
├── routes/
│   ├── web.php                             (Web-Routes)
│   └── api.php                             (API-Routes, Bearer-Token)
│
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php               (Auth Layout)
│       │   ├── base.blade.php              (Produkt Layout)
│       │   ├── guest.blade.php             (Guest Layout)
│       │   ├── navigation.blade.php        (Nav-Partial)
│       │   └── partials/
│       │       ├── head.blade.php          (Konsolidierter Head)
│       │       └── keyword-js.blade.php    (Keyword-JavaScript)
│       ├── products/
│       │   ├── index.blade.php             (Produktliste)
│       │   ├── create.blade.php            (Neues Produkt)
│       │   ├── show.blade.php              (Detail + Tabs)
│       │   └── sections/
│       │       └── amazon-listing.blade.php
│       ├── admin/
│       │   └── users/
│       │       └── index.blade.php         (Benutzerverwaltung)
│       └── dashboard.blade.php
│
├── database/
│   └── migrations/
│       ├── create_products_table.php
│       ├── create_product_images_table.php
│       └── create_api_tokens_table.php
│
├── n8n_google_drive_to_product_image_upload.json  (n8n Workflow)
└── docs/
```
│
├── storage/
│   ├── app/
│   │   ├── public/
│   │   │   └── products/
│   │   │       └── {product_id}/
│   │   │           ├── raw/                (Rohmaterial-Bilder)
│   │   │           └── product/            (Produktbilder)
│   │   └── private/
│   │
│   ├── framework/
│   │   ├── cache/
│   │   ├── sessions/
│   │   └── views/
│   │
│   └── logs/
│       └── laravel.log
│
├── public/
│   ├── index.php                           (Entry Point)
│   ├── storage/ → ../storage/app/public    (Symlink)
│   ├── css/
│   └── js/
│
├── tests/
│   ├── Feature/
│   │   ├── ProductTest.php
│   │   └── AuthTest.php
│   └── Unit/
│
├── docs/
│   ├── INSTALLATION.md
│   ├── ARCHITECTURE.md
│   ├── FEATURES.md
│   ├── API.md
│   └── CHANGELOG.md
│
├── .env.example
├── .env                                    (gitignored)
├── .gitignore
├── composer.json
├── artisan
└── README.md
```

---

## 🔄 Request Flow

### Web-Request (MVC)
```
Browser → routes/web.php → Middleware (CSRF, Auth)
  → Controller → $this->authorize() (Policy)
  → Eloquent ORM → Blade View → HTML Response
```

### API-Request (n8n / externe Systeme)
```
n8n Webhook → routes/api.php → Middleware (api.token)
  → AuthenticateApiToken (Bearer-Token-Prüfung)
  → Api\Controller → JSON Response
```

---

## 🔐 Authorization & Policy

Basiert auf Laravel's Policy-System mit Owner + Admin Check:

```php
// In ProductPolicy.php:
public function update(User $user, Product $product): bool {
    return $user->is_admin || $user->id === $product->user_id;
}

// In Controller ($this->authorize statt manueller Check):
$this->authorize('update', $product);
```

### API-Authentifizierung

Externe Systeme (n8n) authentifizieren sich via Bearer-Token:

```php
// AuthenticateApiToken Middleware
// Header: Authorization: Bearer pplan_...
// Token wird gegen api_tokens-Tabelle geprüft
```

---

## 📦 JSON Felder im Detail

### amazon_listing (JSON)
```json
{
    "asin": "B08KQT5SZ9",
    "title": "Wireless Kopfhörer Pro",
    "description": "Premium Sound mit...",
    "bullet_points": [
        "Aktive Geräuschunterdrückung",
        "30 Stunden Akkulaufzeit",
        "Bluetooth 5.0",
        "Premium Design",
        "Schnelles Laden"
    ],
    "keywords": "wireless, kopfhörer, bluetooth, noise cancellation",
    "category": "Electronics > Audio & Video",
    "brand": "YourBrand",
    "status": "ready",
    "notes": "Ready for upload"
}
```

### shopify_listing (JSON)
```json
{
    "product_id": "gid://shopify/Product/1234567890",
    "title": "Wireless Headphones Pro",
    "price": 199.99,
    "compare_price": 249.99,
    "description": "<p>Premium Sound...</p>",
    "sku": "WH-PRO-2024",
    "barcode": "1234567890123",
    "tags": "audio,headphones,wireless",
    "inventory_quantity": 50,
    "weight": 250,
    "published": true,
    "status": "published"
}
```

### keywords (JSON Array)
```json
[
    "wireless headphones",
    "bluetooth audio",
    "noise cancellation",
    "premium sound"
]
```

---

## 🔄 File Upload Flow

```
┌────────────────────┐
│ Drag & Drop Files  │
└─────────┬──────────┘
          ↓
┌────────────────────────────────────┐
│ JavaScript (base.blade.php)        │
│ - Dropzone Event Handler           │
│ - FormData.append(files)           │
└─────────┬────────────────────────┘
          ↓
┌────────────────────────────────────┐
│ POST /products/{id}/images         │
│ ProductImageController@store       │
└─────────┬────────────────────────┘
          ↓
┌────────────────────────────────────┐
│ Validation & Authorization         │
│ - File MIME type check             │
│ - File size check (max 10MB)       │
│ - Owner verification               │
└─────────┬────────────────────────┘
          ↓
┌────────────────────────────────────┐
│ File Storage                       │
│ storage/app/public/products/       │
│   {product_id}/{type}/{filename}   │
└─────────┬────────────────────────┘
          ↓
┌────────────────────────────────────┐
│ Database Record                    │
│ INSERT INTO product_images         │
└─────────┬────────────────────────┘
          ↓
┌────────────────────────────────────┐
│ JSON Response                      │
│ { success: true, image_url: ... }  │
└────────────────────────────────────┘
```

---

## 🛡️ Security Patterns

1. **CSRF Protection:** Token in allen Forms
2. **SQL Injection:** Eloquent Parameterized Queries
3. **Authorization:** Policy-basierte Checks
4. **File Validation:** MIME-Type & Size Checks
5. **Password:** Bcrypt Hashing
6. **API Token:** Bearer-Token-Middleware für externe API-Zugriffe

---

**Zuletzt aktualisiert:** 18. April 2026
