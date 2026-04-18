<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\N8nWebhookService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProductController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of all products for the authenticated user.
     */
    public function index()
    {
        $products = auth()->user()->products()->latest()->get();
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'keywords' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        $product = auth()->user()->products()->create($validated);

        N8nWebhookService::productCreated(
            $product->id,
            $product->name,
            $product->keywords ?? []
        );

        return redirect()->route('products.show', $product)->with('success', 'Produkt erstellt!');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $this->authorize('view', $product);
        return view('products.show', compact('product'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'keywords' => 'nullable|array',
            'keywords.*' => 'nullable|string',
            'notes' => 'nullable|string',
            'amazon_listing' => 'nullable|array',
            'shopify_listing' => 'nullable|array',
        ]);

        $product->update($validated);

        return redirect()->route('products.show', $product)->with('success', 'Produkt aktualisiert!');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produkt gelöscht!');
    }

    /**
     * Export a single product in the specified format (csv or json).
     */
    public function export(Product $product, $format)
    {
        $this->authorize('view', $product);

        if ($format === 'json') {
            return $this->exportJSON(collect([$product]));
        } elseif ($format === 'csv') {
            return $this->exportCSV([$product]);
        }

        abort(400, 'Ungültiges Export-Format. Verwende: csv oder json');
    }

    /**
     * Export all products for the authenticated user.
     */
    public function exportAll($format)
    {
        $products = auth()->user()->products()->get();

        if ($products->isEmpty()) {
            return redirect()->back()->with('error', 'Keine Produkte zum Exportieren.');
        }

        if ($format === 'json') {
            return $this->exportJSON($products);
        } elseif ($format === 'csv') {
            return $this->exportCSV($products);
        }

        abort(400, 'Ungültiges Export-Format. Verwende: csv oder json');
    }

    /**
     * Export products as JSON.
     */
    private function exportJSON($products)
    {
        $data = $products->map(fn($product) => $this->formatProductData($product))->toArray();
        
        $filename = count($data) === 1
            ? 'product-' . $products->first()->id . '-' . date('Y-m-d-H-i-s') . '.json'
            : 'products-export-' . date('Y-m-d-H-i-s') . '.json';
        
        return response()
            ->json($data, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Export products as CSV.
     */
    private function exportCSV($products)
    {
        $filename = 'products-export-' . date('Y-m-d-H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');
            
            // BOM für Excel UTF-8 Support
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // CSV Header
            fputcsv($file, [
                'Produkt-ID',
                'Produktname',
                'Beschreibung',
                'Preis',
                'Keywords',
                'Notizen',
                'Amazon ASIN',
                'Amazon Titel',
                'Amazon Status',
                'Shopify Product ID',
                'Shopify Titel',
                'Shopify Preis',
                'Erstellt',
                'Zuletzt aktualisiert',
            ], ';');
            
            // CSV Data
            foreach ($products as $product) {
                fputcsv($file, [
                    $product->id,
                    $product->name,
                    $product->description,
                    $product->price,
                    implode(', ', $product->keywords ?? []),
                    $product->notes,
                    $product->amazon_listing['asin'] ?? '',
                    $product->amazon_listing['title'] ?? '',
                    $product->amazon_listing['status'] ?? '',
                    $product->shopify_listing['product_id'] ?? '',
                    $product->shopify_listing['title'] ?? '',
                    $product->shopify_listing['price'] ?? '',
                    $product->created_at?->format('d.m.Y H:i'),
                    $product->updated_at?->format('d.m.Y H:i'),
                ], ';');
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Format product data for export.
     */
    private function formatProductData(Product $product)
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'keywords' => $product->keywords ?? [],
            'notes' => $product->notes,
            'amazon_listing' => $product->amazon_listing ?? null,
            'shopify_listing' => $product->shopify_listing ?? null,
            'created_at' => $product->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $product->updated_at?->format('Y-m-d H:i:s'),
            'image_count' => [
                'raw' => $product->rawImages->count(),
                'product' => $product->productImages->count(),
            ],
        ];
    }
}

