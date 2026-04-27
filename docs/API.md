# 🔌 API-Dokumentation

REST API Referenz für den Amazon Product Planner.

---

## 📍 Base URL

```
Web:  http://localhost:8000
API:  http://localhost:8000/api
```

---

## 🔐 Authentifizierung

### Web-Requests
Session-basiert via Cookies (CSRF-Token erforderlich).

### API-Requests (für n8n / externe Systeme)
Alle API-Endpoints (`/api/*`) erfordern einen Bearer-Token:

```bash
curl -H "Authorization: Bearer pplan_..." http://localhost:8000/api/products
```

Tokens werden als **SHA-256 Hash** in der `api_tokens`-Tabelle gespeichert. Der Klartext-Token wird nur einmalig bei der Erstellung angezeigt.

#### Token erstellen
```bash
php artisan api:token {user_id} --name="n8n"
```

#### Rate Limiting
- API-Endpoints: **60 Requests/Minute** (pro Token)
- Antwort bei Überschreitung: `429 Too Many Requests`

#### Autorisierung
Jeder API-Endpoint prüft, ob der Token-Besitzer (`api_token.user_id`) auch der Besitzer des angeforderten Produkts ist. Bei Mismatch: `403 Forbidden`.

---

## 🔗 API Endpoints (Bearer-Token-Auth)

### GET /api/products
**Beschreibung:** Alle Produkte mit Keywords abrufen
**Authentifizierung:** Bearer-Token

**Response (200):**
```json
[
  {
    "id": 1,
    "name": "Wireless Kopfhörer Pro",
    "keywords": ["wireless", "headphones"],
    "amazon_listing": {...},
    "shopify_listing": {...}
  }
]
```

---

### PATCH /api/products/{id}/keywords
**Beschreibung:** Keywords eines Produkts aktualisieren (z.B. von n8n nach SE Ranking Abfrage)
**Authentifizierung:** Bearer-Token

**Request Body (JSON):**
```json
{
  "keywords": ["wireless headphones", "bluetooth audio", "noise cancellation"]
}
```

**Response (200):**
```json
{
  "message": "Keywords updated",
  "product": {...}
}
```

---

### POST /api/products/{id}/images/upload
**Beschreibung:** Produktbild hochladen (z.B. von n8n nach Google Drive Download)
**Authentifizierung:** Bearer-Token
**Content-Type:** multipart/form-data

**Form Data:**
- `image` (required): Bilddatei
- `type` (optional): `raw` | `product` (default: `product`)

**Response (200):**
```json
{
  "message": "Image uploaded",
  "image": {
    "id": 5,
    "file_name": "photo.jpg",
    "url": "/storage/products/1/product/photo.jpg"
  }
}
```

---

## 📌 Web-Endpoints (Session/Cookie-Auth)

### GET /products
**Beschreibung:** Alle Produkte des aktuellen Benutzers abrufen  
**Authentifizierung:** ✅ Required

**Response (200):**
```json
{
  "products": [
    {
      "id": 1,
      "name": "Wireless Kopfhörer Pro",
      "description": "Premium Wireless Kopfhörer...",
      "price": 199.99,
      "keywords": ["wireless", "headphones"],
      "amazon_listing": {...},
      "shopify_listing": {...},
      "created_at": "2026-04-17T00:39:59Z",
      "updated_at": "2026-04-17T09:02:55Z"
    }
  ]
}
```

---

### GET /products/create
**Beschreibung:** Formular zur Produkterstellung anzeigen  
**Authentifizierung:** ✅ Required  
**Content-Type:** HTML (Blade Template)

---

### POST /products
**Beschreibung:** Neues Produkt erstellen  
**Authentifizierung:** ✅ Required  
**Content-Type:** application/x-www-form-urlencoded

**Request Body:**
```
POST /products HTTP/1.1

name=Wireless Kopfhörer&
description=Premium Sound...&
price=199.99&
keywords[]=wireless&
keywords[]=headphones&
notes=Ready for listing
```

**Response (201):**
```json
{
  "id": 1,
  "name": "Wireless Kopfhörer",
  "user_id": 1,
  "created_at": "2026-04-17T09:03:00Z"
}
```

**Error (422):**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "name": ["The name field is required."],
    "price": ["The price must be a number."]
  }
}
```

---

### GET /products/{id}
**Beschreibung:** Einzelnes Produkt anzeigen/bearbeiten  
**Authentifizierung:** ✅ Required  
**Content-Type:** HTML (Blade Template)

**URL Parameters:**
- `id` (required): Product ID

**Response:** HTML Edit-Form mit allen Feldern

**Error (404):** "Product not found"  
**Error (403):** "Unauthorized - Not your product"

---

### PUT /products/{id}
**Beschreibung:** Produkt aktualisieren  
**Authentifizierung:** ✅ Required  
**Content-Type:** application/x-www-form-urlencoded

**Request Body:**
```
PUT /products/1 HTTP/1.1

_method=PUT&
name=Updated Name&
description=New description&
price=249.99&
amazon_listing[title]=Neuer Amazon Titel&
amazon_listing[description]=Neue Beschreibung&
shopify_listing[title]=Neuer Shopify Titel&
keywords[]=new_keyword
```

**Response (200):**
```json
{
  "message": "Produkt aktualisiert!",
  "id": 1
}
```

---

### DELETE /products/{id}
**Beschreibung:** Produkt löschen  
**Authentifizierung:** ✅ Required

**URL Parameters:**
- `id` (required): Product ID

**Response (200):**
```json
{
  "message": "Produkt gelöscht!"
}
```

**Error (404):** "Product not found"  
**Error (403):** "Unauthorized"

---

## 📸 ProductImages Endpoints

### POST /products/{id}/images
**Beschreibung:** Bilder hochladen (Multipart)  
**Authentifizierung:** ✅ Required  
**Content-Type:** multipart/form-data

**URL Parameters:**
- `id` (required): Product ID

**Form Data:**
```
POST /products/1/images HTTP/1.1
Content-Type: multipart/form-data

--boundary
Content-Disposition: form-data; name="images[]"; filename="photo1.jpg"
Content-Type: image/jpeg

[binary image data]
--boundary
Content-Disposition: form-data; name="images[]"; filename="photo2.jpg"
Content-Type: image/jpeg

[binary image data]
--boundary--
```

**Query Parameter:**
- `type` (required): `raw` | `product`

**Response (200):**
```json
{
  "success": true,
  "images": [
    {
      "id": 1,
      "file_name": "photo1.jpg",
      "file_size": 524288,
      "url": "/storage/products/1/raw/photo1.jpg",
      "type": "raw"
    }
  ]
}
```

**Error (422):**
```json
{
  "error": "File must be an image (PNG, JPG, GIF). Max 10MB."
}
```

---

### DELETE /products/{productId}/images/{imageId}
**Beschreibung:** Einzelnes Bild löschen  
**Authentifizierung:** ✅ Required

**URL Parameters:**
- `productId` (required): Product ID
- `imageId` (required): ProductImage ID

**Response (200):**
```json
{
  "success": true,
  "message": "Image deleted"
}
```

**Error (404):** "Image not found"  
**Error (403):** "Unauthorized"

---

## 🔑 Authentication Endpoints

### GET /login
**Beschreibung:** Login-Formular anzeigen  
**Authentifizierung:** ❌ Not Required (nur für nicht-authentifizierte Benutzer)

---

### POST /login
**Beschreibung:** Benutzer einloggen  
**Authentifizierung:** ❌ Not Required  
**Content-Type:** application/x-www-form-urlencoded

**Request Body:**
```
POST /login HTTP/1.1

email=test@example.com&
password=password&
remember=on
```

**Response (302 Redirect):**
- Success: Redirect zu `/dashboard`
- Failure: Redirect zurück zu `/login` mit Fehler-Message

---

### POST /register
**Beschreibung:** Neuen Benutzer registrieren  
**Authentifizierung:** ❌ Not Required

**Request Body:**
```
POST /register HTTP/1.1

name=John Doe&
email=john@example.com&
password=password&
password_confirmation=password
```

**Response (302 Redirect):**
- Success: Auto-Login + Redirect zu `/dashboard`
- Failure: Redirect zurück mit Validation-Errors

---

### POST /logout
**Beschreibung:** Benutzer abmelden  
**Authentifizierung:** ✅ Required

**Response (302 Redirect):** zu `/`

---

## 📊 HTTP Status Codes

| Code | Bedeutung |
|------|-----------|
| 200 | OK - Anfrage erfolgreich |
| 201 | Created - Ressource erstellt |
| 302 | Redirect - Weitergeleitet |
| 400 | Bad Request - Ungültige Anfrage |
| 401 | Unauthorized - Login erforderlich |
| 403 | Forbidden - Keine Berechtigung |
| 404 | Not Found - Ressource nicht gefunden |
| 422 | Unprocessable Entity - Validierungsfehler |
| 500 | Internal Server Error - Serverfehler |

---

## 🔄 Pagination (geplant)

```
GET /products?page=1&per_page=20

Response:
{
  "data": [...],
  "current_page": 1,
  "last_page": 5,
  "total": 100,
  "per_page": 20
}
```

---

## 🔍 Filtering (geplant)

```
GET /products?status=ready&price_min=100&price_max=500&sort=price_asc
```

---

## 📄 Response Format

Alle Responses folgen diesem Schema:

**Success:**
```json
{
  "success": true,
  "data": {...},
  "message": "Operation successful"
}
```

**Error:**
```json
{
  "success": false,
  "error": "Error message",
  "errors": {
    "field": ["Error detail"]
  }
}
```

---

## 🧪 API Testing

### Mit cURL
```bash
# Login
curl -X POST http://localhost:8000/login \
  -d "email=test@example.com&password=password"

# Produkte abrufen
curl -X GET http://localhost:8000/products \
  -H "Cookie: XSRF-TOKEN=...; laravel_session=..."

# Produkt erstellen
curl -X POST http://localhost:8000/products \
  -d "name=Test&description=Test&price=99.99"

# Bild hochladen
curl -X POST http://localhost:8000/products/1/images \
  -F "images[]=@photo.jpg"
```

### Mit Postman
1. Öffne Postman
2. Erstelle Collection "Amazon Product Planner"
3. Importiere die Endpoints
4. Setze Base URL: `http://localhost:8000`
5. Authentifiziere dich zuerst via POST /login

---

## 🔐 CORS (Cross-Origin Resource Sharing)

Aktuell: **Nicht aktiviert** (für Frontend-Integration geplant)
---

**Zuletzt aktualisiert:** 18. April 2026
Future:
```php
// config/cors.php
'paths' => ['api/*'],
'allowed_methods' => ['*'],
'allowed_origins' => ['https://yourdomain.com'],
```

---

**Zuletzt aktualisiert:** 17. April 2026
