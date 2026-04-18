@extends('layouts.base')

@section('title', $product->name . ' - Product Planner')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <!-- Header -->
    <div class="px-6 py-3 border-b border-gray-200 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900">{{ $product->name }}</h1>
            <p class="text-gray-400 text-xs mt-0.5">Erstellt: {{ $product->created_at->format('d.m.Y H:i') }} &middot; Bearbeitet: {{ $product->updated_at->format('d.m.Y H:i') }}</p>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="border-b border-gray-200">
        <div class="flex overflow-x-auto">
            <button class="tab-btn active px-6 py-4 font-medium text-gray-900 border-b-2 border-gray-900 whitespace-nowrap" data-tab="basic">
                📋 Grundinformationen
            </button>
            <button class="tab-btn px-6 py-4 font-medium text-gray-600 border-b-2 border-transparent whitespace-nowrap hover:text-gray-800" data-tab="keywords">
                🔑 Keywords
            </button>
            <button class="tab-btn px-6 py-4 font-medium text-gray-600 border-b-2 border-transparent whitespace-nowrap hover:text-gray-800" data-tab="images">
                🖼️ Bilder
            </button>
            <button class="tab-btn px-6 py-4 font-medium text-gray-600 border-b-2 border-transparent whitespace-nowrap hover:text-gray-800" data-tab="amazon">
                🛒 Amazon Listing
            </button>
            <button class="tab-btn px-6 py-4 font-medium text-gray-600 border-b-2 border-transparent whitespace-nowrap hover:text-gray-800" data-tab="shopify">
                🌿 Shopify Listing
            </button>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="save-toast" class="hidden fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-2 z-50 animate-fade-in">
        <span>✓</span>
        <span>Änderungen erfolgreich gespeichert</span>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full mx-4 animate-scale-in">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                <span class="text-2xl">⚠️</span>
            </div>
            <h2 class="text-xl font-bold text-gray-900 text-center mb-2">Produkt wirklich löschen?</h2>
            <p class="text-gray-600 text-center mb-6">Diese Aktion kann nicht rückgängig gemacht werden. Das Produkt und alle zugehörigen Daten werden permanent gelöscht.</p>
            <div class="flex gap-3">
                <button id="delete-cancel-btn" class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 font-medium transition">
                    Abbrechen
                </button>
                <button id="delete-confirm-btn" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition">
                    Löschen
                </button>
            </div>
        </div>
    </div>

    <!-- Transfer Keywords Modal -->
    <div id="transfer-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full mx-4">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-gray-100 rounded-full mb-4">
                <span class="text-2xl">🔑</span>
            </div>
            <h2 class="text-xl font-bold text-gray-900 text-center mb-2">Keywords übertragen?</h2>
            <p class="text-gray-600 text-center mb-3">Die folgenden Keywords werden in <strong>Amazon Listing</strong> und <strong>Shopify Tags</strong> übertragen (bestehende Werte werden überschrieben):</p>
            <p id="transfer-preview" class="text-sm text-gray-700 bg-gray-50 rounded-lg px-4 py-3 text-center mb-6 break-words"></p>
            <div class="flex gap-3">
                <button id="transfer-cancel-btn" class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 font-medium transition">
                    Abbrechen
                </button>
                <button id="transfer-confirm-btn" class="flex-1 px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 font-medium transition">
                    Übertragen & speichern
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="p-6">
        @if (auth()->user()->can('update', $product))
        <form id="product-form" action="{{ route('products.update', $product) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Tab: Grundinformationen -->
            <div id="basic" class="tab-content active space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Produktname *</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700 focus:border-transparent"
                               placeholder="z.B. Wireless Kopfhörer">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preis</label>
                        <div class="relative">
                            <span class="absolute left-4 top-2 text-gray-500">€</span>
                            <input type="number" name="price" value="{{ old('price', $product->price) }}" step="0.01" min="0"
                                   class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700 focus:border-transparent"
                                   placeholder="0.00">
                        </div>
                        @error('price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Beschreibung</label>
                    <textarea name="description" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700 focus:border-transparent"
                              placeholder="Produktbeschreibung...">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Interne Notizen</label>
                    <textarea name="notes" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700 focus:border-transparent"
                              placeholder="Interne Notizen...">{{ old('notes', $product->notes) }}</textarea>
                </div>

            </div>

            <!-- Tab: Keywords -->
            <div id="keywords" class="tab-content hidden space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">🔑 Keywords & SEO</h3>
                    <p class="text-sm text-gray-500 mb-4">Diese Keywords werden automatisch per n8n &amp; SE Ranking generiert und können manuell ergänzt werden.</p>
                    <div id="keywords-container" class="space-y-2 mb-4">
                        @if ($product->keywords)
                            @foreach ($product->keywords as $keyword)
                                <div class="flex items-center space-x-2">
                                    <input type="text" name="keywords[]" value="{{ $keyword }}"
                                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700"
                                           placeholder="Keyword...">
                                    <button type="button" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 remove-keyword">
                                        ✕
                                    </button>
                                </div>
                            @endforeach
                        @else
                            <p class="text-sm text-gray-400 italic">Noch keine Keywords vorhanden. Erstelle das Produkt erneut oder füge manuell Keywords hinzu.</p>
                        @endif
                    </div>
                    <button type="button" id="add-keyword" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        + Keyword hinzufügen
                    </button>
                </div>

                <div class="pt-6 border-t">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Keywords übertragen</h4>
                    <p class="text-sm text-gray-500 mb-4">Überträgt die aktuellen Keywords komma-getrennt in das Amazon-Keywords-Feld und das Shopify-Tags-Feld.</p>
                    <button type="button" id="transfer-keywords-btn"
                            class="px-5 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 font-medium">
                        → In Amazon &amp; Shopify übertragen
                    </button>
                </div>
            </div>

            <!-- Tab: Bilder -->
            <div id="images" class="tab-content hidden space-y-8">
                <!-- Raw Images -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900">📸 Rohbilder (Original)</h3>
                    <p class="text-sm text-gray-600">Referenzmaterial und Originalfotos</p>
                    
                    <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer transition hover:bg-gray-100 hover:border-gray-400"
                         data-dropzone data-product-id="{{ $product->id }}" data-image-type="raw">
                        <input type="file" multiple accept="image/*" style="display: none;">
                        <div class="space-y-2">
                            <svg class="mx-auto h-12 w-12 text-blue-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-12l-3.172-3.172a4 4 0 00-5.656 0L28 12M12 24h8m-8-8h4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <p class="text-lg font-semibold text-gray-900">Originalbilder hier ziehen oder klicken</p>
                            <p class="text-sm text-gray-700">PNG, JPG, GIF bis 10MB</p>
                        </div>
                        <div class="upload-progress" style="display: none; margin-top: 1rem;">
                            <div class="w-full bg-gray-300 rounded-full h-2">
                                <div class="bg-gray-900 h-2 rounded-full" style="width: 0%;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="image-gallery grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @forelse ($product->rawImages as $image)
                            <div class="relative group" data-image-id="{{ $image->id }}">
                                <img src="{{ $image->url }}" alt="{{ $image->file_name }}" class="w-full h-32 object-cover rounded-lg shadow-md">
                                <button type="button" class="absolute top-1 right-1 bg-red-600 text-white p-1 rounded opacity-0 group-hover:opacity-100 transition delete-image" 
                                        data-product-id="{{ $product->id }}" data-image-id="{{ $image->id }}">
                                    ✕
                                </button>
                                <p class="text-xs text-gray-600 mt-1 truncate text-center">{{ $image->file_name }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500 col-span-full text-center py-4">Noch keine Rohbilder hochgeladen</p>
                        @endforelse
                    </div>
                </div>

                <!-- Product Images -->
                <div class="pt-8 border-t space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900">🖼️ Produktbilder (Verkauf)</h3>
                    <p class="text-sm text-gray-600">Bilder für Amazon und Shopify Listings</p>
                    
                    <div class="bg-green-50 border-2 border-dashed border-green-300 rounded-lg p-8 text-center cursor-pointer transition hover:bg-green-100 hover:border-green-400"
                         data-dropzone data-product-id="{{ $product->id }}" data-image-type="product">
                        <input type="file" multiple accept="image/*" style="display: none;">
                        <div class="space-y-2">
                            <svg class="mx-auto h-12 w-12 text-green-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-12l-3.172-3.172a4 4 0 00-5.656 0L28 12M12 24h8m-8-8h4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <p class="text-lg font-semibold text-green-900">Produktbilder hier ziehen oder klicken</p>
                            <p class="text-sm text-green-700">PNG, JPG, GIF bis 10MB</p>
                        </div>
                        <div class="upload-progress" style="display: none; margin-top: 1rem;">
                            <div class="w-full bg-gray-300 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: 0%;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="image-gallery grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @forelse ($product->productImages as $image)
                            <div class="relative group" data-image-id="{{ $image->id }}">
                                <img src="{{ $image->url }}" alt="{{ $image->file_name }}" class="w-full h-32 object-cover rounded-lg shadow-md">
                                <button type="button" class="absolute top-1 right-1 bg-red-600 text-white p-1 rounded opacity-0 group-hover:opacity-100 transition delete-image" 
                                        data-product-id="{{ $product->id }}" data-image-id="{{ $image->id }}">
                                    ✕
                                </button>
                                <p class="text-xs text-gray-600 mt-1 truncate text-center">{{ $image->file_name }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500 col-span-full text-center py-4">Noch keine Produktbilder hochgeladen</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Tab: Amazon Listing -->
            <div id="amazon" class="tab-content hidden">
                @include('products.sections.amazon-listing')
            </div>

            <!-- Tab: Shopify Listing -->
            <div id="shopify" class="tab-content hidden">
                @include('products.sections.shopify-listing')
            </div>

            <!-- Submit Button (Fixed at Bottom) -->
            <div class="flex gap-4 pt-6 border-t sticky bottom-0 bg-white p-6 -m-6 mt-6 flex-wrap">
                <button type="submit" class="px-6 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 font-medium transition">
                    💾 Speichern
                </button>
                
                <!-- Export Buttons -->
                <div class="flex gap-2">
                    <a href="{{ route('products.export', [$product, 'csv']) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition inline-block" target="_blank" title="Als CSV exportieren">
                        📊 CSV
                    </a>
                    <a href="{{ route('products.export', [$product, 'json']) }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium transition inline-block" target="_blank" title="Als JSON exportieren">
                        📋 JSON
                    </a>
                </div>
                
                <a href="{{ route('products.index') }}" class="px-6 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 font-medium transition">
                    ← Zurück
                </a>
            </div>
        </form>

        @can('delete', $product)
            <div class="flex gap-4 pt-6 border-t sticky bottom-0 bg-white p-6 -m-6 mt-6">
                <form id="delete-form" action="{{ route('products.destroy', $product) }}" method="POST" style="display: inline; margin-left: auto;">
                    @csrf
                    @method('DELETE')
                    <button type="button" id="delete-btn" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition">
                        🗑️ Löschen
                    </button>
                </form>
            </div>
        @endcan
        @else
            <div class="p-8 text-center">
                <p class="text-gray-600 text-lg">❌ Keine Berechtigung zum Bearbeiten dieses Produkts.</p>
            </div>
        @endif
    </div>
</div>

@include('layouts.partials.keyword-js')

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab Navigation
    function switchTab(tabName) {
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('active', 'text-gray-900', 'border-gray-900');
            b.classList.add('text-gray-600', 'border-transparent');
        });
        document.querySelectorAll('.tab-content').forEach(t => {
            t.classList.add('hidden');
            t.classList.remove('active');
        });

        const btn = document.querySelector(`[data-tab="${tabName}"]`);
        if (btn) {
            btn.classList.add('active', 'text-gray-900', 'border-gray-900');
            btn.classList.remove('text-gray-600', 'border-transparent');
        }
        const panel = document.getElementById(tabName);
        if (panel) {
            panel.classList.remove('hidden');
            panel.classList.add('active');
        }
    }

    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            switchTab(this.dataset.tab);
        });
    });

    // Keyword Transfer
    document.getElementById('transfer-keywords-btn')?.addEventListener('click', function () {
        const inputs = document.querySelectorAll('#keywords-container input[name="keywords[]"]');
        const keywords = Array.from(inputs).map(i => i.value.trim()).filter(v => v.length > 0);
        if (keywords.length === 0) {
            alert('Keine Keywords vorhanden zum Übertragen.');
            return;
        }
        const preview = keywords.slice(0, 5).join(', ') + (keywords.length > 5 ? ` ... (+${keywords.length - 5} weitere)` : '');
        document.getElementById('transfer-preview').textContent = preview;
        document.getElementById('transfer-modal').classList.remove('hidden');
    });

    document.getElementById('transfer-cancel-btn')?.addEventListener('click', function () {
        document.getElementById('transfer-modal').classList.add('hidden');
    });

    document.getElementById('transfer-confirm-btn')?.addEventListener('click', function () {
        const inputs = document.querySelectorAll('#keywords-container input[name="keywords[]"]');
        const keywords = Array.from(inputs).map(i => i.value.trim()).filter(v => v.length > 0);
        const joined = keywords.join(', ');

        const amazonField = document.querySelector('textarea[name="amazon_listing[keywords]"]');
        if (amazonField) amazonField.value = joined;

        const shopifyField = document.querySelector('input[name="shopify_listing[tags]"]');
        if (shopifyField) shopifyField.value = joined;

        document.getElementById('transfer-modal').classList.add('hidden');

        // Zu Amazon-Tab wechseln um Ergebnis zu sehen
        switchTab('amazon');
    });

    // Delete Modal
    const deleteBtn = document.getElementById('delete-btn');
    const deleteModal = document.getElementById('delete-modal');
    const deleteCancelBtn = document.getElementById('delete-cancel-btn');
    const deleteConfirmBtn = document.getElementById('delete-confirm-btn');
    const deleteForm = document.getElementById('delete-form');

    if (deleteBtn) {
        deleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            deleteModal.classList.remove('hidden');
            deleteModal.classList.add('flex');
        });
    }

    if (deleteCancelBtn) {
        deleteCancelBtn.addEventListener('click', function(e) {
            e.preventDefault();
            deleteModal.classList.add('hidden');
            deleteModal.classList.remove('flex');
        });
    }

    if (deleteConfirmBtn) {
        deleteConfirmBtn.addEventListener('click', function(e) {
            e.preventDefault();
            deleteForm.submit();
        });
    }

    // Close modal when clicking outside
    deleteModal?.addEventListener('click', function(e) {
        if (e.target === this) {
            deleteModal.classList.add('hidden');
            deleteModal.classList.remove('flex');
        }
    });

    // Save Toast Notification
    const form = document.getElementById('product-form');
    const toast = document.getElementById('save-toast');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            // Wait a bit for the form to process, then show toast
            setTimeout(() => {
                toast.classList.remove('hidden');
                toast.classList.add('animate-fade-in');
                
                // Hide after 3 seconds
                setTimeout(() => {
                    toast.classList.add('hidden');
                }, 3000);
            }, 200);
        });
    }
});
</script>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }

    .animate-scale-in {
        animation: scaleIn 0.3s ease-out;
    }
</style>
@endsection
