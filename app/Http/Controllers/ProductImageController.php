<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    /**
     * Upload image for a product
     */
    public function store(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
            'type' => 'required|in:raw,product',
        ]);

        $file = $request->file('image');
        
        // Generate unique filename
        $filename = now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs(
            'products/' . $product->id . '/' . $request->type,
            $filename,
            'public'
        );

        // Create database record
        $image = $product->images()->create([
            'type' => $request->type,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'order' => $product->images()->where('type', $request->type)->count(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Bild erfolgreich hochgeladen!',
            'image' => [
                'id' => $image->id,
                'type' => $image->type,
                'url' => $image->url,
                'file_name' => $image->file_name,
                'file_size' => $this->formatBytes($image->file_size),
            ]
        ], 201);
    }

    /**
     * Delete an image
     */
    public function destroy(Product $product, ProductImage $image)
    {
        $this->authorize('update', $product);

        // Verify the image belongs to this product
        if ($image->product_id !== $product->id) {
            return response()->json(['error' => 'Nicht autorisiert'], 403);
        }

        // Delete file from storage
        if (Storage::disk('public')->exists($image->file_path)) {
            Storage::disk('public')->delete($image->file_path);
        }

        // Delete database record
        $image->delete();

        return response()->json([
            'success' => true,
            'message' => 'Bild gelöscht!'
        ]);
    }

    /**
     * Reorder images
     */
    public function reorder(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $request->validate([
            'image_ids' => 'required|array',
            'image_ids.*' => 'integer|exists:product_images,id',
        ]);

        foreach ($request->image_ids as $order => $imageId) {
            ProductImage::where('id', $imageId)
                ->where('product_id', $product->id)
                ->update(['order' => $order]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Reihenfolge aktualisiert!'
        ]);
    }

    /**
     * Format bytes to human-readable format
     */
    private function formatBytes($bytes): string
    {
        if ($bytes < 1024) {
            return $bytes . ' B';
        } elseif ($bytes < 1024 * 1024) {
            return round($bytes / 1024, 2) . ' KB';
        } else {
            return round($bytes / (1024 * 1024), 2) . ' MB';
        }
    }
}
