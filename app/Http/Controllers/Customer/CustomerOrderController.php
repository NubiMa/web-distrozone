<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use App\Services\ShippingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CustomerOrderController extends Controller
{
    protected $shippingService;

    public function __construct(ShippingService $shippingService)
    {
        $this->shippingService = $shippingService;
    }

    /**
     * Display a listing of customer's orders
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['details.productVariant.product'])
            ->where('user_id', auth()->id())
            ->latest();

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('order_status', $request->status);
        }

        $orders = $query->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Create a new online order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:transfer',
            'shipping_destination' => 'required|string',
            'shipping_address' => 'required|string',
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:20',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Calculate subtotal and validate stock
            $subtotal = 0;
            $totalQuantity = 0;
            $items = [];

            foreach ($validated['items'] as $item) {
                $product = Product::active()->inStock()->findOrFail($item['product_id']);

                // Check stock
                if ($product->stock < $item['quantity']) {
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient stock for product: {$product->brand} - {$product->color} ({$product->size})",
                    ], 400);
                }

                $itemSubtotal = $product->selling_price * $item['quantity'];
                $subtotal += $itemSubtotal;
                $totalQuantity += $item['quantity'];

                $items[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $product->selling_price,
                    'cost_price' => $product->cost_price,
                ];
            }

            // Calculate shipping cost (NO FREE SHIPPING - always charged)
            $shippingCalculation = $this->shippingService->calculateShippingCost(
                $validated['shipping_destination'],
                $totalQuantity
            );

            if (!$shippingCalculation['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Shipping destination not available: ' . $validated['shipping_destination'],
                ], 400);
            }

            $shippingCost = $shippingCalculation['shipping_cost'];
            $weightKg = $shippingCalculation['weight_kg'];
            $total = $subtotal + $shippingCost;

            // Upload payment proof
            $paymentProofPath = $request->file('payment_proof')->store('payment-proofs', 'public');

            // Create online transaction
            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'transaction_type' => 'online',
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending', // Requires kasir verification
                'payment_proof' => $paymentProofPath,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost, // Always charged, no free shipping
                'total' => $total,
                'shipping_destination' => $validated['shipping_destination'],
                'shipping_address' => $validated['shipping_address'],
                'recipient_name' => $validated['recipient_name'],
                'recipient_phone' => $validated['recipient_phone'],
                'weight_kg' => $weightKg,
                'order_status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            // Create transaction details and reserve stock
            foreach ($items as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'cost_price' => $item['cost_price'],
                ]);

                // Decrease stock immediately (reserved)
                $item['product']->decreaseStock($item['quantity']);
            }

            DB::commit();

            $transaction->load('details.productVariant.product');

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully. Waiting for payment verification.',
                'data' => $transaction,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            // Delete uploaded payment proof if transaction fails
            if (isset($paymentProofPath)) {
                Storage::disk('public')->delete($paymentProofPath);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to place order: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified order
     */
    public function show($id)
    {
        $order = Transaction::with(['details.productVariant.product', 'cashier', 'verifier'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('customer.orders.show', compact('order'));
    }

    /**
     * Calculate shipping cost
     */
    public function calculateShipping(Request $request)
    {
        $validated = $request->validate([
            'destination' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $calculation = $this->shippingService->calculateShippingCost(
            $validated['destination'],
            $validated['quantity']
        );

        return response()->json($calculation);
    }

    /**
     * Get available shipping destinations
     */
    public function shippingDestinations()
    {
        $destinations = $this->shippingService->getAvailableDestinations();

        return response()->json([
            'success' => true,
            'data' => $destinations,
        ]);
    }
}
