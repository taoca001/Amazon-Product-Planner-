@extends('layouts.base')

@section('title', 'Neuer Benutzer - Product Planner')

@section('content')
<div class="bg-white rounded-lg shadow-lg overflow-hidden max-w-2xl">
    <!-- Header -->
    <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-8 text-white">
        <h1 class="text-3xl font-bold">➕ Neuer Benutzer</h1>
        <p class="text-gray-300 mt-2">Erstelle einen neuen Benutzer im System</p>
    </div>

    <!-- Form -->
    <div class="p-6">
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent"
                       placeholder="z.B. Max Mustermann">
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">E-Mail *</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent"
                       placeholder="z.B. max@example.com">
                @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Passwort *</label>
                <input type="password" name="password" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent"
                       placeholder="Mindestens 8 Zeichen">
                @error('password')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Passwort Bestätigung *</label>
                <input type="password" name="password_confirmation" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent"
                       placeholder="Passwort wiederholen">
                @error('password_confirmation')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Admin Checkbox -->
            <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg">
                <input type="checkbox" id="is_admin" name="is_admin" value="1"
                       class="w-5 h-5 text-gray-900 rounded focus:ring-2 focus:ring-gray-500"
                       {{ old('is_admin') ? 'checked' : '' }}>
                <label for="is_admin" class="text-sm font-medium text-gray-700">
                    👑 Admin-Rechte vergeben
                </label>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4 pt-6 border-t">
                <button type="submit" class="flex-1 px-6 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 font-medium transition">
                    Benutzer erstellen
                </button>
                <a href="{{ route('admin.users.index') }}" class="flex-1 px-6 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 font-medium transition text-center">
                    Abbrechen
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
