<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\N8nWebhookService;
use Illuminate\Http\Request;

class ProductDriveSyncController extends Controller
{
    /**
     * Löst den Google Drive Sync für ein Produkt aus.
     * n8n liest den Drive-Ordner des Produkts und lädt neue Bilder hoch.
     */
    public function sync(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $folderId = $product->gdrive_folder_id;

        if (empty($folderId)) {
            return response()->json([
                'message' => 'Kein Google Drive Ordner für dieses Produkt hinterlegt.',
            ], 422);
        }

        N8nWebhookService::triggerDriveSync($product->id, $product->name, $folderId);

        return response()->json([
            'message' => 'Drive-Sync gestartet. Neue Bilder erscheinen in Kürze.',
        ]);
    }
}
