<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AmazonSpApiService
{
    private const LWA_TOKEN_URL = 'https://api.amazon.com/auth/o2/token';
    private const CACHE_KEY = 'sp_api_access_token';

    public static function getAccessToken(): string
    {
        return Cache::remember(self::CACHE_KEY, 3000, function () {
            $response = Http::asForm()->post(self::LWA_TOKEN_URL, [
                'grant_type' => 'refresh_token',
                'refresh_token' => config('services.sp_api.refresh_token'),
                'client_id' => config('services.sp_api.client_id'),
                'client_secret' => config('services.sp_api.client_secret'),
            ]);

            if ($response->failed()) {
                Log::error('SP-API Token-Fehler', ['response' => $response->json()]);
                throw new \RuntimeException('SP-API Authentifizierung fehlgeschlagen: ' . ($response->json('error_description') ?? 'Unbekannter Fehler'));
            }

            return $response->json('access_token');
        });
    }

    public static function lookupAsin(string $asin, bool $retried = false): array
    {
        $token = self::getAccessToken();
        $endpoint = config('services.sp_api.endpoint');
        $marketplaceId = config('services.sp_api.marketplace_id');

        $response = Http::timeout(15)->withHeaders([
            'x-amz-access-token' => $token,
            'Content-Type' => 'application/json',
        ])->get("{$endpoint}/catalog/2022-04-01/items/{$asin}", [
            'marketplaceIds' => $marketplaceId,
            'includedData' => 'summaries,attributes,images,productTypes,salesRanks',
        ]);

        if ($response->failed()) {
            $error = $response->json();
            Log::error('SP-API ASIN-Lookup Fehler', ['asin' => $asin, 'status' => $response->status(), 'response' => $error]);

            if ($response->status() === 403 && !$retried) {
                // Token expired, clear cache and retry once
                Cache::forget(self::CACHE_KEY);
                return self::lookupAsin($asin, true);
            }

            if ($response->status() === 403) {
                throw new \RuntimeException('Zugriff verweigert (403). Prüfe ob deine SP-API Credentials die nötigen Berechtigungen haben (Catalog Items Rolle).');
            }

            if ($response->status() === 404) {
                throw new \RuntimeException("ASIN '{$asin}' wurde auf diesem Marketplace nicht gefunden.");
            }

            $message = $error['errors'][0]['message'] ?? 'ASIN konnte nicht abgerufen werden (HTTP ' . $response->status() . ')';
            throw new \RuntimeException($message);
        }

        return self::parseResponse($response->json(), $marketplaceId);
    }

    private static function parseResponse(array $data, string $marketplaceId): array
    {
        $result = [
            'asin' => $data['asin'] ?? null,
            'title' => null,
            'brand' => null,
            'category' => null,
            'bullet_points' => [],
            'images' => [],
            'sales_ranks' => [],
        ];

        // Summaries (title, brand, category)
        $summaries = $data['summaries'] ?? [];
        foreach ($summaries as $summary) {
            if (($summary['marketplaceId'] ?? '') === $marketplaceId) {
                $result['title'] = $summary['itemName'] ?? null;
                $result['brand'] = $summary['brand'] ?? null;
                $result['category'] = $summary['productType'] ?? ($summary['classificationId'] ?? null);
                break;
            }
        }
        // Fallback to first summary
        if (!$result['title'] && !empty($summaries)) {
            $result['title'] = $summaries[0]['itemName'] ?? null;
            $result['brand'] = $summaries[0]['brand'] ?? null;
            $result['category'] = $summaries[0]['productType'] ?? null;
        }

        // Attributes (bullet points)
        $attributes = $data['attributes'] ?? [];
        if (isset($attributes['bullet_point'])) {
            foreach ($attributes['bullet_point'] as $bp) {
                if (isset($bp['value'])) {
                    $result['bullet_points'][] = $bp['value'];
                }
            }
        }
        // Alternative: item_name for title backup
        if (!$result['title'] && isset($attributes['item_name'])) {
            foreach ($attributes['item_name'] as $name) {
                if (isset($name['value'])) {
                    $result['title'] = $name['value'];
                    break;
                }
            }
        }

        // Images
        $images = $data['images'] ?? [];
        foreach ($images as $imageSet) {
            if (($imageSet['marketplaceId'] ?? '') === $marketplaceId) {
                foreach ($imageSet['images'] ?? [] as $img) {
                    $result['images'][] = [
                        'url' => $img['link'] ?? null,
                        'variant' => $img['variant'] ?? null,
                        'width' => $img['width'] ?? null,
                        'height' => $img['height'] ?? null,
                    ];
                }
                break;
            }
        }

        // Sales Ranks
        $salesRanks = $data['salesRanks'] ?? [];
        foreach ($salesRanks as $rankSet) {
            if (($rankSet['marketplaceId'] ?? '') === $marketplaceId) {
                foreach ($rankSet['classificationRanks'] ?? [] as $rank) {
                    $result['sales_ranks'][] = [
                        'title' => $rank['title'] ?? null,
                        'rank' => $rank['rank'] ?? null,
                    ];
                }
                foreach ($rankSet['displayGroupRanks'] ?? [] as $rank) {
                    $result['sales_ranks'][] = [
                        'title' => $rank['title'] ?? null,
                        'rank' => $rank['rank'] ?? null,
                    ];
                }
                break;
            }
        }

        return $result;
    }
}
