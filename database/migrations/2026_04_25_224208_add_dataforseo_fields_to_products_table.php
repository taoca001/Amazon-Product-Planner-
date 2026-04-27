<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Keyword-Metriken: [{keyword, volume, difficulty, competition}]
            $table->json('keyword_metrics')->nullable()->after('keywords');
            // Wettbewerber-Daten: SERP-Ergebnisse, ASIN-Daten, Reviews
            $table->json('competitor_data')->nullable()->after('keyword_metrics');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['keyword_metrics', 'competitor_data']);
        });
    }
};
