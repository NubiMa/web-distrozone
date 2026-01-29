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
            
            // Variants
            'variants' => 'required|array|min:1',
            'variants.*.color' => 'required|string|max:50',
            'variants.*.color_hex' => 'required|string|max:7', // validate hex code
            'variants.*.size' => 'required|in:XS,S,M,L,XL,2XL,3XL,4XL,5XL',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Create product (photo will be set from first variant)
            $product = Product::create([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'brand' => $validated['brand'],
                'type' => $validated['type'],
                'description' => $validated['description'] ?? null,
                'base_price' => $validated['base_price'],
                'photo' => null, // Will be updated from first variant
                'is_active' => true,
            ]);

            $firstVariantPhoto = null;

            // Create variants
            foreach ($validated['variants'] as $index => $variantData) {
                // Handle variant photo upload
                $variantPhoto = null;
                if ($request->hasFile("variants.{$index}.photo")) {
                    $file = $request->file("variants.{$index}.photo");
                    
                    // Generate unique filename
                    $color = str_replace(' ', '_', $variantData['color']);
                    $size = $variantData['size'];
                    $filename = "{$product->id}_{$color}_{$size}_" . time() . '.' . $file->extension();
                    
                    // Ensure directory exists
                    $directory = public_path('images/products/variants');
                    if (!file_exists($directory)) {
                        mkdir($directory, 0755, true);
                    }
                    
                    // Move file to public directory
                    $file->move($directory, $filename);
                    $variantPhoto = $filename;
                    
                    // Store first variant photo for product thumbnail
                    if ($index === 0 || $firstVariantPhoto === null) {
                        $firstVariantPhoto = $filename;
                    }
                }
                
                ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => ProductVariant::generateSKU($product->id, $variantData['color'], $variantData['size']),
                    'color' => $variantData['color'],
                    'color_hex' => $variantData['color_hex'] ?? null,
                    'size' => $variantData['size'],
                    'price' => $variantData['price'],
                    'stock' => $variantData['stock'],
                    'photo' => $variantPhoto,
                ]);
            }

            // Update product photo with first variant's photo
            if ($firstVariantPhoto) {
                $product->update(['photo' => $firstVariantPhoto]);
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
            'is_active' => 'nullable|boolean',
            
            // Variants
            'variants' => 'required|array|min:1',
            'variants.*.id' => 'nullable|exists:product_variants,id',
            'variants.*.color' => 'required|string|max:50',
            'variants.*.color_hex' => 'required|string|max:7', // validate hex code
            'variants.*.size' => 'required|in:XS,S,M,L,XL,2XL,3XL,4XL,5XL',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Update product (photo will be from first variant)
            $product->update([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'brand' => $validated['brand'],
                'type' => $validated['type'],
                'description' => $validated['description'] ?? null,
                'base_price' => $validated['base_price'],
                'is_active' => $validated['is_active'] ?? $product->is_active,
            ]);

            // Track existing variant IDs
            $existingVariantIds = [];

            // Update or create variants
            foreach ($validated['variants'] as $index => $variantData) {
                // Handle variant photo upload
                $variantPhoto = null;
                if ($request->hasFile("variants.{$index}.photo")) {
                    $file = $request->file("variants.{$index}.photo");
                    
                    // Generate unique filename: {product_id}_{color}_{size}_{timestamp}.{extension}
                    $color = str_replace(' ', '_', $variantData['color']);
                    $size = $variantData['size'];
                    $filename = "{$product->id}_{$color}_{$size}_" . time() . '.' . $file->extension();
                    
                    // Ensure directory exists
                    $directory = public_path('images/products/variants');
                    if (!file_exists($directory)) {
                        mkdir($directory, 0755, true);
                    }
                    
                    // Move file to public directory
                    $file->move($directory, $filename);
                    $variantPhoto = $filename;
                }
                
                if (isset($variantData['id'])) {
                    // Update existing variant
                    $variant = ProductVariant::findOrFail($variantData['id']);
                    
                    // Delete old photo if new one uploaded
                    if ($variantPhoto && $variant->photo) {
                        $oldPhotoPath = public_path('images/products/variants/' . $variant->photo);
                        if (file_exists($oldPhotoPath)) {
                            unlink($oldPhotoPath);
                        }
                    }
                    
                    $variant->update([
                        'color' => $variantData['color'],
                        'color_hex' => $variantData['color_hex'] ?? null,
                        'size' => $variantData['size'],
                        'price' => $variantData['price'],
                        'stock' => $variantData['stock'],
                        'sku' => ProductVariant::generateSKU($product->id, $variantData['color'], $variantData['size']),
                        'photo' => $variantPhoto ?? $variant->photo,
                    ]);
                    $existingVariantIds[] = $variant->id;
                } else {
                    // Create new variant
                    $newVariant = ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => ProductVariant::generateSKU($product->id, $variantData['color'], $variantData['size']),
                        'color' => $variantData['color'],
                        'color_hex' => $variantData['color_hex'] ?? null,
                        'size' => $variantData['size'],
                        'price' => $variantData['price'],
                        'stock' => $variantData['stock'],
                        'photo' => $variantPhoto,
                    ]);
                    $existingVariantIds[] = $newVariant->id;
                }
            }

            // Delete variants that are not in the update (and their photos)
            $variantsToDelete = $product->variants()->whereNotIn('id', $existingVariantIds)->get();
            foreach ($variantsToDelete as $variant) {
                // Delete variant photo if exists
                if ($variant->photo) {
                    $photoPath = public_path('images/products/variants/' . $variant->photo);
                    if (file_exists($photoPath)) {
                        unlink($photoPath);
                    }
                }
                $variant->delete();
            }

            // Update product photo to use first variant's photo (from form submission order)
            // Get the first variant from the submitted form (index 0)
            if (!empty($validated['variants'])) {
                $firstVariantData = $validated['variants'][0];
                
                // Find the corresponding variant record
                if (isset($firstVariantData['id'])) {
                    $firstVariant = ProductVariant::find($firstVariantData['id']);
                    if ($firstVariant && $firstVariant->photo) {
                        $product->update(['photo' => $firstVariant->photo]);
                    }
                }
            }

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



