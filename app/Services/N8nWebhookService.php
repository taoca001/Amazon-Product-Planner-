<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class N8nWebhookService
{
    public static function triggerKeywordAnalysis(
        int $productId,
        string $name,
        array $keywords = [],
        array $options = []
    ): void {
        $webhookUrl = config('services.n8n.webhook_url');

        Log::info('n8n Webhook: URL=' . ($webhookUrl ?: 'LEER'));

        if (empty($webhookUrl)) {
            return;
        }

        try {
            $payload = [
                'product_id' => $productId,
                'name'       => $name,
                'keywords'   => $keywords,
            ];

            if (!empty($options)) {
                $payload['options'] = $options;
            }

            $response = Http::timeout(5)->post($webhookUrl, $payload);
            Log::info('n8n Webhook gesendet: Status=' . $response->status());
        } catch (\Throwable $e) {
            Log::warning('n8n Webhook Fehler: ' . $e->getMessage());
        }
    }

    /**
     * @deprecated Use triggerKeywordAnalysis() instead
     */
    public static function productCreated(int $productId, string $name, array $keywords = []): void
    {
        static::triggerKeywordAnalysis($productId, $name, $keywords);
    }
}
