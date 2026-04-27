@extends('layouts.base')

@section('title', '404 - Seite nicht gefunden')

@section('content')
<div class="flex items-center justify-center min-h-[60vh]">
    <div class="text-center">
        <div class="text-8xl font-bold text-gray-200 mb-4">404</div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Seite nicht gefunden</h1>
        <p class="text-gray-600 mb-6">Die angeforderte Seite existiert nicht oder wurde verschoben.</p>
        <a href="{{ route('products.index') }}" class="inline-block px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 font-medium">
            Zurück zur Übersicht
        </a>
    </div>
</div>
@endsection
