<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'color',
        'size',
        'stock',
        'price',
        'cost_price',
        'photo',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'stock' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Boot method to auto-generate SKU
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($variant) {
            if (!$variant->sku) {
                $variant->sku = self::generateSku($variant);
            }
        });
    }

    /**
     * Generate SKU: PRD-{product_id}-{COLOR_CODE}-{SIZE}
     * Example: PRD-001-BLK-M
     */
    protected static function generateSku($variant)
    {
        $productId = str_pad($variant->product_id, 3, '0', STR_PAD_LEFT);
        $colorCode = strtoupper(substr($variant->color, 0, 3));
        $size = strtoupper($variant->size);

        return "PRD-{$productId}-{$colorCode}-{$size}";
    }

    /**
     * Relationships
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'product_variant_id');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeByColor($query, $color)
    {
        if ($color) {
            return $query->where('color', $color);
        }
        return $query;
    }

    public function scopeBySize($query, $size)
    {
        if ($size) {
            return $query->where('size', $size);
        }
        return $query;
    }

    /**
     * Accessors
     */
    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        // Fall back to product photo
        return $this->product->photo_url ?? asset('images/default-product.png');
    }

    public function getProfitAttribute()
    {
        return $this->price - $this->cost_price;
    }

    public function getProfitMarginAttribute()
    {
        if ($this->price > 0) {
            return (($this->price - $this->cost_price) / $this->price) * 100;
        }
        return 0;
    }

    /**
     * Helper methods
     */
    public function decreaseStock($quantity)
    {
        if ($this->stock >= $quantity) {
            $this->stock -= $quantity;
            $this->save();
            return true;
        }
        return false;
    }

    public function increaseStock($quantity)
    {
        $this->stock += $quantity;
        $this->save();
        return true;
    }

    public function isAvailable()
    {
        return $this->is_active && $this->stock > 0;
    }
}
