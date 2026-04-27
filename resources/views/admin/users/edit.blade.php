@extends('layouts.base')

@section('title', 'Benutzer bearbeiten - Product Planner')

@section('content')
<div class="bg-white rounded-lg shadow-lg overflow-hidden max-w-2xl">
    <!-- Header -->
    <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-8 text-white">
        <h1 class="text-3xl font-bold flex items-center gap-2">
            ✏️ {{ $user->name }} bearbeiten
        </h1>
        <p class="text-gray-300 mt-2">{{ $user->email }}</p>
    </div>

    <!-- Form -->
    <div class="p-6">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
            @csrf
            @method('PATCH')

            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent"
                       placeholder="z.B. Max Mustermann">
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">E-Mail *</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent"
                       placeholder="z.B. max@example.com">
                @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password (Optional for Edit) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Passwort (Optional)</label>
                <input type="password" name="password"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent"
                       placeholder="Leer lassen um unverändert zu behalten">
                <p class="text-xs text-gray-500 mt-1">Geben Sie ein neues Passwort ein, um das alte zu ersetzen</p>
                @error('password')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Passwort Bestätigung</label>
                <input type="password" name="password_confirmation"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent"
                       placeholder="Passwort wiederholen">
            </div>

            <!-- Admin Checkbox -->
            <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <input type="checkbox" id="is_admin" name="is_admin" value="1"
                       class="w-4 h-4 text-gray-900 rounded focus:ring-2 focus:ring-gray-500"
                       {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}>
                <label for="is_admin" class="text-sm font-medium text-gray-700">
                    👑 Admin-Rechte
                </label>
            </div>

            <!-- Konto-Status (nicht eigenes Konto) -->
            @if ($user->id !== auth()->id())
                <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <input type="checkbox" id="is_active" name="is_active" value="1"
                           class="w-4 h-4 text-green-600 rounded focus:ring-2 focus:ring-green-500"
                           {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                    <label for="is_active" class="text-sm font-medium text-gray-700">
                        ✅ Konto aktiv (Deaktivieren sperrt Zugang sofort)
                    </label>
                </div>
            @endif

            <!-- Buttons -->
            <div class="flex gap-4 pt-6 border-t">
                <button type="submit" class="flex-1 px-6 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 font-medium transition">
                    Änderungen speichern
                </button>
                <a href="{{ route('admin.users.show', $user) }}" class="flex-1 px-6 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 font-medium transition text-center">
                    Abbrechen
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
