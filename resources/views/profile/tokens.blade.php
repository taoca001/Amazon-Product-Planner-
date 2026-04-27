@extends('layouts.base')

@section('title', 'API-Tokens – Product Planner')

@section('content')
<div class="max-w-3xl space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900">🔑 API-Tokens</h1>
            <p class="text-xs text-gray-400 mt-0.5">Tokens für n8n-Automatisierungen und externe Integrationen</p>
        </div>
        <a href="{{ route('profile.edit') }}" class="text-sm text-gray-500 hover:text-gray-700">← Zurück zum Profil</a>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 px-5 py-3 rounded text-green-700 text-sm font-medium">✓ {{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 px-5 py-3 rounded text-red-700 text-sm font-medium">✗ {{ session('error') }}</div>
    @endif

    @if (session('new_token'))
        <div class="bg-amber-50 border border-amber-300 rounded-lg p-5">
            <p class="text-sm font-semibold text-amber-800 mb-2">⚠️ Dein neuer API-Token — nur jetzt sichtbar!</p>
            <code class="block bg-white border border-amber-200 rounded px-4 py-3 text-sm font-mono text-gray-900 break-all select-all">{{ session('new_token') }}</code>
            <p class="text-xs text-amber-600 mt-2">Kopiere ihn jetzt. Er wird nicht erneut angezeigt.</p>
        </div>
    @endif

    <!-- Neuen Token erstellen -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-900 text-sm">Neuen Token erstellen</h2>
        </div>
        <form action="{{ route('profile.tokens.store') }}" method="POST" class="px-6 py-5">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Name / Verwendungszweck</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           placeholder="z.B. n8n Keyword Automation"
                           class="w-full rounded-lg border-gray-300 text-sm focus:ring-gray-500 focus:border-gray-500">
                    @error('name')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Läuft ab am (optional)</label>
                    <input type="date" name="expires_at" value="{{ old('expires_at') }}"
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           class="w-full rounded-lg border-gray-300 text-sm focus:ring-gray-500 focus:border-gray-500">
                    @error('expires_at')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-lg text-sm font-medium hover:bg-gray-800 transition">
                    Token erstellen
                </button>
            </div>
        </form>
    </div>

    <!-- Bestehende Tokens -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-900 text-sm">Deine Tokens</h2>
            <span class="text-xs text-gray-400">{{ $tokens->count() }} / 10</span>
        </div>
        @if ($tokens->isEmpty())
            <p class="px-6 py-10 text-sm text-center text-gray-400">Noch keine API-Tokens erstellt.</p>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-5 py-2.5 text-left font-medium">Name</th>
                        <th class="px-5 py-2.5 text-left font-medium">Erstellt</th>
                        <th class="px-5 py-2.5 text-left font-medium">Zuletzt genutzt</th>
                        <th class="px-5 py-2.5 text-left font-medium">Läuft ab</th>
                        <th class="px-5 py-2.5 text-left font-medium">Status</th>
                        <th class="px-5 py-2.5 text-right font-medium">Aktion</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($tokens as $token)
                        <tr class="hover:bg-gray-50 {{ $token->isExpired() ? 'opacity-50' : '' }}">
                            <td class="px-5 py-3 font-medium text-gray-800">{{ $token->name }}</td>
                            <td class="px-5 py-3 text-gray-500 text-xs">{{ $token->created_at->format('d.m.Y') }}</td>
                            <td class="px-5 py-3 text-gray-500 text-xs">
                                {{ $token->last_used_at ? $token->last_used_at->diffForHumans() : '–' }}
                            </td>
                            <td class="px-5 py-3 text-gray-500 text-xs">
                                {{ $token->expires_at ? $token->expires_at->format('d.m.Y') : 'Nie' }}
                            </td>
                            <td class="px-5 py-3">
                                @if ($token->isExpired())
                                    <span class="text-xs text-red-600 font-medium">Abgelaufen</span>
                                @else
                                    <span class="text-xs text-green-700 font-medium">Aktiv</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-right">
                                <form action="{{ route('profile.tokens.destroy', $token) }}" method="POST"
                                      onsubmit="return confirm('Token &quot;{{ addslashes($token->name) }}&quot; widerrufen?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-medium">Widerrufen</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</div>
@endsection
