@extends('layouts.base')

@section('title', 'User Management - Product Planner')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900">👥 User Management</h1>
            <p class="text-xs text-gray-400 mt-0.5">{{ $users->total() }} Benutzer gesamt</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 font-medium text-sm transition">
            + Neuer Benutzer
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 px-6 py-3 text-green-700 text-sm font-medium">✓ {{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 px-6 py-3 text-red-700 text-sm font-medium">✗ {{ session('error') }}</div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-xs text-gray-500 uppercase tracking-wider">
                    <th class="px-5 py-3 text-left font-medium">Benutzer</th>
                    <th class="px-5 py-3 text-left font-medium">Rolle</th>
                    <th class="px-5 py-3 text-left font-medium">Status</th>
                    <th class="px-5 py-3 text-right font-medium">Produkte</th>
                    <th class="px-5 py-3 text-right font-medium">API-Tokens</th>
                    <th class="px-5 py-3 text-left font-medium">Letzter Login</th>
                    <th class="px-5 py-3 text-left font-medium">Erstellt</th>
                    <th class="px-5 py-3 text-right font-medium">Aktionen</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-50 transition {{ !$user->is_active ? 'opacity-60' : '' }}">
                        <td class="px-5 py-3">
                            <div class="font-medium text-gray-900 flex items-center gap-2">
                                @if ($user->is_admin) <span>👑</span> @else <span class="text-gray-400">👤</span> @endif
                                <a href="{{ route('admin.users.show', $user) }}" class="hover:underline">{{ $user->name }}</a>
                            </div>
                            <div class="text-xs text-gray-400 mt-0.5">{{ $user->email }}</div>
                        </td>
                        <td class="px-5 py-3">
                            @if ($user->is_admin)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-200 text-gray-800">Admin</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">User</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            @if ($user->is_active)
                                <span class="inline-flex items-center gap-1 text-xs font-medium text-green-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span> Aktiv
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-medium text-red-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span> Gesperrt
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right text-gray-700 font-medium">{{ $user->products_count }}</td>
                        <td class="px-5 py-3 text-right text-gray-700 font-medium">{{ $user->api_tokens_count }}</td>
                        <td class="px-5 py-3 text-gray-500 text-xs">
                            {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : '–' }}
                        </td>
                        <td class="px-5 py-3 text-gray-500 text-xs">{{ $user->created_at->format('d.m.Y') }}</td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="px-2.5 py-1 bg-gray-100 text-gray-700 rounded text-xs hover:bg-gray-200 transition">
                                    Details
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="px-2.5 py-1 bg-gray-100 text-gray-700 rounded text-xs hover:bg-gray-200 transition">
                                    Bearbeiten
                                </a>
                                @if ($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.toggle-active', $user) }}" method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="px-2.5 py-1 rounded text-xs transition {{ $user->is_active ? 'bg-amber-100 text-amber-700 hover:bg-amber-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                                            {{ $user->is_active ? 'Sperren' : 'Aktivieren' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline"
                                          onsubmit="return confirm('{{ addslashes($user->name) }} wirklich löschen? Alle Produkte und Tokens werden entfernt.')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="px-2.5 py-1 bg-red-100 text-red-700 rounded text-xs hover:bg-red-200 transition">
                                            Löschen
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-5 py-12 text-center text-gray-400">
                            Keine Benutzer vorhanden.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($users->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
