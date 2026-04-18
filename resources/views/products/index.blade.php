@extends('layouts.base')

@section('title', 'Meine Produkte - Product Planner')

@section('content')
<div class="space-y-3">
    <!-- Header with Create Button & Exports -->
    <div class="flex justify-between items-center flex-wrap gap-2">
        <div>
            <h1 class="text-xl font-bold text-gray-900">📦 Meine Produkte</h1>
            <p class="text-gray-600 text-sm">Verwalte deine Amazon-Produkte und Listings</p>
        </div>
        <div class="flex gap-3">
            @if ($products->count() > 0)
            <div class="flex gap-2 bg-white rounded-lg shadow-md p-1">
                <a href="{{ route('products.export-all', 'csv') }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 font-medium text-sm" title="Alle Produkte als CSV exportieren">
                    📥 CSV
                </a>
                <a href="{{ route('products.export-all', 'json') }}" class="px-4 py-2 bg-gray-900 text-white rounded hover:bg-gray-800 font-medium text-sm" title="Alle Produkte als JSON exportieren">
                    📥 JSON
                </a>
            </div>
            @endif
            <a href="{{ route('products.create') }}" class="px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 font-medium shadow-lg">
                + Neues Produkt
            </a>
        </div>
    </div>

    <!-- Products Grid -->
    @if ($products->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($products as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <!-- Product Image -->
                    <div class="relative bg-gray-200 h-48 flex items-center justify-center overflow-hidden">
                        @if ($product->productImages->first())
                            <img src="{{ $product->productImages->first()->url }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="text-gray-400 text-4xl">📦</div>
                        @endif
                    </div>

                    <!-- Product Info -->
                    <div class="p-4 space-y-3">
                        <h3 class="text-lg font-semibold text-gray-900 line-clamp-2">{{ $product->name }}</h3>
                        
                        <p class="text-sm text-gray-600 line-clamp-2">
                            {{ $product->description ?? 'Keine Beschreibung' }}
                        </p>

                        <!-- Price -->
                        @if ($product->price)
                            <div class="flex items-baseline space-x-2">
                                <span class="text-2xl font-bold text-gray-900">€{{ number_format($product->price, 2, ',', '.') }}</span>
                            </div>
                        @endif

                        <!-- Stats -->
                        <div class="flex items-center justify-between text-sm text-gray-500 pt-2 border-t">
                            <span>🖼️ {{ $product->rawImages->count() }} Rohbilder</span>
                            <span>📸 {{ $product->productImages->count() }} Bilder</span>
                        </div>

                        <!-- Keywords -->
                        @if ($product->keywords && count($product->keywords) > 0)
                            <div class="flex flex-wrap gap-1 pt-2">
                                @foreach (array_slice($product->keywords, 0, 3) as $keyword)
                                    <span class="inline-block bg-gray-100 text-gray-900 px-2 py-1 rounded text-xs">
                                        {{ $keyword }}
                                    </span>
                                @endforeach
                                @if (count($product->keywords) > 3)
                                    <span class="inline-block text-gray-500 text-xs px-2 py-1">
                                        +{{ count($product->keywords) - 3 }} mehr
                                    </span>
                                @endif
                            </div>
                        @endif

                        <!-- Listing Status -->
                        <div class="flex gap-2 text-xs pt-2">
                            @if ($product->amazon_listing)
                                <span class="bg-amber-100 text-amber-800 px-2 py-1 rounded">✓ Amazon</span>
                            @endif
                            @if ($product->shopify_listing)
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded">✓ Shopify</span>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2 pt-4 border-t">
                            <a href="{{ route('products.show', $product) }}" class="flex-1 text-center px-3 py-2 bg-gray-500 text-white rounded hover:bg-gray-900 text-sm font-medium">
                                Bearbeiten
                            </a>
                            <a href="{{ route('products.export', [$product, 'csv']) }}" class="px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm font-medium" target="_blank" title="CSV exportieren">
                                📊
                            </a>
                            <a href="{{ route('products.export', [$product, 'json']) }}" class="px-3 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 text-sm font-medium" target="_blank" title="JSON exportieren">
                                📋
                            </a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600 text-sm font-medium"
                                        onclick="return confirm('Wirklich löschen?')">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <div class="text-6xl mb-4">📦</div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Keine Produkte vorhanden</h2>
            <p class="text-gray-600 mb-6">Erstelle dein erstes Produkt, um zu beginnen!</p>
            <a href="{{ route('products.create') }}" class="inline-block px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 font-medium">
                + Neues Produkt erstellen
            </a>
        </div>
    @endif
</div>
@endsection
