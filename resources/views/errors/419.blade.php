@extends('layouts.base')

@section('title', '419 - Sitzung abgelaufen')

@section('content')
<div class="flex items-center justify-center min-h-[60vh]">
    <div class="text-center">
        <div class="text-8xl font-bold text-gray-200 mb-4">419</div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Sitzung abgelaufen</h1>
        <p class="text-gray-600 mb-6">Deine Sitzung ist abgelaufen. Bitte lade die Seite neu und versuche es erneut.</p>
        <a href="{{ url()->previous() }}" class="inline-block px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 font-medium">
            Seite neu laden
        </a>
    </div>
</div>
@endsection
