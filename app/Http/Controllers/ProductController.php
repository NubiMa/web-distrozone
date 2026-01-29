<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Get all distinct filter options from database
        $allBrands = Product::distinct()->orderBy('brand')->pluck('brand')->filter();
        $allTypes = Product::distinct()->orderBy('type')->pluck('type')->filter();
        $allColors = ProductVariant::distinct()->orderBy('color')->pluck('color')->filter();

        // Get ALL active products with variants for client-side filtering
        $products = Product::query()
            ->active()
            ->with(['variants' => function($q) {
                $q->where('is_active', true)->where('stock', '>', 0);
            }])
            ->latest()
            ->get();

        // Pass empty arrays for selected filters (client-side will handle this)
        $selectedBrands = [];
        $selectedTypes = [];
        $selectedColors = [];
        $selectedPriceRange = '';
        $searchQuery = '';
        $sortBy = 'newest';

        // Prepare products data for JavaScript
        $productsData = $products->map(function($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'brand' => $p->brand,
                'type' => $p->type,
                'base_price' => $p->base_price,
                'photo_url' => $p->photo_url,
                'price_range' => $p->price_range,
                'colors' => $p->availableColors,
                'min_price' => $p->variants->min('price') ?? $p->base_price,
                'max_price' => $p->variants->max('price') ?? $p->base_price,
            ];
        });

        return view('products.index', compact(
            'products',
            'productsData',
            'allBrands',
            'allTypes',
            'allColors',
            'selectedBrands',
            'selectedTypes',
            'selectedColors',
            'selectedPriceRange',
            'searchQuery',
            'sortBy'
        ));
    }
}
