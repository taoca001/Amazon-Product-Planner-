<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DataForSeoService
{
    private static function client(): \Illuminate\Http\Client\PendingRequest
    {
        return Http::timeout(20)
            ->withBasicAuth(
                config('services.dataforseo.login'),
                config('services.dataforseo.password')
            )
            ->baseUrl('https://api.dataforseo.com/v3/');
    }

    /**
     * Keyword-Metriken: Suchvolumen + Schwierigkeit für eine Liste von Keywords.
     */
    public static function getBulkKeywordMetrics(array $keywords, int $locationCode = 2276): array
    {
        if (empty($keywords)) {
            return [];
        }

        // Max 700 Keywords pro Request (DataForSEO Limit)
        $keywords = array_slice($keywords, 0, 700);

        $response = self::client()->post('keywords_data/google_ads/search_volume/live', [
            [
                'keywords'      => $keywords,
                'location_code' => $locationCode,
                'language_code' => 'de',
            ],
        ]);

        if ($response->failed()) {
            throw new \RuntimeException('DataForSEO API nicht erreichbar (HTTP ' . $response->status() . ').');
        }

        $data = $response->json();
        $task = $data['tasks'][0] ?? null;

        if (!$task || ($task['status_code'] ?? 0) !== 20000) {
            throw new \RuntimeException('DataForSEO Keyword-Metriken Fehler: ' . ($task['status_message'] ?? 'Unbekannt'));
        }

        $results = [];
        foreach ($task['result'] ?? [] as $item) {
            $results[] = [
                'keyword'     => $item['keyword'] ?? '',
                'volume'      => $item['search_volume'] ?? 0,
                'competition' => $item['competition'] ?? null,
                'cpc'         => $item['cpc'] ?? null,
            ];
        }

        // Nach Volumen sortieren
        usort($results, fn($a, $b) => ($b['volume'] ?? 0) <=> ($a['volume'] ?? 0));

        return $results;
    }

    /**
     * Keywords for Keywords: Ähnliche/verwandte Keywords zu Seed-Keywords abrufen.
     */
    public static function getKeywordsForKeywords(array $keywords, int $locationCode = 2276, int $limit = 100): array
    {
        $keywords = array_slice(array_filter(array_map('trim', $keywords)), 0, 20);

        if (empty($keywords)) {
            return [];
        }

        $response = self::client()->post('keywords_data/google_ads/keywords_for_keywords/live', [
            [
                'keywords'      => $keywords,
                'location_code' => $locationCode,
                'language_code' => 'de',
                'sort_by'       => 'search_volume',
            ],
        ]);

        if ($response->failed()) {
            throw new \RuntimeException('DataForSEO API nicht erreichbar (HTTP ' . $response->status() . ').');
        }

        $data = $response->json();
        $task = $data['tasks'][0] ?? null;

        if (!$task || ($task['status_code'] ?? 0) !== 20000) {
            throw new \RuntimeException('DataForSEO Keywords-for-Keywords Fehler: ' . ($task['status_message'] ?? 'Unbekannt'));
        }

        $results = [];
        foreach (array_slice($task['result'] ?? [], 0, $limit) as $item) {
            $results[] = [
                'keyword'     => $item['keyword'] ?? '',
                'volume'      => $item['search_volume'] ?? 0,
                'competition' => $item['competition'] ?? null,
                'cpc'         => $item['cpc'] ?? null,
            ];
        }

        return $results;
    }

    /**
     * Keywords for Site: Keywords abrufen, für die eine Domain rankt (Google Ads).
     */
    public static function getKeywordsForSite(string $domain, int $locationCode = 2276, int $limit = 100): array
    {
        // Domain bereinigen (kein https://, kein Pfad)
        $domain = preg_replace('#^https?://|/.*$#', '', trim($domain));

        $response = self::client()->post('keywords_data/google_ads/keywords_for_site/live', [
            [
                'target'        => $domain,
                'location_code' => $locationCode,
                'language_code' => 'de',
                'sort_by'       => 'search_volume',
            ],
        ]);

        if ($response->failed()) {
            throw new \RuntimeException('DataForSEO API nicht erreichbar (HTTP ' . $response->status() . ').');
        }

        $data = $response->json();
        $task = $data['tasks'][0] ?? null;

        if (!$task || ($task['status_code'] ?? 0) !== 20000) {
            throw new \RuntimeException('DataForSEO Keywords-for-Site Fehler: ' . ($task['status_message'] ?? 'Unbekannt'));
        }

        $results = [];
        foreach (array_slice($task['result'] ?? [], 0, $limit) as $item) {
            $results[] = [
                'keyword'     => $item['keyword'] ?? '',
                'volume'      => $item['search_volume'] ?? 0,
                'competition' => $item['competition'] ?? null,
                'cpc'         => $item['cpc'] ?? null,
            ];
        }

        return $results;
    }
}
