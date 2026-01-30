<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        if (empty($query)) {
            return redirect()->back();
        }

        $lowerQuery = Str::lower($query);

        // Keyword Navigation Map
        $keywords = [
            // Orders
            'order' => 'orders.index',
            'pesanan' => 'orders.index',
            'history' => 'orders.index',
            'riwayat' => 'orders.index',
            
            // Address
            'address' => 'address.index',
            'alamat' => 'address.index',
            'location' => 'address.index',
            'lokasi' => 'address.index',
            
            // Profile
            'profile' => 'profile.show',
            'profil' => 'profile.show',
            'akun' => 'profile.show',
            'account' => 'profile.show',
            
            // Cart
            'cart' => 'cart.index',
            'keranjang' => 'cart.index',
            'troli' => 'cart.index',
            
            // Wishlist
            'wishlist' => 'wishlist.index',
            'favorit' => 'wishlist.index',
        ];

        // Check for exact keyword match or if query contains the keyword
        foreach ($keywords as $keyword => $route) {
            if (Str::contains($lowerQuery, $keyword)) {
                return redirect()->route($route);
            }
        }

        // Fallback: Product Search
        return redirect()->route('products.index', ['q' => $query]);
    }
}
