@extends('layouts.base')

@section('title', 'Operationen - Product Planner')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <!-- Header -->
    <div class="px-6 py-3 border-b border-gray-200">
        <h1 class="text-xl font-bold text-gray-900">🔧 Operationen</h1>
        <p class="text-gray-400 text-xs mt-0.5">Spezielle Funktionen für Ausnahmefälle</p>
    </div>

    <!-- Messages -->
    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 m-6 mb-0">
            <p class="text-green-700 font-medium">✓ {{ session('success') }}</p>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 m-6 mb-0">
            <p class="text-red-700 font-medium">✗ {{ session('error') }}</p>
        </div>
    @endif

    <div class="p-6 space-y-4">

        <!-- Keyword-Analyse (via n8n) -->
        <div x-data="{ open: false }" class="border border-gray-200 rounded-lg overflow-hidden">
            <button @click="open = !open" class="w-full flex items-center justify-between px-5 py-4 bg-gray-50 hover:bg-gray-100 transition text-left">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🔍</span>
                    <div>
                        <h3 class="font-semibold text-gray-900">Keyword-Analyse auslösen</h3>
                        <p class="text-xs text-gray-500 mt-0.5">SE Ranking Keyword-Analyse für bestehende Produkte starten (via n8n)</p>
                    </div>
                </div>
                <svg :class="{ 'rotate-180': open }" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open" x-transition.duration.200ms>
                <form method="POST" action="{{ route('operations.keyword-analysis') }}" class="p-5 border-t border-gray-200">
                    @csrf
                    @if ($products->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Region / Suchmaschine</label>
                                <select name="region" class="w-full rounded-lg border-gray-300 text-sm focus:ring-gray-500 focus:border-gray-500">
                                    <optgroup label="Google">
                                        <option value="google_de" selected>🇩🇪 Google DE</option>
                                        <option value="google_at">🇦🇹 Google AT</option>
                                        <option value="google_ch">🇨🇭 Google CH</option>
                                        <option value="google_us">🇺🇸 Google US</option>
                                        <option value="google_uk">🇬🇧 Google UK</option>
                                    </optgroup>
                                    <optgroup label="Amazon">
                                        <option value="amazon_de">🇩🇪 Amazon DE</option>
                                        <option value="amazon_us">🇺🇸 Amazon US</option>
                                        <option value="amazon_uk">🇬🇧 Amazon UK</option>
                                    </optgroup>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Sprache</label>
                                <select name="language" class="w-full rounded-lg border-gray-300 text-sm focus:ring-gray-500 focus:border-gray-500">
                                    <option value="de" selected>🇩🇪 Deutsch</option>
                                    <option value="en">🇬🇧 Englisch</option>
                                    <option value="fr">🇫🇷 Französisch</option>
                                    <option value="es">🇪🇸 Spanisch</option>
                                    <option value="it">🇮🇹 Italienisch</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Max. Keywords</label>
                                <input type="number" name="max_keywords" value="50" min="5" max="200" class="w-full rounded-lg border-gray-300 text-sm focus:ring-gray-500 focus:border-gray-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Keyword-Quellen</label>
                                <div class="space-y-1.5 mt-1">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="sources[]" value="export" checked class="rounded border-gray-300 text-gray-900 focus:ring-gray-500">
                                        <span class="text-sm text-gray-700">Export-Keywords</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="sources[]" value="similar" checked class="rounded border-gray-300 text-gray-900 focus:ring-gray-500">
                                        <span class="text-sm text-gray-700">Ähnliche</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="sources[]" value="related" checked class="rounded border-gray-300 text-gray-900 focus:ring-gray-500">
                                        <span class="text-sm text-gray-700">Verwandte</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="sources[]" value="typos" checked class="rounded border-gray-300 text-gray-900 focus:ring-gray-500">
                                        <span class="text-sm text-gray-700">Tippfehler</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <hr class="mb-4 border-gray-200">
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-3">
                                <label class="text-sm font-medium text-gray-700">Produkte auswählen</label>
                                <button type="button" onclick="toggleAll(this)" class="text-xs text-gray-500 hover:text-gray-800 underline">Alle auswählen</button>
                            </div>
                            <div class="max-h-64 overflow-y-auto border border-gray-200 rounded-lg divide-y divide-gray-100">
                                @foreach ($products as $product)
                                    <label class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 cursor-pointer transition">
                                        <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" class="rounded border-gray-300 text-gray-900 focus:ring-gray-500">
                                        <div class="flex-1 min-w-0">
                                            <span class="text-sm font-medium text-gray-900 truncate block">{{ $product->name }}</span>
                                            <span class="text-xs text-gray-400">
                                                {{ $product->keywords ? count($product->keywords) . ' Keywords vorhanden' : 'Keine Keywords' }}
                                                · Erstellt {{ $product->created_at->format('d.m.Y') }}
                                            </span>
                                        </div>
                                        @if ($product->keywords && count($product->keywords) > 0)
                                            <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">✓</span>
                                        @else
                                            <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">—</span>
                                        @endif
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 mb-4">
                            <p class="text-xs text-amber-800">
                                <strong>⚠️ Hinweis:</strong> Die Keyword-Analyse wird über n8n an die SE Ranking API gesendet.
                                Bestehende Keywords werden dabei <strong>überschrieben</strong>. Stelle sicher, dass der n8n-Workflow aktiv ist.
                            </p>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 font-medium text-sm transition">
                            🔍 Analyse starten
                        </button>
                    @else
                        <p class="text-gray-500 text-sm">Keine Produkte vorhanden. Erstelle zuerst ein Produkt.</p>
                    @endif
                </form>
            </div>
        </div>

        <!-- Keywords für Keywords (DataForSEO) -->
        <div x-data="{ open: false }" class="border border-gray-200 rounded-lg overflow-hidden">
            <button @click="open = !open" class="w-full flex items-center justify-between px-5 py-4 bg-gray-50 hover:bg-gray-100 transition text-left">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🔑</span>
                    <div>
                        <h3 class="font-semibold text-gray-900">Verwandte Keywords finden (DataForSEO)</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Ähnliche & verwandte Keywords zu eigenen Seed-Keywords — inkl. Suchvolumen, Wettbewerb & CPC</p>
                    </div>
                </div>
                <svg :class="{ 'rotate-180': open }" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open" x-transition.duration.200ms>
                <form method="POST" action="{{ route('operations.keywords-for-keywords') }}" class="p-5 border-t border-gray-200">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Seed-Keywords <span class="text-gray-400">(max. 20, eines pro Zeile)</span></label>
                            <textarea name="seed_keywords" rows="4" required
                                      placeholder="gravur schlüsselanhänger&#10;schlüsselanhänger personalisiert&#10;geschenk gravur"
                                      class="w-full rounded-lg border-gray-300 text-sm focus:ring-gray-500 focus:border-gray-500 font-mono">{{ old('seed_keywords') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Max. Ergebnisse</label>
                            <input type="number" name="limit" value="{{ old('limit', 100) }}" min="10" max="1000"
                                   class="w-full rounded-lg border-gray-300 text-sm focus:ring-gray-500 focus:border-gray-500">
                            <button type="submit" class="mt-3 w-full px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 font-medium text-sm transition">
                                🔑 Keywords finden
                            </button>
                        </div>
                    </div>
                </form>

                @if (session('kfk_results'))
                    <div class="px-5 pb-5">
                        <p class="text-xs text-gray-500 mb-3">{{ count(session('kfk_results')) }} verwandte Keywords für: <strong>{{ session('kfk_seeds') }}</strong></p>
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-200 text-xs text-gray-500 uppercase tracking-wider">
                                        <th class="px-4 py-2 text-left font-medium">Keyword</th>
                                        <th class="px-4 py-2 text-right font-medium">Volumen</th>
                                        <th class="px-4 py-2 text-right font-medium">Wettbewerb</th>
                                        <th class="px-4 py-2 text-right font-medium">CPC</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach (session('kfk_results') as $m)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-2 text-gray-900">{{ $m['keyword'] }}</td>
                                            <td class="px-4 py-2 text-right font-medium text-gray-700">
                                                {{ $m['volume'] ? number_format($m['volume']) : '—' }}
                                            </td>
                                            <td class="px-4 py-2 text-right">
                                                <x-keyword-competition :competition="$m['competition'] ?? null" />
                                            </td>
                                            <td class="px-4 py-2 text-right text-gray-600">
                                                {{ isset($m['cpc']) && $m['cpc'] ? number_format($m['cpc'], 2, ',', '.') . ' €' : '—' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Keywords for Site (DataForSEO) -->
        <div x-data="{ open: false }" class="border border-gray-200 rounded-lg overflow-hidden">
            <button @click="open = !open" class="w-full flex items-center justify-between px-5 py-4 bg-gray-50 hover:bg-gray-100 transition text-left">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🌐</span>
                    <div>
                        <h3 class="font-semibold text-gray-900">Keywords einer Domain abrufen (DataForSEO)</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Keywords für die eine Domain bei Google rankt — inkl. Suchvolumen, Wettbewerb & CPC</p>
                    </div>
                </div>
                <svg :class="{ 'rotate-180': open }" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open" x-transition.duration.200ms>
                <form method="POST" action="{{ route('operations.keywords-for-site') }}" class="p-5 border-t border-gray-200">
                    @csrf
                    <div class="flex items-end gap-3 mb-4">
                        <div class="flex-1">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Domain eingeben</label>
                            <input type="text" name="domain" value="{{ old('domain', session('kfs_domain')) }}"
                                   placeholder="z.B. amazon.de oder competitor.com"
                                   class="w-full rounded-lg border-gray-300 text-sm focus:ring-gray-500 focus:border-gray-500" required>
                        </div>
                        <div class="w-32">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Max. Ergebnisse</label>
                            <input type="number" name="limit" value="{{ old('limit', 100) }}" min="10" max="1000"
                                   class="w-full rounded-lg border-gray-300 text-sm focus:ring-gray-500 focus:border-gray-500">
                        </div>
                        <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 font-medium text-sm transition whitespace-nowrap">
                            🌐 Keywords abrufen
                        </button>
                    </div>
                </form>

                @if (session('kfs_results'))
                    <div class="px-5 pb-5">
                        <p class="text-xs text-gray-500 mb-3">{{ count(session('kfs_results')) }} Keywords für <strong>{{ session('kfs_domain') }}</strong></p>
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-200 text-xs text-gray-500 uppercase tracking-wider">
                                        <th class="px-4 py-2 text-left font-medium">Keyword</th>
                                        <th class="px-4 py-2 text-right font-medium">Volumen</th>
                                        <th class="px-4 py-2 text-right font-medium">Wettbewerb</th>
                                        <th class="px-4 py-2 text-right font-medium">CPC</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach (session('kfs_results') as $m)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-2 text-gray-900">{{ $m['keyword'] }}</td>
                                            <td class="px-4 py-2 text-right font-medium text-gray-700">
                                                {{ $m['volume'] ? number_format($m['volume']) : '—' }}
                                            </td>
                                            <td class="px-4 py-2 text-right">
                                                <x-keyword-competition :competition="$m['competition'] ?? null" />
                                            </td>
                                            <td class="px-4 py-2 text-right text-gray-600">
                                                {{ isset($m['cpc']) && $m['cpc'] ? number_format($m['cpc'], 2, ',', '.') . ' €' : '—' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Keyword-Metriken (DataForSEO) -->
        <div x-data="{ open: false }" class="border border-gray-200 rounded-lg overflow-hidden">
            <button @click="open = !open" class="w-full flex items-center justify-between px-5 py-4 bg-gray-50 hover:bg-gray-100 transition text-left">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">📊</span>
                    <div>
                        <h3 class="font-semibold text-gray-900">Keyword-Metriken abrufen (DataForSEO)</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Suchvolumen & Wettbewerb für alle Keywords eines Produkts — wird im Keyword-Tab angezeigt</p>
                    </div>
                </div>
                <svg :class="{ 'rotate-180': open }" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open" x-transition.duration.200ms>
                <form method="POST" action="{{ route('operations.keyword-metrics') }}" class="p-5 border-t border-gray-200">
                    @csrf
                    @if ($products->count() > 0)
                        <div class="mb-4">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Produkt auswählen</label>
                            <select name="product_id" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-gray-500 focus:border-gray-500">
                                <option value="">-- Produkt wählen --</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->name }}
                                        ({{ $product->keywords ? count($product->keywords) . ' Keywords' : 'keine Keywords' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
                            <p class="text-xs text-blue-800">
                                <strong>ℹ️ Info:</strong> Die Metriken werden direkt im Keyword-Tab des Produkts angezeigt (Suchvolumen + Wettbewerb-Score).
                            </p>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 font-medium text-sm transition">
                            📊 Metriken abrufen & speichern
                        </button>
                    @else
                        <p class="text-gray-500 text-sm">Keine Produkte vorhanden.</p>
                    @endif
                </form>
            </div>
        </div>

    </div>
</div>

<script>
function toggleAll(btn) {
    const checkboxes = btn.closest('form').querySelectorAll('input[type="checkbox"]');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    checkboxes.forEach(cb => cb.checked = !allChecked);
    btn.textContent = allChecked ? 'Alle auswählen' : 'Alle abwählen';
}
</script>
@endsection
