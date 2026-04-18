<!-- Amazon Listing Section -->
@php
    $amazonListing = $product->amazon_listing ?? [];
@endphp
<div class="space-y-6">
    <h2 class="text-2xl font-bold text-gray-900 border-b pb-3">🏪 Amazon Listing</h2>

    <div class="bg-gray-50 border border-gray-300 rounded-lg p-4 mb-6">
        <p class="text-sm text-gray-900">
            <strong>Hinweis:</strong> Alle Felder hier werden später direkt zu Amazon übertragen. Stelle sicher, dass alles korrekt ist!
        </p>
    </div>

    <!-- Basic Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Amazon ASIN (optional)</label>
            <input type="text" name="amazon_listing[asin]" 
                   value="{{ old('amazon_listing.asin', $amazonListing['asin'] ?? '') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700"
                   placeholder="z.B. B08KQTS4M4">
            <p class="text-xs text-gray-500 mt-1">Falls vorhanden</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Amazon Titel *</label>
            <input type="text" name="amazon_listing[title]" required
                   value="{{ old('amazon_listing.title', $amazonListing['title'] ?? '') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700"
                   placeholder="Max. 200 Zeichen"
                   maxlength="200">
        </div>
    </div>

    <!-- Main Description -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Hauptbeschreibung *</label>
        <textarea name="amazon_listing[description]" rows="4" required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700"
                  placeholder="Detaillierte Produktbeschreibung für Amazon...">{{ old('amazon_listing.description', $amazonListing['description'] ?? '') }}</textarea>
        <p class="text-xs text-gray-500 mt-1">Berichte wichtige Features und Vorteile</p>
    </div>

    <!-- Bullet Points -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-3">Bullet Points (bis zu 5)</label>
        <div class="space-y-2" id="bullet-points-container">
            @php
                $bulletPoints = $amazonListing['bullet_points'] ?? [];
                if (!is_array($bulletPoints)) {
                    $bulletPoints = [];
                }
            @endphp
            @forelse($bulletPoints as $index => $point)
                <div class="flex items-start space-x-2">
                    <span class="text-gray-500 font-bold mt-2">•</span>
                    <input type="text" name="amazon_listing[bullet_points][]"
                           value="{{ $point }}"
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700"
                           placeholder="z.B. Premium Sound mit 40mm Treibern"
                           maxlength="500">
                    <button type="button" class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600 remove-bullet">
                        ✕
                    </button>
                </div>
            @empty
                @for($i = 0; $i < 3; $i++)
                    <div class="flex items-start space-x-2">
                        <span class="text-gray-500 font-bold mt-2">•</span>
                        <input type="text" name="amazon_listing[bullet_points][]"
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700"
                               placeholder="z.B. Premium Sound mit 40mm Treibern"
                               maxlength="500">
                        <button type="button" class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600 remove-bullet">
                            ✕
                        </button>
                    </div>
                @endfor
            @endforelse
        </div>
        <button type="button" id="add-bullet" class="mt-3 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
            + Bullet Point hinzufügen
        </button>
    </div>

    <!-- Keywords -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Amazon Keywords (für die Suche)</label>
        <textarea name="amazon_listing[keywords]" rows="3"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700"
                  placeholder="Komma-getrennte Keywords, z.B.: wireless kopfhörer, bluetooth headphones, noise cancellation">{{ old('amazon_listing.keywords', $amazonListing['keywords'] ?? '') }}</textarea>
        <p class="text-xs text-gray-500 mt-1">Getrennt durch Kommas - max. 250 Zeichen</p>
    </div>

    <!-- Additional Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Amazon Kategorie</label>
            <input type="text" name="amazon_listing[category]"
                   value="{{ old('amazon_listing.category', $amazonListing['category'] ?? '') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700"
                   placeholder="z.B. Elektronik > Audio & Video > Kopfhörer">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Brand / Hersteller</label>
            <input type="text" name="amazon_listing[brand]"
                   value="{{ old('amazon_listing.brand', $amazonListing['brand'] ?? '') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700"
                   placeholder="z.B. YourBrand">
        </div>
    </div>

    <!-- Status -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
        <select name="amazon_listing[status]"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700">
            <option value="">-- Wählen --</option>
            <option value="draft" {{ old('amazon_listing.status', $amazonListing['status'] ?? '') === 'draft' ? 'selected' : '' }}>
                📝 Entwurf
            </option>
            <option value="ready" {{ old('amazon_listing.status', $amazonListing['status'] ?? '') === 'ready' ? 'selected' : '' }}>
                ✓ Bereit zum Hochladen
            </option>
            <option value="published" {{ old('amazon_listing.status', $amazonListing['status'] ?? '') === 'published' ? 'selected' : '' }}>
                🟢 Auf Amazon veröffentlicht
            </option>
        </select>
    </div>

    <!-- Notes -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Interne Amazon-Notizen</label>
        <textarea name="amazon_listing[notes]" rows="2"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700"
                  placeholder="z.B. 'Preisanpassung geplant', 'Wartet auf Fotos'">{{ old('amazon_listing.notes', $amazonListing['notes'] ?? '') }}</textarea>
    </div>

    @if($product->amazon_synced_at)
        <div class="bg-green-50 border border-green-200 rounded-lg p-3">
            <p class="text-sm text-green-800">
                <strong>✓ Zuletzt synchronisiert:</strong> {{ $product->amazon_synced_at->format('d.m.Y H:i') }}
            </p>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addBulletBtn = document.getElementById('add-bullet');
    const bulletContainer = document.getElementById('bullet-points-container');

    addBulletBtn?.addEventListener('click', function() {
        if (bulletContainer.querySelectorAll('input').length < 5) {
            const html = `
                <div class="flex items-start space-x-2">
                    <span class="text-gray-500 font-bold mt-2">•</span>
                    <input type="text" name="amazon_listing[bullet_points][]"
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700"
                           placeholder="z.B. Premium Sound mit 40mm Treibern"
                           maxlength="500">
                    <button type="button" class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600 remove-bullet">
                        ✕
                    </button>
                </div>
            `;
            bulletContainer.insertAdjacentHTML('beforeend', html);
            attachRemoveListeners();
        } else {
            alert('Maximum 5 Bullet Points erlaubt!');
        }
    });

    function attachRemoveListeners() {
        document.querySelectorAll('.remove-bullet').forEach(btn => {
            btn.removeEventListener('click', removeBullet);
            btn.addEventListener('click', removeBullet);
        });
    }

    function removeBullet(e) {
        e.preventDefault();
        this.parentElement.remove();
    }

    attachRemoveListeners();
});
</script>
