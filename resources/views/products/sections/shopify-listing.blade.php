<!-- Shopify Listing Section -->
@php
    $shopifyListing = $product->shopify_listing ?? [];
@endphp
<div class="space-y-6">
    <h2 class="text-2xl font-bold text-gray-900 border-b pb-3">🛍️ Shopify Listing</h2>

    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
        <p class="text-sm text-green-800">
            <strong>Hinweis:</strong> Erstelle separate Listings für deinen Shopify-Shop. Du kannst unterschiedliche Preise und Beschreibungen verwenden!
        </p>
    </div>

    <!-- Basic Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Shopify Produkt-ID (optional)</label>
            <input type="text" name="shopify_listing[product_id]" 
                   value="{{ old('shopify_listing.product_id', $shopifyListing['product_id'] ?? '') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                   placeholder="z.B. 1234567890">
            <p class="text-xs text-gray-500 mt-1">Falls bereits auf Shopify vorhanden</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Shopify Titel *</label>
            <input type="text" name="shopify_listing[title]" required
                   value="{{ old('shopify_listing.title', $shopifyListing['title'] ?? '') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                   placeholder="Produkttitel für Shopify">
        </div>
    </div>

    <!-- Shopify Price -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Shopify Preis (€)</label>
            <div class="relative">
                <span class="absolute left-4 top-2 text-gray-500">€</span>
                <input type="number" name="shopify_listing[price]" step="0.01" min="0"
                       value="{{ old('shopify_listing.price', $shopifyListing['price'] ?? '') }}"
                       class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                       placeholder="0.00">
            </div>
            <p class="text-xs text-gray-500 mt-1">Kann anders als Amazon-Preis sein</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Vergleichender Preis (€, optional)</label>
            <div class="relative">
                <span class="absolute left-4 top-2 text-gray-500">€</span>
                <input type="number" name="shopify_listing[compare_price]" step="0.01" min="0"
                       value="{{ old('shopify_listing.compare_price', $shopifyListing['compare_price'] ?? '') }}"
                       class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                       placeholder="0.00">
            </div>
            <p class="text-xs text-gray-500 mt-1">Ursprünglicher Preis (für Rabattanzeige)</p>
        </div>
    </div>

    <!-- Description -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Produktbeschreibung *</label>
        <textarea name="shopify_listing[description]" rows="5" required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                  placeholder="HTML unterstützt - Beschreibe das Produkt für deine Shopify-Kunden...">{{ old('shopify_listing.description', $shopifyListing['description'] ?? '') }}</textarea>
        <p class="text-xs text-gray-500 mt-1">Kann HTML-Formatierung enthalten</p>
    </div>

    <!-- SKU & Barcode -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">SKU (Stock Keeping Unit)</label>
            <input type="text" name="shopify_listing[sku]"
                   value="{{ old('shopify_listing.sku', $shopifyListing['sku'] ?? '') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                   placeholder="z.B. WH-PRO-2024">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Barcode / EAN (optional)</label>
            <input type="text" name="shopify_listing[barcode]"
                   value="{{ old('shopify_listing.barcode', $shopifyListing['barcode'] ?? '') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                   placeholder="z.B. 1234567890123">
        </div>
    </div>

    <!-- Collections / Tags -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Tags / Kategorien</label>
        <input type="text" name="shopify_listing[tags]"
               value="{{ old('shopify_listing.tags', $shopifyListing['tags'] ?? '') }}"
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
               placeholder="Komma-getrennt: z.B. audio, headphones, wireless">
        <p class="text-xs text-gray-500 mt-1">Hilft bei der Produktsuche in deinem Shop</p>
    </div>

    <!-- Inventory -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Lagermenge</label>
            <input type="number" name="shopify_listing[inventory_quantity]" min="0"
                   value="{{ old('shopify_listing.inventory_quantity', $shopifyListing['inventory_quantity'] ?? '') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                   placeholder="0">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Gewicht (g)</label>
            <input type="number" name="shopify_listing[weight]" step="0.1" min="0"
                   value="{{ old('shopify_listing.weight', $shopifyListing['weight'] ?? '') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                   placeholder="0">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Veröffentlicht?</label>
            <select name="shopify_listing[published]"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                <option value="">-- Wählen --</option>
                <option value="true" {{ old('shopify_listing.published', $shopifyListing['published'] ?? '') === 'true' ? 'selected' : '' }}>
                    🟢 Veröffentlicht
                </option>
                <option value="false" {{ old('shopify_listing.published', $shopifyListing['published'] ?? '') === 'false' ? 'selected' : '' }}>
                    🔴 Versteckt
                </option>
            </select>
        </div>
    </div>

    <!-- Status -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
        <select name="shopify_listing[status]"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
            <option value="">-- Wählen --</option>
            <option value="draft" {{ old('shopify_listing.status', $shopifyListing['status'] ?? '') === 'draft' ? 'selected' : '' }}>
                📝 Entwurf
            </option>
            <option value="ready" {{ old('shopify_listing.status', $shopifyListing['status'] ?? '') === 'ready' ? 'selected' : '' }}>
                ✓ Bereit zum Hochladen
            </option>
            <option value="published" {{ old('shopify_listing.status', $shopifyListing['status'] ?? '') === 'published' ? 'selected' : '' }}>
                🟢 Auf Shopify veröffentlicht
            </option>
        </select>
    </div>

    <!-- Notes -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Interne Shopify-Notizen</label>
        <textarea name="shopify_listing[notes]" rows="2"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                  placeholder="z.B. 'Mit Rabatt im Sale', 'Limitierte Auflage'">{{ old('shopify_listing.notes', $shopifyListing['notes'] ?? '') }}</textarea>
    </div>

    @if($product->shopify_synced_at)
        <div class="bg-green-50 border border-green-200 rounded-lg p-3">
            <p class="text-sm text-green-800">
                <strong>✓ Zuletzt synchronisiert:</strong> {{ $product->shopify_synced_at->format('d.m.Y H:i') }}
            </p>
        </div>
    @endif
</div>
