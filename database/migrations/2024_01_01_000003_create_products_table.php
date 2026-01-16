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
            $table->string('product_code')->unique(); // id_kaos
            $table->string('brand'); // merek_kaos
            $table->enum('type', ['lengan panjang', 'lengan pendek']); // type_kaos
            $table->string('color'); // warna_kaos
            $table->enum('size', ['XS', 'S', 'M', 'L', 'XL', '2XL', '3XL', '4XL', '5XL']); // size
            $table->decimal('selling_price', 10, 2); // harga_jual
            $table->decimal('cost_price', 10, 2); // harga_pokok
            $table->integer('stock')->default(0); // stok_kaos
            $table->string('photo')->nullable(); // foto_kaos
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Index for faster searches
            $table->index(['brand', 'color', 'size']);
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
