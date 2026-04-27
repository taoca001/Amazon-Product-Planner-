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
        Schema::table('products', function (Blueprint $table) {
            $table->string('gdrive_folder_id')->nullable()->after('product_images_path');
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->string('gdrive_file_id')->nullable()->unique()->after('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('gdrive_folder_id');
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->dropColumn('gdrive_file_id');
        });
    }
};
