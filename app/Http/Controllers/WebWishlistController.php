<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class WebWishlistController extends Controller
{
    public function index()
    {
        $wishlistItems = Wishlist::where('user_id', Auth::id())
            ->with('product')
            ->get()
            ->pluck('product');
            
        return view('customer.wishlist', compact('wishlistItems'));
    }

    public function store(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        Wishlist::firstOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id
        ]);

        return back()->with('success', 'Produk ditambahkan ke wishlist!');
    }

    public function destroy($productId)
    {
        Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->delete();

        return back()->with('success', 'Produk dihapus dari wishlist.');
    }

    public function moveAllToCart()
    {
        $wishlistItems = Wishlist::where('user_id', Auth::id())->get();

        foreach ($wishlistItems as $item) {
            // Add to cart if not exists (or increment? logic depends, usually add 1)
            $cartItem = CartItem::where('user_id', Auth::id())
                ->where('product_id', $item->product_id)
                ->first();

            if ($cartItem) {
                $cartItem->quantity += 1;
                $cartItem->save();
            } else {
                CartItem::create([
                    'user_id' => Auth::id(),
                    'product_id' => $item->product_id,
                    'quantity' => 1
                ]);
            }
            
            $item->delete(); // Remove from wishlist after moving
        }

        return back()->with('success', 'Semua item dipindahkan ke keranjang!');
    }
}
