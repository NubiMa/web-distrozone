<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'transaction_id',
        'product_variant_id', // Changed from product_id
        'quantity',
        'price',
        'cost_price',
        'subtotal',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Relationships
     */

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    // Alias for backwards compatibility
    public function product()
    {
        return $this->productVariant;
    }

    /**
     * Accessors
     */

    public function getProfitAttribute()
    {
        return ($this->price - $this->cost_price) * $this->quantity;
    }

    /**
     * Boot method to auto-calculate subtotal
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($detail) {
            $detail->subtotal = $detail->price * $detail->quantity;
        });

        static::updating(function ($detail) {
            $detail->subtotal = $detail->price * $detail->quantity;
        });
    }
}
