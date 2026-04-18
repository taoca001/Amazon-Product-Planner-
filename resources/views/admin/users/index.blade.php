@extends('layouts.base')

@section('title', 'User Management - Product Planner')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <!-- Header -->
    <div class="px-6 py-3 border-b border-gray-200 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900">👥 User Management</h1>
            <p class="text-gray-400 text-xs mt-0.5">Verwalte alle Benutzer des Systems</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 font-medium text-sm transition">
            + Neuer Benutzer
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 m-6">
            <p class="text-green-700 font-medium">✓ {{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 m-6">
            <p class="text-red-700 font-medium">✗ {{ session('error') }}</p>
        </div>
    @endif

    <!-- Users Table -->
    <div class="p-6">
        @if ($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-200">
                            <th class="text-left px-4 py-3 font-semibold text-gray-700">Name</th>
                            <th class="text-left px-4 py-3 font-semibold text-gray-700">E-Mail</th>
                            <th class="text-left px-4 py-3 font-semibold text-gray-700">Admin</th>
                            <th class="text-left px-4 py-3 font-semibold text-gray-700">Erstellt</th>
                            <th class="text-left px-4 py-3 font-semibold text-gray-700">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="px-4 py-3 font-medium text-gray-900">
                                    <span class="flex items-center gap-2">
                                        @if ($user->is_admin)
                                            <span class="text-lg">👑</span>
                                        @else
                                            <span class="text-lg">👤</span>
                                        @endif
                                        {{ $user->name }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>
                                <td class="px-4 py-3">
                                    @if ($user->is_admin)
                                        <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-medium">Admin</span>
                                    @else
                                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm font-medium">User</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-600 text-sm">
                                    {{ $user->created_at->format('d.m.Y H:i') }}
                                </td>
                                <td class="px-4 py-3 flex gap-2">
                                    <a href="{{ route('admin.users.show', $user) }}" class="px-3 py-1 bg-gray-500 text-white rounded text-sm hover:bg-gray-900">
                                        Ansehen
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="px-3 py-1 bg-gray-500 text-white rounded text-sm hover:bg-gray-600">
                                        Bearbeiten
                                    </a>
                                    @if ($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded text-sm hover:bg-red-600"
                                                    onclick="return confirm('Benutzer wirklich löschen?')">
                                                Löschen
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $users->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg mb-4">Keine Benutzer vorhanden</p>
                <a href="{{ route('admin.users.create') }}" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium">
                    + Ersten Benutzer erstellen
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Back Button -->
<div class="mt-6">
    <a href="{{ route('dashboard') }}" class="text-gray-900 hover:text-gray-900 font-medium">
        ← Zurück zum Dashboard
    </a>
</div>
@endsection
