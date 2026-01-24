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
        // The constraint name is transaction_details_product_id_foreign (from original migration)
        try {
            DB::statement('ALTER TABLE `transaction_details` DROP FOREIGN KEY `transaction_details_product_id_foreign`');
        } catch (\Exception $e) {
            // Ignore if doesn't exist
        }

        // Add the correct foreign key constraint that references product_variants
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->foreign('product_variant_id')
                  ->references('id')
                  ->on('product_variants')
                  ->onDelete('cascade');
        });
        
        // Drop old unique constraint and add new one if needed
        try {
            DB::statement('ALTER TABLE `transaction_details` DROP INDEX `transaction_details_transaction_id_product_id_unique`');
        } catch (\Exception $e) {
            // Ignore if doesn't exist
        }
        
        try {
            DB::statement('ALTER TABLE `transaction_details` ADD UNIQUE `transaction_details_transaction_variant_unique` (`transaction_id`, `product_variant_id`)');
        } catch (\Exception $e) {
            // Ignore if already exists
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropForeign(['product_variant_id']);
        });
        
        try {
            DB::statement('ALTER TABLE `transaction_details` DROP INDEX `transaction_details_transaction_variant_unique`');
        } catch (\Exception $e) {
            // Ignore
        }
    }
};
