<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    /**
     * Display a listing of products with filters
     */
    public function index(Request $request)
    {
        $query = Product::with('variants');

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('brand', 'LIKE', "%{$search}%");
            });
        }

        // Filter by brand
        if ($request->has('brand') && $request->brand != '') {
            $query->where('brand', $request->brand);
        }

        // Filter by type
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        $products = $query->latest()->paginate(15);

        // Get unique brands and types for filters
        $brands = Product::distinct()->pluck('brand');
        $types = Product::distinct()->pluck('type');

        return view('admin.products.index', compact('products', 'brands', 'types'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'type' => 'required|in:lengan panjang,lengan pendek',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            
            // Variants
            'variants' => 'required|array|min:1',
            'variants.*.color' => 'required|string|max:50',
            'variants.*.size' => 'required|in:XS,S,M,L,XL,2XL,3XL,4XL,5XL',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Handle photo upload
            $photoPath = $request->file('photo')->store('products', 'public');

            // Create product
            $product = Product::create([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'brand' => $validated['brand'],
                'type' => $validated['type'],
                'description' => $validated['description'] ?? null,
                'base_price' => $validated['base_price'],
                'photo' => $photoPath,
                'is_active' => true,
            ]);

            // Create variants
            foreach ($validated['variants'] as $variantData) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => ProductVariant::generateSKU($product->id, $variantData['color'], $variantData['size']),
                    'color' => $variantData['color'],
                    'size' => $variantData['size'],
                    'price' => $variantData['price'],
                    'stock' => $variantData['stock'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Delete uploaded photo if exists
            if (isset($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal menambahkan produk: ' . $e->getMessage()]);
        }
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $product = Product::with('variants')->findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'type' => 'required|in:lengan panjang,lengan pendek',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'nullable|boolean',
            
            // Variants
            'variants' => 'required|array|min:1',
            'variants.*.id' => 'nullable|exists:product_variants,id',
            'variants.*.color' => 'required|string|max:50',
            'variants.*.size' => 'required|in:XS,S,M,L,XL,2XL,3XL,4XL,5XL',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Handle photo upload
            $photoPath = $product->photo;
            if ($request->hasFile('photo')) {
                // Delete old photo
                if ($product->photo) {
                    Storage::disk('public')->delete($product->photo);
                }
                $photoPath = $request->file('photo')->store('products', 'public');
            }

            // Update product
            $product->update([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'brand' => $validated['brand'],
                'type' => $validated['type'],
                'description' => $validated['description'] ?? null,
                'base_price' => $validated['base_price'],
                'photo' => $photoPath,
                'is_active' => $validated['is_active'] ?? $product->is_active,
            ]);

            // Track existing variant IDs
            $existingVariantIds = [];

            // Update or create variants
            foreach ($validated['variants'] as $variantData) {
                if (isset($variantData['id'])) {
                    // Update existing variant
                    $variant = ProductVariant::findOrFail($variantData['id']);
                    $variant->update([
                        'color' => $variantData['color'],
                        'size' => $variantData['size'],
                        'price' => $variantData['price'],
                        'stock' => $variantData['stock'],
                        'sku' => ProductVariant::generateSKU($product->id, $variantData['color'], $variantData['size']),
                    ]);
                    $existingVariantIds[] = $variant->id;
                } else {
                    // Create new variant
                    $newVariant = ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => ProductVariant::generateSKU($product->id, $variantData['color'], $variantData['size']),
                        'color' => $variantData['color'],
                        'size' => $variantData['size'],
                        'price' => $variantData['price'],
                        'stock' => $variantData['stock'],
                    ]);
                    $existingVariantIds[] = $newVariant->id;
                }
            }

            // Delete variants that are not in the update
            $product->variants()->whereNotIn('id', $existingVariantIds)->delete();

            DB::commit();

            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal mengupdate produk: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified product
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);

            // Delete photo if exists
            if ($product->photo) {
                Storage::disk('public')->delete($product->photo);
            }

            // Delete product (will cascade delete variants)
            $product->delete();

            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal menghapus produk: ' . $e->getMessage()]);
        }
    }
}
