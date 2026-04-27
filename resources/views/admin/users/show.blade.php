@extends('layouts.base')

@section('title', $user->name . ' – Product Planner')

@section('content')
<div class="space-y-6">

    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 px-5 py-3 rounded text-green-700 text-sm font-medium">✓ {{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 px-5 py-3 rounded text-red-700 text-sm font-medium">✗ {{ session('error') }}</div>
    @endif

    <!-- User Card -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex items-start justify-between">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center text-2xl">
                    {{ $user->is_admin ? '👑' : '👤' }}
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">{{ $user->name }}</h1>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    <div class="flex items-center gap-2 mt-1.5">
                        @if ($user->is_admin)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-200 text-gray-800">Admin</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">User</span>
                        @endif
                        @if ($user->is_active)
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-green-700"><span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>Aktiv</span>
                        @else
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-red-600"><span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span>Gesperrt</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.users.edit', $user) }}"
                   class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200 font-medium transition">
                    Bearbeiten
                </a>
                @if ($user->id !== auth()->id())
                    <form action="{{ route('admin.users.toggle-active', $user) }}" method="POST" class="inline">
                        @csrf @method('PATCH')
                        <button type="submit"
                            class="px-3 py-1.5 rounded-lg text-sm font-medium transition {{ $user->is_active ? 'bg-amber-100 text-amber-700 hover:bg-amber-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                            {{ $user->is_active ? '🔒 Sperren' : '✅ Aktivieren' }}
                        </button>
                    </form>
                @endif
                <a href="{{ route('admin.users.index') }}"
                   class="px-3 py-1.5 bg-gray-50 text-gray-500 rounded-lg text-sm hover:bg-gray-100 transition">
                    ← Zurück
                </a>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 sm:grid-cols-4 divide-x divide-gray-100">
            <div class="px-6 py-4">
                <p class="text-xs text-gray-400 uppercase tracking-wide">Produkte</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $user->products_count }}</p>
            </div>
            <div class="px-6 py-4">
                <p class="text-xs text-gray-400 uppercase tracking-wide">API-Tokens</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $user->api_tokens_count }}</p>
            </div>
            <div class="px-6 py-4">
                <p class="text-xs text-gray-400 uppercase tracking-wide">Registriert</p>
                <p class="text-sm font-medium text-gray-800 mt-1">{{ $user->created_at->format('d.m.Y') }}</p>
            </div>
            <div class="px-6 py-4">
                <p class="text-xs text-gray-400 uppercase tracking-wide">Letzter Login</p>
                <p class="text-sm font-medium text-gray-800 mt-1">
                    {{ $user->last_login_at ? $user->last_login_at->format('d.m.Y H:i') : '–' }}
                </p>
            </div>
        </div>
    </div>

    <!-- API Token Table -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-900 text-sm">🔑 API-Tokens</h2>
        </div>
        @if ($tokens->isEmpty())
            <p class="px-6 py-8 text-sm text-center text-gray-400">Dieser Benutzer hat keine API-Tokens.</p>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-5 py-2.5 text-left font-medium">Name</th>
                        <th class="px-5 py-2.5 text-left font-medium">Zuletzt genutzt</th>
                        <th class="px-5 py-2.5 text-left font-medium">Läuft ab</th>
                        <th class="px-5 py-2.5 text-left font-medium">Status</th>
                        <th class="px-5 py-2.5 text-right font-medium">Aktion</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($tokens as $token)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3 font-medium text-gray-800">{{ $token->name }}</td>
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
                                <form action="{{ route('admin.users.tokens.revoke', [$user, $token]) }}" method="POST"
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

    @if ($user->id !== auth()->id())
        <!-- Danger Zone -->
        <div class="bg-white rounded-lg border border-red-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-red-100">
                <h2 class="font-semibold text-red-700 text-sm">⚠️ Gefahrenzone</h2>
            </div>
            <div class="px-6 py-4 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-800">Benutzer löschen</p>
                    <p class="text-xs text-gray-500 mt-0.5">Löscht den Account inklusive aller Produkte und API-Tokens. Nicht rückgängig zu machen.</p>
                </div>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                      onsubmit="return confirm('{{ addslashes($user->name) }} wirklich dauerhaft löschen?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition">
                        Löschen
                    </button>
                </form>
            </div>
        </div>
    @endif

</div>
@endsection


@section('content')
<div class="bg-white rounded-lg shadow-lg overflow-hidden max-w-2xl">
    <!-- Header -->
    <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold flex items-center gap-2">
                    @if ($user->is_admin)
                        👑
                    @else
                        👤
                    @endif
                    {{ $user->name }}
                </h1>
                <p class="text-gray-300 mt-2">{{ $user->email }}</p>
            </div>
            @if ($user->id !== auth()->id())
                <a href="{{ route('admin.users.edit', $user) }}" class="px-6 py-2 bg-white text-gray-900 rounded-lg hover:bg-gray-50 font-medium transition">
                    ✏️ Bearbeiten
                </a>
            @endif
        </div>
    </div>

    <!-- Info -->
    <div class="p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">Status</p>
                <p class="text-lg font-semibold text-gray-900">
                    @if ($user->is_admin)
                        <span class="px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-sm">Admin</span>
                    @else
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">User</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Registriert</p>
                <p class="text-lg font-semibold text-gray-900">{{ $user->created_at->format('d.m.Y H:i') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Zuletzt aktualisiert</p>
                <p class="text-lg font-semibold text-gray-900">{{ $user->updated_at->format('d.m.Y H:i') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Produkte</p>
                <p class="text-lg font-semibold text-gray-900">{{ $user->products()->count() }}</p>
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex gap-4 pt-6 border-t">
            <a href="{{ route('admin.users.edit', $user) }}" class="flex-1 px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 font-medium transition text-center">
                Bearbeiten
            </a>
            @if ($user->id !== auth()->id())
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition"
                            onclick="return confirm('Benutzer wirklich löschen?')">
                        Löschen
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.users.index') }}" class="flex-1 px-6 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 font-medium transition text-center">
                Zurück
            </a>
        </div>
    </div>
</div>
@endsection
