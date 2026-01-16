<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasirTransactionController extends Controller
{
    /**
     * Display a listing of transactions for current kasir
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['details.product', 'user'])
            ->where('cashier_id', auth()->id())
            ->latest();

        // Filter by transaction type
        if ($request->has('type')) {
            $query->where('transaction_type', $request->type);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->dateRange($request->start_date, $request->end_date);
        }

        $transactions = $query->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $transactions,
        ]);
    }

    /**
     * Store a new offline transaction (POS)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:tunai,qris,transfer',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Calculate totals
            $subtotal = 0;
            $items = [];

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                // Check stock
                if ($product->stock < $item['quantity']) {
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient stock for product: {$product->brand} - {$product->color} ({$product->size})",
                    ], 400);
                }

                $itemSubtotal = $product->selling_price * $item['quantity'];
                $subtotal += $itemSubtotal;

                $items[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $product->selling_price,
                    'cost_price' => $product->cost_price,
                    'subtotal' => $itemSubtotal,
                ];
            }

            // Create transaction (offline, no shipping)
            $transaction = Transaction::create([
                'cashier_id' => auth()->id(),
                'transaction_type' => 'offline',
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'verified', // Offline transactions are instantly verified
                'subtotal' => $subtotal,
                'shipping_cost' => 0,
                'total' => $subtotal,
                'order_status' => 'completed',
                'notes' => $validated['notes'] ?? null,
                'verified_at' => now(),
                'verified_by' => auth()->id(),
            ]);

            // Create transaction details and update stock
            foreach ($items as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'cost_price' => $item['cost_price'],
                ]);

                // Decrease stock
                $item['product']->decreaseStock($item['quantity']);
            }

            DB::commit();

            $transaction->load('details.product');

            return response()->json([
                'success' => true,
                'message' => 'Transaction created successfully',
                'data' => $transaction,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create transaction: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified transaction
     */
    public function show($id)
    {
        $transaction = Transaction::with(['details.product', 'user'])
            ->where('cashier_id', auth()->id())
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $transaction,
        ]);
    }

    /**
     * Verify online order payment
     */
    public function verifyPayment(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        if ($transaction->transaction_type !== 'online') {
            return response()->json([
                'success' => false,
                'message' => 'Only online transactions can be verified',
            ], 400);
        }

        if ($transaction->payment_status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Transaction has already been processed',
            ], 400);
        }

        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            if ($validated['action'] === 'approve') {
                $transaction->markAsVerified(auth()->id());
                $transaction->updateOrderStatus('processing');
                if ($validated['notes'] ?? null) {
                    $transaction->notes = $validated['notes'];
                    $transaction->save();
                }
                $message = 'Payment verified successfully';
            } else {
                $transaction->markAsRejected();
                if ($validated['notes'] ?? null) {
                    $transaction->notes = $validated['notes'];
                    $transaction->save();
                }

                // Restore stock
                foreach ($transaction->details as $detail) {
                    $detail->product->increaseStock($detail->quantity);
                }

                $message = 'Payment rejected';
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $transaction->fresh(),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to process payment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get pending online orders for verification
     */
    public function pendingOrders()
    {
        $orders = Transaction::with(['details.product', 'user'])
            ->online()
            ->pending()
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }
}
