# Amazon SP-API Setup (Seller Central)

Damit die Amazon-Integration funktioniert, müssen folgende Schritte durchgeführt werden:

## 1. Amazon SP-API App registrieren
- Gehe zu https://sellercentral.amazon.de/apps/credentials
- Klicke auf "Neue App registrieren"
- Name: z.B. "Product Planner"
- OAuth-Flow: "Self Authorization"
- Berechtigungen:
  - **Catalog Items** (lesen)
  - **Listings Items** (lesen/schreiben, falls Listing-Upload gewünscht)
  - **Product Pricing** (optional, für Preis-Monitoring)
- Speichere die App und notiere:
  - Client ID
  - Client Secret
  - Refresh Token (nach OAuth-Flow generieren)

## 2. OAuth-Flow durchführen (Self Authorization)
- Klicke in der App auf "Self-Authorization"
- Folge dem Flow, um einen **Refresh Token** zu erhalten
- Notiere den Token (wird nur einmal angezeigt!)

## 3. .env konfigurieren
Trage die Werte in deine `.env` ein:

```
SP_API_CLIENT_ID=deine-client-id
SP_API_CLIENT_SECRET=dein-client-secret
SP_API_REFRESH_TOKEN=dein-refresh-token
SP_API_MARKETPLACE_ID=A1PA6795UKMFR9 # DE Marketplace
SP_API_ENDPOINT=https://sellingpartnerapi-eu.amazon.com
```

## 4. Verbindung testen
Führe folgenden Artisan-Befehl aus:

```
php artisan spapi:test B08N5WRWNW
```

Erwartet wird eine Produkt-Info-Ausgabe. Bei 403-Fehler: Prüfe die App-Berechtigungen und den Refresh Token.

---

**Tipp:**
- Die SP-API ist sehr restriktiv. Nach jeder Änderung an der App (Berechtigungen!) muss ggf. ein neuer Refresh Token generiert werden.
- Die Logs findest du in `storage/logs/laravel-*.log`.
