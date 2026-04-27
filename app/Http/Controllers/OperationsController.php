<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\DataForSeoService;
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
            'product_ids'   => 'required|array|min:1',
            'product_ids.*' => 'exists:products,id',
            'region'        => 'nullable|string|in:google_de,google_us,google_uk,google_at,google_ch,amazon_de,amazon_us,amazon_uk',
            'language'      => 'nullable|string|in:de,en,fr,es,it',
            'sources'       => 'nullable|array',
            'sources.*'     => 'in:export,similar,related,typos',
            'max_keywords'  => 'nullable|integer|min:5|max:200',
        ]);

        $products = Product::whereIn('id', $validated['product_ids'])
            ->where('user_id', auth()->id())
            ->get();

        if ($products->isEmpty()) {
            return back()->with('error', 'Keine gültigen Produkte ausgewählt.');
        }

        $options = [
            'region'       => $validated['region'] ?? 'google_de',
            'language'     => $validated['language'] ?? 'de',
            'sources'      => $validated['sources'] ?? ['export', 'similar', 'related', 'typos'],
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

    /**
     * Keyword-Metriken (Volumen + Competition) für ein Produkt via DataForSEO abrufen und speichern.
     */
    public function keywordMetrics(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $product = Product::where('id', $validated['product_id'])
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if (empty($product->keywords)) {
            return back()->with('error', 'Dieses Produkt hat keine Keywords. Führe zuerst eine Keyword-Analyse durch.');
        }

        try {
            $metrics = DataForSeoService::getBulkKeywordMetrics($product->keywords);
            $product->update(['keyword_metrics' => $metrics]);
            return back()->with('success', count($metrics) . ' Keyword-Metriken für "' . $product->name . '" gespeichert.');
        } catch (\RuntimeException $e) {
            return back()->with('error', 'Keyword-Metriken fehlgeschlagen: ' . $e->getMessage());
        }
    }

    /**
     * Keywords for Site: Keywords einer Domain via DataForSEO abrufen.
     */
    public function keywordsForSite(Request $request)
    {
        $validated = $request->validate([
            'domain'  => 'required|string|max:255',
            'limit'   => 'nullable|integer|min:10|max:1000',
        ]);

        $domain = $validated['domain'];
        $limit  = (int) ($validated['limit'] ?? 100);

        try {
            $results = DataForSeoService::getKeywordsForSite($domain, 2276, $limit);
            return back()
                ->with('kfs_results', $results)
                ->with('kfs_domain', $domain);
        } catch (\RuntimeException $e) {
            return back()->with('error', 'Keywords-for-Site fehlgeschlagen: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Keywords for Keywords: Ähnliche Keywords zu Seed-Keywords via DataForSEO abrufen.
     */
    public function keywordsForKeywords(Request $request)
    {
        $validated = $request->validate([
            'seed_keywords' => 'required|string|max:1000',
            'limit'         => 'nullable|integer|min:10|max:1000',
        ]);

        $keywords = array_filter(array_map('trim', preg_split('/\r?\n/', $validated['seed_keywords'])));
        $limit    = (int) ($validated['limit'] ?? 100);

        if (empty($keywords)) {
            return back()->with('error', 'Bitte mindestens ein Keyword eingeben.')->withInput();
        }

        try {
            $results = DataForSeoService::getKeywordsForKeywords(array_values($keywords), 2276, $limit);
            return back()
                ->with('kfk_results', $results)
                ->with('kfk_seeds', implode(', ', array_slice($keywords, 0, 5)) . (count($keywords) > 5 ? ' ...' : ''));
        } catch (\RuntimeException $e) {
            return back()->with('error', 'Keywords-for-Keywords fehlgeschlagen: ' . $e->getMessage())->withInput();
        }
    }
}

