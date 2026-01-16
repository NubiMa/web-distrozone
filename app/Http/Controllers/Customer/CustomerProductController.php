<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CustomerProductController extends Controller
{
    /**
     * Display a listing of active products
     */
    public function index(Request $request)
    {
        $query = Product::active()->inStock();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $query->search($request->search);
        }

        // Filters
        if ($request->has('brand') && $request->brand != '') {
            $query->filterByBrand($request->brand);
        }

        if ($request->has('size') && $request->size != '') {
            $query->filterBySize($request->size);
        }

        if ($request->has('color') && $request->color != '') {
            $query->filterByColor($request->color);
        }

        if ($request->has('type') && $request->type != '') {
            $query->filterByType($request->type);
        }

        // Sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('selling_price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('selling_price', 'desc');
                    break;
                case 'newest':
                    $query->latest();
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    /**
     * Display the specified product detail
     */
    public function show($id)
    {
        $product = Product::active()->inStock()->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }

    /**
     * Get available filter options
     */
    public function filterOptions()
    {
        $brands = Product::active()->inStock()->distinct()->pluck('brand');
        $colors = Product::active()->inStock()->distinct()->pluck('color');
        $sizes = ['XS', 'S', 'M', 'L', 'XL', '2XL', '3XL', '4XL', '5XL'];
        $types = ['lengan panjang', 'lengan pendek'];

        return response()->json([
            'success' => true,
            'data' => [
                'brands' => $brands,
                'colors' => $colors,
                'sizes' => $sizes,
                'types' => $types,
            ],
        ]);
    }
}
