<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class WebCartController extends Controller
{
    // View Cart
    public function index()
    {
        $cartItems = CartItem::where('user_id', Auth::id())
            ->with('product')
            ->get();

        // Transform to match the old session structure for view compatibility
        $cart = [];
        $total = 0;
        foreach ($cartItems as $item) {
            $cart[$item->product_id] = [
                'id' => $item->product->id,
                'name' => $item->product->name,
                'quantity' => $item->quantity,
                'price' => $item->product->selling_price,
                'image' => $item->product->image
            ];
            $total += $item->product->selling_price * $item->quantity;
        }

        return view('cart.index', compact('cart', 'total'));
    }

    // Add to Cart
    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $quantity = $request->input('quantity', 1);

        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $id,
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
                ->where('product_id', $request->id)
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
                ->where('product_id', $request->id)
                ->delete();
            
            return redirect()->back()->with('success', 'Product removed successfully');
        }
    }
}
