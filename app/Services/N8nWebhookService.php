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

    public static function triggerDriveSync(int $productId, string $name, string $folderId): void
    {
        $webhookUrl = config('services.n8n.drive_sync_webhook_url');

        if (empty($webhookUrl)) {
            Log::info('n8n Drive-Sync Webhook nicht konfiguriert (N8N_DRIVE_SYNC_WEBHOOK_URL fehlt)');
            return;
        }

        try {
            $response = Http::timeout(5)->post($webhookUrl, [
                'product_id' => $productId,
                'name'       => $name,
                'folder_id'  => $folderId,
            ]);
            Log::info('n8n Drive-Sync Webhook gesendet: Status=' . $response->status());
        } catch (\Throwable $e) {
            Log::warning('n8n Drive-Sync Webhook Fehler: ' . $e->getMessage());
        }
    }

    public static function triggerCreateDriveFolder(int $productId, string $name): void
    {
        $webhookUrl = config('services.n8n.folder_webhook_url');

        if (empty($webhookUrl)) {
            Log::info('n8n Drive-Folder Webhook nicht konfiguriert (N8N_FOLDER_WEBHOOK_URL fehlt)');
            return;
        }

        try {
            $response = Http::timeout(5)->post($webhookUrl, [
                'product_id' => $productId,
                'name'       => $name,
            ]);
            Log::info('n8n Drive-Folder Webhook gesendet: Status=' . $response->status());
        } catch (\Throwable $e) {
            Log::warning('n8n Drive-Folder Webhook Fehler: ' . $e->getMessage());
        }
    }

}
