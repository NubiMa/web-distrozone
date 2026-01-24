<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'brand',
        'type',
        'description',
        'base_price',
        'photo',
        'is_active',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Boot method to auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (!$product->slug) {
                $product->slug = Str::slug($product->name);

                // Ensure unique slug
                $count = 1;
                while (static::where('slug', $product->slug)->exists()) {
                    $product->slug = Str::slug($product->name) . '-' . $count;
                    $count++;
                }
            }
        });
    }

    /**
     * Relationships
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function activeVariants()
    {
        return $this->hasMany(ProductVariant::class)->where('is_active', true);
    }

    public function inStockVariants()
    {
        return $this->hasMany(ProductVariant::class)
            ->where('is_active', true)
            ->where('stock', '>', 0);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('brand', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    }

    public function scopeFilterByBrand($query, $brand)
    {
        if ($brand) {
            return $query->where('brand', $brand);
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
     * Accessors
     */
    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        return asset('images/default-product.png');
    }

    public function getPriceRangeAttribute()
    {
        $prices = $this->variants()->pluck('price');

        if ($prices->isEmpty()) {
            return $this->base_price;
        }

        $min = $prices->min();
        $max = $prices->max();

        if ($min == $max) {
            return 'Rp ' . number_format($min, 0, ',', '.');
        }

        return 'Rp ' . number_format($min, 0, ',', '.') . ' - Rp ' . number_format($max, 0, ',', '.');
    }

    public function getAvailableColorsAttribute()
    {
        return $this->inStockVariants()
            ->distinct()
            ->pluck('color')
            ->toArray();
    }

    public function getAvailableSizesAttribute()
    {
        return $this->inStockVariants()
            ->distinct()
            ->pluck('size')
            ->toArray();
    }

    public function getTotalStockAttribute()
    {
        return $this->variants()->sum('stock');
    }
}
