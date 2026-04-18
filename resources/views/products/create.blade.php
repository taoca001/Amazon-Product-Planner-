@extends('layouts.base')

@section('title', 'Neues Produkt - Product Planner')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-3 border-b border-gray-200">
            <h1 class="text-xl font-bold text-gray-900">📦 Neues Produkt erstellen</h1>
            <p class="text-gray-400 text-xs mt-0.5">Grundinformationen eingeben und los geht's!</p>
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
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700 focus:border-transparent @error('name') border-red-500 @enderror"
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
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700 focus:border-transparent"
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
                            class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700 focus:border-transparent"
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
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700 focus:border-transparent"
                    placeholder="z.B. Supplier-Info, Verfügbarkeit, etc.">{{ old('notes') }}</textarea>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4 pt-6 border-t">
                <button 
                    type="submit" 
                    class="px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 font-bold shadow-md">
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

@include('layouts.partials.keyword-js')
@endsection
