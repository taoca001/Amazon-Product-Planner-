@extends('layouts.base')

@section('title', '500 - Serverfehler')

@section('content')
<div class="flex items-center justify-center min-h-[60vh]">
    <div class="text-center">
        <div class="text-8xl font-bold text-gray-200 mb-4">500</div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Interner Serverfehler</h1>
        <p class="text-gray-600 mb-6">Es ist ein unerwarteter Fehler aufgetreten. Bitte versuche es später erneut.</p>
        <a href="{{ route('products.index') }}" class="inline-block px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 font-medium">
            Zurück zur Übersicht
        </a>
    </div>
</div>
@endsection
