<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductImageUploadController extends Controller
{
    /**
     * Speichert ein Bild für ein Produkt über die API (n8n Google Drive Automation).
     * Erstellt sowohl die Datei als auch den ProductImage DB-Record.
     */
    public function store(Request $request, Product $product)
    {
        $apiToken = $request->get('api_token');

        if ($apiToken->user_id !== $product->user_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $request->validate([
            'image' => 'required|image|max:10240',
            'type' => 'sometimes|in:raw,product',
            'gdrive_file_id' => 'sometimes|nullable|string|max:255',
        ]);

        $file = $request->file('image');
        $type = $request->input('type', 'product');
        $gdriveFileId = $request->input('gdrive_file_id');

        // Duplikat-Prüfung: Drive File-ID bereits vorhanden?
        if ($gdriveFileId && $product->images()->where('gdrive_file_id', $gdriveFileId)->exists()) {
            return response()->json([
                'message' => 'Bild bereits vorhanden (Duplikat übersprungen).',
                'skipped' => true,
            ], 200);
        }

        $filename = now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs(
            'products/' . $product->id . '/' . $type,
            $filename,
            'public'
        );

        $image = $product->images()->create([
            'type' => $type,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'order' => $product->images()->where('type', $type)->count(),
            'gdrive_file_id' => $gdriveFileId,
        ]);

        return response()->json([
            'message' => 'Bild erfolgreich hochgeladen.',
            'image' => [
                'id' => $image->id,
                'type' => $image->type,
                'url' => $image->url,
                'file_name' => $image->file_name,
                'file_size' => $image->file_size,
            ],
        ], 201);
    }
}
