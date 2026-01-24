<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the old foreign key constraint that references products table
        // The constraint name is cart_items_product_id_foreign (from original migration)
        DB::statement('ALTER TABLE `cart_items` DROP FOREIGN KEY `cart_items_product_id_foreign`');

        // Add the correct foreign key constraint that references product_variants
        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreign('product_variant_id')
                  ->references('id')
                  ->on('product_variants')
                  ->onDelete('cascade');
        });
        
        // Drop old unique constraint and add new one
        try {
            DB::statement('ALTER TABLE `cart_items` DROP INDEX `cart_items_user_id_product_id_unique`');
        } catch (\Exception $e) {
            // Ignore if doesn't exist
        }
        
        DB::statement('ALTER TABLE `cart_items` ADD UNIQUE `cart_items_user_variant_unique` (`user_id`, `product_variant_id`)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['product_variant_id']);
        });
        
        DB::statement('ALTER TABLE `cart_items` DROP INDEX `cart_items_user_variant_unique`');
    }
};
