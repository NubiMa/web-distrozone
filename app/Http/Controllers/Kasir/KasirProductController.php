<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class KasirProductController extends Controller
{
    /**
     * Display a listing of products with search
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $products = \App\Models\Product::with(['variants' => function($q) {
                // Ensure we get variants to show stock
            }])
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhereHas('variants', function ($q) use ($search) {
                        $q->where('sku', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(12);

        return view('kasir.inventory', compact('products', 'search'));
    }

    /**
     * Display the specified product
     */
    public function show($id)
    {
        $product = Product::active()->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }
}
