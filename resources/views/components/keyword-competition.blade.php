@php
    $compRaw = $competition ?? null;
    if ($compRaw === null) {
        $comp = null;
    } elseif (is_numeric($compRaw)) {
        $comp = round($compRaw * 100);
    } else {
        $comp = match(strtoupper((string)$compRaw)) {
            'HIGH'   => 90,
            'MEDIUM' => 50,
            'LOW'    => 15,
            default  => 0,
        };
    }
@endphp

@if ($comp !== null)
    <span class="inline-flex items-center gap-1">
        <span class="w-16 h-1.5 rounded-full bg-gray-200 overflow-hidden inline-block align-middle">
            <span class="h-full rounded-full block {{ $comp > 66 ? 'bg-red-500' : ($comp > 33 ? 'bg-amber-400' : 'bg-green-500') }}"
                  style="width: {{ $comp }}%"></span>
        </span>
        <span class="text-xs text-gray-500">{{ is_numeric($compRaw) ? $comp . '%' : $compRaw }}</span>
    </span>
@else
    <span class="text-gray-400">—</span>
@endif
