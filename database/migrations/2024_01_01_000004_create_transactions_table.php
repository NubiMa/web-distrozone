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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique(); // Invoice number
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Customer (for online) or Kasir (for offline)
            $table->foreignId('cashier_id')->nullable()->constrained('users')->onDelete('set null'); // Kasir who processed
            $table->enum('transaction_type', ['offline', 'online'])->default('offline');
            
            // Payment information
            $table->enum('payment_method', ['tunai', 'qris', 'transfer']); // Cash, QRIS, Bank Transfer
            $table->enum('payment_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->string('payment_proof')->nullable(); // For online orders
            
            // Order details
            $table->decimal('subtotal', 12, 2); // Total product price
            $table->decimal('shipping_cost', 10, 2)->default(0); // Ongkir (always charged for online)
            $table->decimal('total', 12, 2); // Subtotal + Shipping
            
            // Shipping information (for online orders)
            $table->string('shipping_destination')->nullable(); // City/region
            $table->text('shipping_address')->nullable();
            $table->string('recipient_name')->nullable();
            $table->string('recipient_phone')->nullable();
            $table->integer('weight_kg')->nullable(); // Calculated weight
            
            // Order status
            $table->enum('order_status', ['pending', 'processing', 'shipped', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Indexes
            $table->index('transaction_code');
            $table->index('transaction_type');
            $table->index('order_status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
