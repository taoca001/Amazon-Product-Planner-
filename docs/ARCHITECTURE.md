# 🏗️ Architektur & Datenbankschema

Technische Übersicht der Applikation: Datenbankschema, Modell-Beziehungen und Architektur-Patterns.

---

## 📊 Datenbankschema

```
┌─────────────────────────────────────┐
│           USERS                     │
├─────────────────────────────────────┤
│ PK  id                              │
│     email (UNIQUE)                  │
│     password (hashed)               │
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
│     raw_images_path                (VARCHAR, nullable)     │
│     product_images_path            (VARCHAR, nullable)     │
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

---

## 📁 Ordnerstruktur

```
amazon-product-planner/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ProductController.php       (REST CRUD)
│   │   │   ├── ProductImageController.php  (Upload/Delete)
│   │   │   └── AuthController.php          (Login/Register)
│   │   ├── Middleware/
│   │   │   └── Authenticate.php
│   │   └── Requests/                       (Form Validation)
│   │
│   ├── Models/
│   │   ├── User.php                        (Auth User)
│   │   ├── Product.php                     (Hauptmodell)
│   │   └── ProductImage.php                (Bilder)
│   │
│   ├── Policies/
│   │   └── ProductPolicy.php               (Authorization)
│   │
│   ├── Exceptions/
│   └── Traits/
│
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   └── filesystems.php
│
├── database/
│   ├── migrations/
│   │   ├── create_products_table.php
│   │   └── create_product_images_table.php
│   ├── seeders/
│   │   └── ProductSeeder.php
│   └── factories/
│
├── routes/
│   └── web.php                             (Route Definitionen)
│
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   └── base.blade.php              (Master Layout)
│   │   ├── products/
│   │   │   ├── index.blade.php             (Produktliste)
│   │   │   ├── create.blade.php            (Neues Produkt)
│   │   │   ├── show.blade.php              (Bearbeiten/Detail)
│   │   │   └── sections/
│   │   │       ├── amazon-listing.blade.php
│   │   │       └── shopify-listing.blade.php
│   │   ├── auth/
│   │   │   ├── login.blade.php
│   │   │   ├── register.blade.php
│   │   │   └── reset-password.blade.php
│   │   └── dashboard.blade.php
│   │
│   ├── css/
│   │   └── app.css
│   │
│   └── js/
│       └── app.js
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

## 🔄 Request Flow (MVC)

```
┌─────────────────────────────────────────────────────┐
│  1. HTTP Request (GET/POST/PUT/DELETE)              │
│     Browser → routes/web.php                        │
└─────────────────────┬───────────────────────────────┘
                      ↓
┌─────────────────────────────────────────────────────┐
│  2. Routing & Middleware                            │
│     - CSRF Protection (web.php middleware)          │
│     - Authentication Check                          │
│     - Session Handler                               │
└─────────────────────┬───────────────────────────────┘
                      ↓
┌─────────────────────────────────────────────────────┐
│  3. Controller Action (ProductController.php)       │
│     - Authorization Check (ProductPolicy)           │
│     - Validation (Request::validate)                │
│     - Database Query (Eloquent ORM)                 │
└─────────────────────┬───────────────────────────────┘
                      ↓
┌─────────────────────────────────────────────────────┐
│  4. Model Interaction (Product.php)                 │
│     - Database Transaction                          │
│     - JSON Casting (amazon_listing, keywords)       │
│     - Relationships (rawImages(), productImages())  │
└─────────────────────┬───────────────────────────────┘
                      ↓
┌─────────────────────────────────────────────────────┐
│  5. View Rendering (Blade Template)                 │
│     - resources/views/products/show.blade.php       │
│     - Partial includes (amazon-listing.blade.php)   │
└─────────────────────┬───────────────────────────────┘
                      ↓
┌─────────────────────────────────────────────────────┐
│  6. HTTP Response                                   │
│     Browser ← HTML/Redirect                         │
└─────────────────────────────────────────────────────┘
```

---

## 🔐 Authorization & Policy

Basiert auf Laravel's Policy-System:

```php
// In ProductPolicy.php:
public function update(User $user, Product $product): bool {
    return $user->id === $product->user_id;
}

// In Controller:
if (auth()->user()->id !== $product->user_id) {
    abort(403, 'Unauthorized');
}
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
6. **Sessions:** Laravel Session Handler

---

**Zuletzt aktualisiert:** 17. April 2026
