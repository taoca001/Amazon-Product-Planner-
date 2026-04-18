<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductImageUploadController extends Controller
{
    /**
     * Speichert ein Bild für ein Produkt über die API (n8n).
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'image' => 'required|image|max:10240',
        ]);

        $path = $request->file('image')->store('products/' . $product->id, 'public');

        return response()->json([
            'message' => 'Bild erfolgreich hochgeladen.',
            'path' => $path,
            'url' => Storage::disk('public')->url($path),
        ], 201);
    }
}
