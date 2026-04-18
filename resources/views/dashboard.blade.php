<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Banner -->
            <div class="mb-8 bg-gradient-to-r from-gray-900 to-gray-700 rounded-lg shadow-lg p-8 text-white">
                <h1 class="text-3xl font-bold mb-2">Willkommen, {{ Auth::user()->name }}! 👋</h1>
                <p class="text-gray-300">Verwalte deine Amazon und Shopify Produkte an einem Ort</p>
            </div>

            <!-- Stats Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                @php
                    $totalProducts = auth()->user()->products()->count();
                    $productsWithAmazon = auth()->user()->products()->whereNotNull('amazon_listing')->count();
                    $productsWithShopify = auth()->user()->products()->whereNotNull('shopify_listing')->count();
                @endphp

                <!-- Total Products Card -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-gray-900">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Gesamt Produkte</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $totalProducts }}</p>
                        </div>
                        <div class="text-4xl text-gray-700">📦</div>
                    </div>
                </div>

                <!-- Amazon Listings Card -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Amazon Listings</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $productsWithAmazon }}</p>
                        </div>
                        <div class="text-4xl text-orange-500">🛒</div>
                    </div>
                </div>

                <!-- Shopify Listings Card -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Shopify Listings</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $productsWithShopify }}</p>
                        </div>
                        <div class="text-4xl text-green-500">🌿</div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mb-8 bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">🚀 Quick Actions</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('products.create') }}" class="bg-gray-900 hover:bg-gray-800 text-white font-semibold py-3 px-6 rounded-lg text-center transition">
                        + Neues Produkt erstellen
                    </a>
                    <a href="{{ route('products.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 px-6 rounded-lg text-center transition">
                        📋 Alle Produkte anzeigen
                    </a>
                    <a href="#" class="bg-gray-400 text-gray-200 font-semibold py-3 px-6 rounded-lg text-center cursor-not-allowed">
                        📊 Reports (Bald verfügbar)
                    </a>
                </div>
            </div>

            <!-- Recent Products -->
            @if($totalProducts > 0)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">📝 Zuletzt bearbeitete Produkte</h2>
                </div>
                <div class="divide-y">
                    @foreach(auth()->user()->products()->latest('updated_at')->take(5)->get() as $product)
                    <div class="px-6 py-4 hover:bg-gray-50 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900">{{ $product->name }}</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    Zuletzt aktualisiert: {{ $product->updated_at->format('d.m.Y H:i') }}
                                </p>
                            </div>
                            <div class="flex items-center space-x-3">
                                @if($product->amazon_listing)
                                    <span class="text-orange-500 text-lg">🛒</span>
                                @endif
                                @if($product->shopify_listing)
                                    <span class="text-green-500 text-lg">🌿</span>
                                @endif
                                <a href="{{ route('products.show', $product) }}" class="text-gray-900 hover:text-gray-900 font-medium">
                                    Bearbeiten →
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <p class="text-2xl text-gray-400 mb-4">📦</p>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Keine Produkte vorhanden</h3>
                <p class="text-gray-600 mb-6">Erstelle dein erstes Produkt um zu beginnen</p>
                <a href="{{ route('products.create') }}" class="inline-block bg-gray-900 hover:bg-gray-800 text-white font-semibold py-3 px-8 rounded-lg transition">
                    + Produkt erstellen
                </a>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
