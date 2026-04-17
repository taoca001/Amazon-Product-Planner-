<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            
            // Keywords and metadata
            $table->json('keywords')->nullable();
            $table->text('notes')->nullable();
            
            // Listings (stored as JSON)
            $table->json('amazon_listing')->nullable();
            $table->json('shopify_listing')->nullable();
            
            // File paths
            $table->string('raw_images_path')->nullable();
            $table->string('product_images_path')->nullable();
            
            // Tracking
            $table->timestamp('amazon_synced_at')->nullable();
            $table->timestamp('shopify_synced_at')->nullable();
            $table->timestamp('exported_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
