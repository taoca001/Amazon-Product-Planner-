<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class N8nWebhookService
{
    public static function productCreated(int $productId, string $name, array $keywords = []): void
    {
        $webhookUrl = config('services.n8n.webhook_url');

        Log::info('n8n Webhook: URL=' . ($webhookUrl ?: 'LEER'));

        if (empty($webhookUrl)) {
            return;
        }

        try {
            $response = Http::timeout(5)->post($webhookUrl, [
                'product_id' => $productId,
                'name'       => $name,
                'keywords'   => $keywords,
            ]);
            Log::info('n8n Webhook gesendet: Status=' . $response->status());
        } catch (\Throwable $e) {
            Log::warning('n8n Webhook Fehler: ' . $e->getMessage());
        }
    }
}
