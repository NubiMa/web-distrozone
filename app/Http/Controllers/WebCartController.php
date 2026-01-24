<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class WebCartController extends Controller
{
    // View Cart
    public function index()
    {
        $cartItems = CartItem::where('user_id', Auth::id())
            ->with('productVariant.product')
            ->get();

        // Transform to match the old session structure for view compatibility
        $cart = [];
        $total = 0;
        foreach ($cartItems as $item) {
            $variant = $item->productVariant;
            $cart[$variant->id] = [
                'id' => $variant->product->id,
                'variant_id' => $variant->id,
                'name' => $variant->product->name,
                'size' => $variant->size,
                'color' => $variant->color,
                'quantity' => $item->quantity,
                'price' => $variant->price,
                'image' => $variant->photo ?? $variant->product->photo
            ];
            $total += $variant->price * $item->quantity;
        }

        return view('cart.index', compact('cart', 'total'));
    }

    // Add to Cart
    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $quantity = $request->input('quantity', 1);
        $variantId = $request->input('variant_id');
        
        // Validate variant exists and has stock
        if (!$variantId) {
            return redirect()->back()->with('error', 'Please select a size and color.');
        }
        
        $variant = ProductVariant::findOrFail($variantId);
        
        if ($variant->stock < $quantity) {
            return redirect()->back()->with('error', 'Insufficient stock available.');
        }

        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('product_variant_id', $variantId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'product_variant_id' => $variantId,
                'quantity' => $quantity
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    // Update Cart
    public function update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $cartItem = CartItem::where('user_id', Auth::id())
                ->where('product_variant_id', $request->id)
                ->first();
            
            if ($cartItem) {
                $cartItem->quantity = $request->quantity;
                $cartItem->save();
            }
            
            return redirect()->back()->with('success', 'Cart updated successfully');
        }
    }

    // Remove from Cart
    public function remove(Request $request)
    {
        if ($request->id) {
            CartItem::where('user_id', Auth::id())
                ->where('product_variant_id', $request->id)
                ->delete();
            
            return redirect()->back()->with('success', 'Product removed successfully');
        }
    }
}
