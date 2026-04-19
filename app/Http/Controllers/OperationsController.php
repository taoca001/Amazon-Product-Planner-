<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\N8nWebhookService;
use Illuminate\Http\Request;

class OperationsController extends Controller
{
    public function index()
    {
        $products = auth()->user()->products()->latest()->get();
        return view('operations.index', compact('products'));
    }

    public function triggerKeywordAnalysis(Request $request)
    {
        $validated = $request->validate([
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'exists:products,id',
            'region' => 'nullable|string|in:google_de,google_us,google_uk,google_at,google_ch,amazon_de,amazon_us,amazon_uk',
            'language' => 'nullable|string|in:de,en,fr,es,it',
            'sources' => 'nullable|array',
            'sources.*' => 'in:export,similar,related,typos',
            'max_keywords' => 'nullable|integer|min:5|max:200',
        ]);

        $products = Product::whereIn('id', $validated['product_ids'])
            ->where('user_id', auth()->id())
            ->get();

        if ($products->isEmpty()) {
            return back()->with('error', 'Keine gültigen Produkte ausgewählt.');
        }

        $options = [
            'region' => $validated['region'] ?? 'google_de',
            'language' => $validated['language'] ?? 'de',
            'sources' => $validated['sources'] ?? ['export', 'similar', 'related', 'typos'],
            'max_keywords' => (int) ($validated['max_keywords'] ?? 50),
        ];

        $triggered = 0;
        foreach ($products as $product) {
            N8nWebhookService::triggerKeywordAnalysis(
                $product->id,
                $product->name,
                $product->keywords ?? [],
                $options
            );
            $triggered++;
        }

        return back()->with('success', "Keyword-Analyse für {$triggered} Produkt(e) gestartet.");
    }
}
