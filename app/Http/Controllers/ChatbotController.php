<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StoreSetting;

class ChatbotController extends Controller
{
    public function handle(Request $request)
    {
        $message = strtolower($request->input('message'));
        $isEnglish = preg_match('/\b(can|english|speak|help|hello|hi|good|thank|you|bye|better|which)\b/', $message);
        
        // 0. LANGUAGE SWITCHING (Explicit)
        if (str_contains($message, 'ganti') && (str_contains($message, 'indo') || str_contains($message, 'bahasa'))) {
            return response()->json([
                'text' => "Siap! Mode Bahasa Indonesia diaktifkan. 🇮🇩\n\nAda yang bisa saya bantu? Tanya tentang produk atau pengiriman ya.",
                'type' => 'text'
            ]);
        }
        
        // 1. CASUAL CONVERSATION (SMALL TALK)
        if (str_contains($message, 'robot') || str_contains($message, 'real') || str_contains($message, 'manusia')) {
             return response()->json([
                'text' => $isEnglish 
                    ? "🤖 Beep boop! I am a Virtual Assistant created for DistroZone. I'm here to help you shop!" 
                    : "🤖 Beep boop! Saya Asisten Virtual untuk DistroZone. Saya di sini untuk membantu Anda belanja!",
                'type' => 'text'
            ]);
        }


        // 3. BRAND LIST INQUIRY (Priority over comparison)
        if (str_contains($message, 'brand') || str_contains($message, 'merek')) {
            $brands = Product::where('is_active', true)->select('brand')->distinct()->pluck('brand');
            
            if ($brands->isNotEmpty()) {
                $brandList = $brands->map(fn($b) => "- $b")->implode("\n");
                return response()->json([
                    'text' => $isEnglish
                        ? "🏷️ **Available Brands:**\nWe have official collections from:\n\n$brandList\n\nType a brand name to see products."
                        : "🏷️ **Brand Tersedia:**\nKami memiliki koleksi resmi dari:\n\n$brandList\n\nKetik nama brand untuk melihat produknya.",
                    'type' => 'text'
                ]);
            }
        }

        // 4. COMPARISON / OPINION (e.g., "which is better", "mending mana")
        if (str_contains($message, 'better') || str_contains($message, 'bagus') || str_contains($message, 'mending') || str_contains($message, 'pilih') || str_contains($message, 'prefer')) {
              return response()->json([
                'text' => $isEnglish 
                    ? "Both are great choices! 🤩 It depends on your style. Check our 'Best Sellers' to see what others are buying!" 
                    : "Wah, pilihan sulit! 🤩 Keduanya keren kok. Tergantung selera dan kebutuhanmu hari ini. Cek rekomendasi kami ya!",
                'type' => 'text'
            ]);
        }

        if (str_contains($message, 'english') || str_contains($message, 'bahasa')) {
             return response()->json([
                'text' => "Yes, I can speak English! 🇬🇧 Just ask me about products, shipping, or store info.\n\nYa, saya bisa bahasa Indonesia! 🇮🇩 Tanya saja tentang produk atau pengiriman.",
                'type' => 'text'
            ]);
        }

        if (str_contains($message, 'thanks') || str_contains($message, 'thank') || str_contains($message, 'makasih') || str_contains($message, 'terima kasih')) {
             return response()->json([
                'text' => $isEnglish 
                    ? "You're welcome! Happy shopping! 🛍️" 
                    : "Sama-sama! Selamat berbelanja! 🛍️",
                'type' => 'text'
            ]);
        }

        // 2. GREETINGS (Strict Word Boundary \b to avoid matching 'which' as 'hi')
        if (preg_match('/\b(hi|hello|hey|halo|hai|pagi|siang|sore|malam)\b/', $message)) {
            return response()->json([
                'text' => $isEnglish 
                    ? "Hello! 👋 Welcome to DistroZone.\n\nI'm your virtual assistant. Ask me anything like:\n- 'Best sellers'\n- 'Do you have Nike?'\n- 'Shipping info'"
                    : "Halo! 👋 Selamat datang di DistroZone.\n\nSaya asisten virtual toko ini. Coba tanya:\n- 'Rekomendasi best seller'\n- 'Ada hoodie Nike?'\n- 'Info pengiriman'",
                'type' => 'text'
            ]);
        }

        // 3. STORE INFO & HISTORY
        if (str_contains($message, 'sejarah') || str_contains($message, 'tentang') || str_contains($message, 'profil') || str_contains($message, 'history') || str_contains($message, 'about')) {
            return response()->json([
                'text' => $isEnglish
                    ? "🏢 **About DistroZone**\n\nFounded in 2023 in Los Angeles, we are dedicated to providing premium streetwear at affordable prices."
                    : "🏢 **Tentang DistroZone**\n\nDistroZone didirikan pada tahun 2023 di Los Angeles. Kami berdedikasi untuk menyediakan streetwear premium dengan harga terjangkau.",
                'type' => 'text'
            ]);
        }

        // 4. SHIPPING (Original logic + English)
        if (str_contains($message, 'kirim') || str_contains($message, 'antar') || str_contains($message, 'ongkir') || str_contains($message, 'shipping') || str_contains($message, 'delivery')) {
             return response()->json([
                'text' => $isEnglish
                    ? "🚚 **Shipping Info**\n\n1. We ship nationwide.\n2. Costs are calculated at checkout.\n3. Couriers: JNE, J&T, Sicepat.\n4. Orders ship H+1."
                    : "🚚 **Info Pengiriman & Ongkir**\n\n1. Kami mengirim ke seluruh Indonesia.\n2. Ongkir dihitung otomatis saat checkout.\n3. Kurir tersedia: JNE, J&T, Sicepat.\n4. Pesanan dikirim H+1.",
                'type' => 'text'
            ]);
        }



        // 6. RECOMMENDATIONS
        if (str_contains($message, 'rekomendasi') || str_contains($message, 'best') || str_contains($message, 'laku') || str_contains($message, 'recommend')) {
            $products = Product::where('is_active', true)->inRandomOrder()->limit(5)->get();
            $cards = $this->formatProductCards($products);

            return response()->json([
                'text' => $isEnglish 
                    ? "🔥 **Top Recommendations:**\nHere are our hottest items right now:"
                    : "🔥 **Rekomendasi Terbaik:**\nBerikut adalah produk-produk paling hits di toko kami saat ini:",
                'type' => 'products',
                'data' => $cards
            ]);
        }

        // 7. PRICE INTELLIGENCE (Cheapest/Expensive)
        if (str_contains($message, 'murah') || str_contains($message, 'mahal') || str_contains($message, 'cheapest') || str_contains($message, 'expensive')) {
            $isCheapest = str_contains($message, 'murah') || str_contains($message, 'cheapest');
            $direction = $isCheapest ? 'asc' : 'desc';
            
            // Extract keyword (remove 'paling', 'yang', 'harga', etc)
            $searchKeyword = str_replace(['paling', 'ter', 'yang', 'harga', 'price', 'murah', 'mahal', 'cheapest', 'expensive', 'berapa', 'kaos' => 't-shirt'], '', $message);
            $searchKeyword = trim($searchKeyword);

            $query = Product::where('is_active', true);
            
            // If there is a specific keyword (e.g. "kaos"), filter by it
            if (!empty($searchKeyword) && strlen($searchKeyword) > 2) {
                 $query->where('name', 'like', "%$searchKeyword%");
            }

            $product = $query->orderBy('base_price', $direction)->first();

            if ($product) {
                 $price = 'Rp ' . number_format($product->base_price, 0, ',', '.');
                 $label = $isCheapest ? ($isEnglish ? "cheapest" : "termurah") : ($isEnglish ? "most expensive" : "termahal");
                 
                 return response()->json([
                    'text' => $isEnglish
                        ? "💰 The $label item we have is **$product->name** at **$price**."
                        : "💰 Produk $label kami adalah **$product->name** seharga **$price**.",
                    'type' => 'products',
                    'data' => $this->formatProductCards(collect([$product]))
                ]);
            }
        }

        // 8. IMPLICIT SEARCH
        $keywords = str_replace(
            ['cari', 'jual', 'punya', 'ada', 'apa', 'saja', 'yang', 'warna', 'ukuran', 'stok', 'harga', 'berapa', 'product', 'produk', 'tersedia', 'search', 'buy', 'have', 'available', 'price', 'can', 'speak', 'indonesia', 'english', 'better', 'bagus'], 
            '', 
            $message
        );
        $keywords = trim($keywords);

        if (!empty($keywords) && strlen($keywords) > 2) {
            $products = Product::search($keywords)->where('is_active', true)->limit(5)->get();
            
            if ($products->count() > 0) {
                return response()->json([
                    'text' => $isEnglish
                        ? "🔍 Found " . $products->count() . " products for '$keywords':"
                        : "🔍 Menemukan " . $products->count() . " produk untuk '$keywords':",
                    'type' => 'products',
                    'data' => $this->formatProductCards($products)
                ]);
            }
        }

        // 8. FALLBACK (Final)
        // More guiding response, not just echoing part of the input
        return response()->json([
            'text' => $isEnglish
                ? "Hmm, I'm not sure specifically what you mean. 🤔\n\nTry using simpler keywords like:\n- 'Nike hoodie'\n- 'Shipping cost'\n- 'Store location'"
                : "Hmm, saya kurang mengerti spesifiknya. 🤔\n\nCoba gunakan kata kunci yang lebih jelas, misalnya:\n- 'Hoodie hitam'\n- 'Ongkir ke Jakarta'\n- 'Cara pesan'",
            'type' => 'text'
        ]);
    }

    private function formatProductCards($products) {
        return $products->map(function($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'price' => 'Rp ' . number_format($p->base_price, 0, ',', '.'), 
                'image' => $p->photo_url,
                'url' => route('products.show', $p->slug)
            ];
        });
    }
}
