<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductGdriveFolderController extends Controller
{
    /**
     * Speichert die Google Drive Folder-ID am Produkt.
     * Wird von n8n nach der Ordner-Erstellung aufgerufen.
     */
    public function store(Request $request, Product $product)
    {
        $apiToken = $request->get('api_token');

        if ($apiToken->user_id !== $product->user_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $request->validate([
            'folder_id' => 'required|string|max:255',
        ]);

        $product->update([
            'gdrive_folder_id' => $request->input('folder_id'),
        ]);

        return response()->json([
            'message' => 'Drive-Ordner verknüpft.',
            'folder_id' => $product->gdrive_folder_id,
        ]);
    }
}
