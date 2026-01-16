<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShippingRate;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Services\ShippingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WebCheckoutController extends Controller
{
    protected $shippingService;

    public function __construct(ShippingService $shippingService)
    {
        $this->shippingService = $shippingService;
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        if (count($cart) == 0) {
            return redirect()->route('cart.index');
        }

        $destinations = ShippingRate::all();
        $total = 0;
        $totalQuantity = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
            $totalQuantity += $item['quantity'];
        }

        return view('cart.checkout', compact('cart', 'total', 'destinations', 'totalQuantity'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_rate_id' => 'required|exists:shipping_rates,id',
            'address' => 'required|string',
            'phone' => 'required|string',
            'payment_method' => 'required|string',
            'payment_proof' => 'required|image|max:2048', 
        ]);

        $cart = session()->get('cart', []);
        if (count($cart) == 0) {
            return redirect()->route('cart.index');
        }

        DB::beginTransaction();
        try {
            // Calculate Total and Weight
            $subtotal = 0;
            $totalQuantity = 0;
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
                $totalQuantity += $item['quantity'];
            }

            // Calculate Shipping
            $destination = ShippingRate::find($request->shipping_rate_id);
            $weightInKg = $this->shippingService->calculateWeight($totalQuantity);
            $shippingCost = $this->shippingService->calculateCost($destination->rate_per_kg, $weightInKg);
            
            $grandTotal = $subtotal + $shippingCost;

            // Upload Proof
            $proofPath = $request->file('payment_proof')->store('payment_proofs', 'public');

            // Create Transaction
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'transaction_date' => now(),
                'total_amount' => $grandTotal,
                'status' => 'pending', // Waiting for verification
                'payment_method' => $request->payment_method,
                'shipping_address' => $request->address,
                'shipping_cost' => $shippingCost,
                'payment_proof' => $proofPath
            ]);

            // Create Details & Decrease Stock
            foreach ($cart as $id => $item) {
               $product = \App\Models\Product::find($id);
               
               // Check stock
               if ($product->stock < $item['quantity']) {
                   throw new \Exception("Insufficient stock for " . $product->name);
               }

               TransactionDetail::create([
                   'transaction_id' => $transaction->id,
                   'product_id' => $id,
                   'quantity' => $item['quantity'],
                   'price' => $item['price'],
                   'subtotal' => $item['price'] * $item['quantity']
               ]);

               // Decrease Stock
               $product->decrement('stock', $item['quantity']);
            }

            DB::commit();
            session()->forget('cart');

            return redirect()->route('dashboard')->with('success', 'Order placed successfully! Waiting for verification.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Order failed: ' . $e->getMessage()]);
        }
    }
}
