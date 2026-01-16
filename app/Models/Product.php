<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_code',
        'brand',
        'type',
        'color',
        'size',
        'selling_price',
        'cost_price',
        'stock',
        'photo',
        'description',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'selling_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'stock' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Boot method to auto-generate product_code
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (!$product->product_code) {
                // Generate product code: PRD-YYYYMMDD-XXX
                $date = now()->format('Ymd');
                $count = static::whereDate('created_at', now())->count() + 1;
                $product->product_code = 'PRD-' . $date . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * Relationships
     */
    
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    /**
     * Accessors
     */
    
    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        return asset('images/default-product.png');
    }

    public function getProfitMarginAttribute()
    {
        if ($this->selling_price > 0) {
            return (($this->selling_price - $this->cost_price) / $this->selling_price) * 100;
        }
        return 0;
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

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('product_code', 'like', "%{$search}%")
              ->orWhere('brand', 'like', "%{$search}%")
              ->orWhere('color', 'like', "%{$search}%")
              ->orWhere('size', 'like', "%{$search}%");
        });
    }

    public function scopeFilterByBrand($query, $brand)
    {
        if ($brand) {
            return $query->where('brand', $brand);
        }
        return $query;
    }

    public function scopeFilterBySize($query, $size)
    {
        if ($size) {
            return $query->where('size', $size);
        }
        return $query;
    }

    public function scopeFilterByColor($query, $color)
    {
        if ($color) {
            return $query->where('color', $color);
        }
        return $query;
    }

    public function scopeFilterByType($query, $type)
    {
        if ($type) {
            return $query->where('type', $type);
        }
        return $query;
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
}
