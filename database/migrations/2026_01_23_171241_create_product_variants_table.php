<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('sku')->unique(); // Auto-generated: PRD-001-BLK-M
            $table->string('color'); // Black, White, Grey, Navy, etc.
            $table->string('size'); // S, M, L, XL, 2XL
            $table->integer('stock')->default(0);
            $table->decimal('price', 10, 2); // Can be different from base_price
            $table->decimal('cost_price', 10, 2); // For profit calculation
            $table->string('photo')->nullable(); // Optional variant-specific image
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Indexes
            $table->index('product_id');
            $table->index('sku');
            $table->index('color');
            $table->index('size');
            $table->index('stock');
            $table->index('is_active');

            // Unique constraint: one product can't have duplicate color+size combinations
            $table->unique(['product_id', 'color', 'size']);
        });

        // Update wishlists table to use product_variant_id
        if (Schema::hasTable('wishlists')) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Schema::table('wishlists', function (Blueprint $table) {
                $table->renameColumn('product_id', 'product_variant_id');
            });
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        // Update cart_items table to use product_variant_id
        if (Schema::hasTable('cart_items')) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Schema::table('cart_items', function (Blueprint $table) {
                $table->renameColumn('product_id', 'product_variant_id');
            });
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        // Update transaction_details table to use product_variant_id
        if (Schema::hasTable('transaction_details')) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Schema::table('transaction_details', function (Blueprint $table) {
                $table->renameColumn('product_id', 'product_variant_id');
            });
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rename columns back
        if (Schema::hasColumn('wishlists', 'product_variant_id')) {
            Schema::table('wishlists', function (Blueprint $table) {
                $table->renameColumn('product_variant_id', 'product_id');
            });
        }

        if (Schema::hasColumn('cart_items', 'product_variant_id')) {
            Schema::table('cart_items', function (Blueprint $table) {
                $table->renameColumn('product_variant_id', 'product_id');
            });
        }

        if (Schema::hasColumn('transaction_details', 'product_variant_id')) {
            Schema::table('transaction_details', function (Blueprint $table) {
                $table->renameColumn('product_variant_id', 'product_id');
            });
        }

        Schema::dropIfExists('product_variants');
    }
};
