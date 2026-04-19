@extends('layouts.base')

@section('title', $user->name . ' - Product Planner')

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
