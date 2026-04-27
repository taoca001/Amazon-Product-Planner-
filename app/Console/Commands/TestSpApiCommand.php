<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AmazonSpApiService;

class TestSpApiCommand extends Command
{
    protected $signature = 'spapi:test {asin}';
    protected $description = 'Testet die Amazon SP-API Verbindung und gibt Produktdaten für eine ASIN aus';

    public function handle()
    {
        $asin = $this->argument('asin');
        $this->info("Teste SP-API Lookup für ASIN: $asin");
        try {
            $result = AmazonSpApiService::lookupAsin($asin);
            $this->info('Titel: ' . ($result['title'] ?? '-'));
            $this->info('Brand: ' . ($result['brand'] ?? '-'));
            $this->info('Kategorie: ' . ($result['category'] ?? '-'));
            $this->info('Bullet Points: ' . implode(' | ', $result['bullet_points'] ?? []));
            $this->info('Bilder: ' . implode(' | ', array_column($result['images'] ?? [], 'url')));
            $this->info('Sales Ranks: ' . json_encode($result['sales_ranks'] ?? []));
        } catch (\Throwable $e) {
            $this->error('Fehler: ' . $e->getMessage());
            return 1;
        }
        return 0;
    }
}
