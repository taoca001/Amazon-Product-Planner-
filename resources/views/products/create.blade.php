@extends('layouts.base')

@section('title', 'Neues Produkt - Product Planner')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-8 text-white">
            <h1 class="text-3xl font-bold">📦 Neues Produkt erstellen</h1>
            <p class="text-blue-100 mt-2">Grundinformationen eingeben und los geht's!</p>
        </div>

        <!-- Form -->
        <form action="{{ route('products.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Produktname *</label>
                <input 
                    type="text" 
                    id="name"
                    name="name" 
                    value="{{ old('name') }}" 
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                    placeholder="z.B. Wireless Kopfhörer Pro">
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Beschreibung</label>
                <textarea 
                    id="description"
                    name="description" 
                    rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Produktbeschreibung...">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Preis (€)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-2 text-gray-500">€</span>
                        <input 
                            type="number" 
                            id="price"
                            name="price" 
                            value="{{ old('price') }}" 
                            step="0.01" 
                            min="0"
                            class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="0.00">
                    </div>
                </div>
            </div>

            <!-- Keywords -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Keywords (SEO)</label>
                <div id="keywords-container" class="space-y-2 mb-4">
                    <!-- Keywords werden hier hinzugefügt -->
                </div>
                <button 
                    type="button" 
                    id="add-keyword" 
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">
                    + Keyword hinzufügen
                </button>
            </div>

            <!-- Notizen -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Interne Notizen</label>
                <textarea 
                    id="notes"
                    name="notes" 
                    rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="z.B. Supplier-Info, Verfügbarkeit, etc.">{{ old('notes') }}</textarea>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4 pt-6 border-t">
                <button 
                    type="submit" 
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold shadow-md">
                    Produkt erstellen
                </button>
                <a 
                    href="{{ route('products.index') }}" 
                    class="px-6 py-3 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 font-medium">
                    Abbrechen
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addKeywordBtn = document.getElementById('add-keyword');
    const keywordsContainer = document.getElementById('keywords-container');

    addKeywordBtn.addEventListener('click', function() {
        const html = `
            <div class="flex items-center space-x-2">
                <input 
                    type="text" 
                    name="keywords[]"
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    placeholder="Keyword (z.B. 'wireless', 'bluetooth')">
                <button 
                    type="button" 
                    class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 remove-keyword">
                    Entfernen
                </button>
            </div>
        `;
        keywordsContainer.insertAdjacentHTML('beforeend', html);
        attachRemoveListeners();
    });

    function attachRemoveListeners() {
        document.querySelectorAll('.remove-keyword').forEach(btn => {
            btn.removeEventListener('click', removeKeyword);
            btn.addEventListener('click', removeKeyword);
        });
    }

    function removeKeyword(e) {
        e.preventDefault();
        this.parentElement.remove();
    }
});
</script>
@endsection
