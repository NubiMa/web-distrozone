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
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Drop old products table
        Schema::dropIfExists('products');

        // Create new products table (parent products)
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Nike Sportswear Essential"
            $table->string('slug')->unique(); // URL-friendly name
            $table->string('brand'); // Nike, Adidas, Uniqlo, H&M
            $table->enum('type', ['lengan pendek', 'lengan panjang']); // Short sleeve or long sleeve
            $table->text('description')->nullable();
            $table->decimal('base_price', 10, 2); // Reference price
            $table->string('photo')->nullable(); // Main product image
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Indexes
            $table->index('brand');
            $table->index('type');
            $table->index('is_active');
        });

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
