<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Fix wishlist foreign key - it already has product_id column,
     * just need to ensure the foreign key is correct.
     */
    public function up(): void
    {
        // Check if foreign key exists and drop it
        $foreignKeys = DB::select("SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_NAME = 'wishlists' 
            AND TABLE_SCHEMA = DATABASE() 
            AND REFERENCED_TABLE_NAME IS NOT NULL");
        
        foreach ($foreignKeys as $fk) {
            try {
                DB::statement("ALTER TABLE `wishlists` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
            } catch (\Exception $e) {
                // Continue if error
            }
        }
        
        // Add correct foreign key
        Schema::table('wishlists', function (Blueprint $table) {
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wishlists', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });
    }
};
