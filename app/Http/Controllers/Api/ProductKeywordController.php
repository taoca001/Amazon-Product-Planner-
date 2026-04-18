<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductKeywordController extends Controller
{
    /**
     * Gibt alle Produkte mit ihren Keywords zurück (für n8n).
     */
    public function index(Request $request)
    {
        $apiToken = $request->get('api_token');

        return response()->json(
            Product::where('user_id', $apiToken->user_id)->get(['id', 'name', 'keywords'])
        );
    }

    /**
     * Aktualisiert die Keywords eines Produkts (für n8n nach SE Ranking Analyse).
     */
    public function update(Request $request, Product $product)
    {
        $apiToken = $request->get('api_token');

        if ($apiToken->user_id !== $product->user_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $request->validate([
            'keywords' => 'required|array',
            'keywords.*' => 'string|max:255',
        ]);

        $product->update(['keywords' => $request->input('keywords')]);

        return response()->json([
            'message' => 'Keywords erfolgreich aktualisiert.',
            'keywords' => $product->keywords,
        ]);
    }
}
