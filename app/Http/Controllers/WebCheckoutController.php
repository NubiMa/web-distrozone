<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Address;
use App\Models\ShippingRate;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\StoreSetting;
use App\Services\ShippingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WebCheckoutController extends Controller
{
    protected $shippingService;

    public function __construct(ShippingService $shippingService)
    {
        $this->shippingService = $shippingService;
    }

    public function index()
    {
        $user = Auth::user();
        
        // Get cart items
        $cartItems = CartItem::where('user_id', $user->id)
            ->with('product')
            ->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong.');
        }

        // Get all user addresses
        $addresses = Address::where('user_id', $user->id)->get();
        $primaryAddress = $addresses->where('is_primary', true)->first();
        
        if (!$primaryAddress && $addresses->isNotEmpty()) {
            $primaryAddress = $addresses->first();
        } elseif ($addresses->isEmpty()) {
            return redirect()->route('address.index')->with('error', 'Silakan tambahkan alamat pengiriman terlebih dahulu.');
        }

        // Get shipping rates and store settings
        $shippingRates = ShippingRate::all();
        $storeSettings = StoreSetting::whereIn('key', ['qris_image', 'bank_name', 'bank_account_number', 'bank_account_holder'])
            ->pluck('value', 'key');

        // Calculate totals
        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->selling_price * $item->quantity;
        });
        $totalQuantity = $cartItems->sum('quantity');
        
        // Calculate weight (1kg = max 3 shirts, per brief)
        $weightKg = ceil($totalQuantity / 3);

        return view('checkout.index', compact('cartItems', 'addresses', 'primaryAddress', 'shippingRates', 'subtotal', 'totalQuantity', 'weightKg', 'storeSettings'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'shipping_destination' => 'required|string',
            'payment_method' => 'required|in:transfer,qris',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        // Get cart items
        $cartItems = CartItem::where('user_id', $user->id)
            ->with('product')
            ->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        // Get address
        $address = Address::findOrFail($request->address_id);

        DB::beginTransaction();
        try {
            // Calculate totals
            $subtotal = 0;
            $totalQuantity = 0;
            
            foreach ($cartItems as $item) {
                $subtotal += $item->product->selling_price * $item->quantity;
                $totalQuantity += $item->quantity;
            }

            // Weight calculation (brief: 1kg = max 3 shirts)
            $weightKg = ceil($totalQuantity / 3);

            // Get shipping rate
            $shippingRate = ShippingRate::where('destination', $request->shipping_destination)->first();
            if (!$shippingRate) {
                throw new \Exception('Tarif pengiriman tidak ditemukan untuk tujuan ini.');
            }
            
            $shippingCost = $shippingRate->rate_per_kg * $weightKg;
            $total = $subtotal + $shippingCost;

            // Upload payment proof
            $proofPath = null;
            if ($request->hasFile('payment_proof')) {
                $proofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
            }

            // Generate transaction code
            $transactionCode = 'DZ-' . strtoupper(Str::random(6)) . '-' . date('ymd');

            // Create transaction
            $transaction = Transaction::create([
                'transaction_code' => $transactionCode,
                'user_id' => $user->id,
                'transaction_type' => 'online',
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'payment_proof' => $proofPath,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'shipping_destination' => $request->shipping_destination,
                'shipping_address' => $address->address . ', ' . $address->city . ', ' . $address->postal_code,
                'recipient_name' => $address->recipient_name,
                'recipient_phone' => $address->phone,
                'weight_kg' => $weightKg,
                'order_status' => 'pending',
            ]);

            // Create transaction details and update stock
            foreach ($cartItems as $item) {
                // Check stock
                if ($item->product->stock < $item->quantity) {
                    throw new \Exception("Stok tidak mencukupi untuk produk: " . $item->product->brand);
                }

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'cost_price' => $item->product->cost_price,
                    'price' => $item->product->selling_price,
                    'subtotal' => $item->product->selling_price * $item->quantity,
                ]);

                // Decrease stock
                $item->product->decrement('stock', $item->quantity);
            }

            // Clear cart
            CartItem::where('user_id', $user->id)->delete();

            DB::commit();

            return redirect()->route('checkout.success', $transaction->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memproses pesanan: ' . $e->getMessage()])->withInput();
        }
    }

    public function success($transactionId)
    {
        $transaction = Transaction::with('details.product')
            ->where('user_id', Auth::id())
            ->findOrFail($transactionId);

        return view('checkout.success', compact('transaction'));
    }
}
