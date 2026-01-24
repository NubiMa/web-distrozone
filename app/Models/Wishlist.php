<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ProductVariant;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_variant_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
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
}
