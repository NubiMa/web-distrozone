<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Get financial report for a specific period
     * 
     * @param string|null $startDate
     * @param string|null $endDate
     * @param int|null $cashierId Filter by cashier (optional)
     * @return array
     */
    public function getFinancialReport(?string $startDate = null, ?string $endDate = null, ?int $cashierId = null): array
    {
        // Default to current month if no dates provided
        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $end = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        // Base query
        $query = Transaction::whereBetween('created_at', [$start, $end])
            ->where('payment_status', 'verified');

        // Filter by cashier if provided
        if ($cashierId) {
            $query->where('cashier_id', $cashierId);
        }

        $transactions = $query->with('details')->get();

        // Calculate totals
        $totalRevenue = $transactions->sum('total');
        $totalCost = $transactions->sum(function ($transaction) {
            return $transaction->details->sum(function ($detail) {
                return $detail->cost_price * $detail->quantity;
            });
        });
        $totalProfit = $totalRevenue - $totalCost;
        $totalTransactions = $transactions->count();

        // Calculate by transaction type
        $offlineRevenue = $transactions->where('transaction_type', 'offline')->sum('total');
        $onlineRevenue = $transactions->where('transaction_type', 'online')->sum('total');

        // Calculate by payment method
        $paymentMethods = $transactions->groupBy('payment_method')->map(function ($group) {
            return [
                'count' => $group->count(),
                'total' => $group->sum('total'),
            ];
        });

        return [
            'period' => [
                'start_date' => $start->format('Y-m-d'),
                'end_date' => $end->format('Y-m-d'),
            ],
            'summary' => [
                'total_transactions' => $totalTransactions,
                'total_revenue' => $totalRevenue,
                'total_cost' => $totalCost,
                'total_profit' => $totalProfit,
                'profit_margin' => $totalRevenue > 0 ? ($totalProfit / $totalRevenue) * 100 : 0,
            ],
            'by_type' => [
                'offline' => [
                    'count' => $transactions->where('transaction_type', 'offline')->count(),
                    'revenue' => $offlineRevenue,
                ],
                'online' => [
                    'count' => $transactions->where('transaction_type', 'online')->count(),
                    'revenue' => $onlineRevenue,
                ],
            ],
            'by_payment_method' => $paymentMethods,
        ];
    }

    /**
     * Get daily sales summary for a period
     * 
     * @param string|null $startDate
     * @param string|null $endDate
     * @param int|null $cashierId
     * @return array
     */
    public function getDailySales(?string $startDate = null, ?string $endDate = null, ?int $cashierId = null): array
    {
        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $end = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        $query = Transaction::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as transaction_count'),
            DB::raw('SUM(total) as total_revenue')
        )
            ->whereBetween('created_at', [$start, $end])
            ->where('payment_status', 'verified')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'asc');

        if ($cashierId) {
            $query->where('cashier_id', $cashierId);
        }

        return $query->get()->toArray();
    }

    /**
     * Get top selling products
     * 
     * @param string|null $startDate
     * @param string|null $endDate
     * @param int $limit
     * @return array
     */
    public function getTopSellingProducts(?string $startDate = null, ?string $endDate = null, int $limit = 10): array
    {
        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $end = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        $topProducts = TransactionDetail::select(
            'products.id',
            'products.product_code',
            'products.brand',
            'products.color',
            'products.size',
            DB::raw('SUM(transaction_details.quantity) as total_sold'),
            DB::raw('SUM(transaction_details.subtotal) as total_revenue')
        )
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->whereBetween('transactions.created_at', [$start, $end])
            ->where('transactions.payment_status', 'verified')
            ->groupBy('products.id', 'products.product_code', 'products.brand', 'products.color', 'products.size')
            ->orderBy('total_sold', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();

        return $topProducts;
    }

    /**
     * Get cashier performance report
     * 
     * @param string|null $startDate
     * @param string|null $endDate
     * @return array
     */
    public function getCashierPerformance(?string $startDate = null, ?string $endDate = null): array
    {
        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $end = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        $cashierStats = Transaction::select(
            'users.id',
            'users.name',
            DB::raw('COUNT(transactions.id) as transaction_count'),
            DB::raw('SUM(transactions.total) as total_revenue')
        )
            ->join('users', 'transactions.cashier_id', '=', 'users.id')
            ->whereBetween('transactions.created_at', [$start, $end])
            ->where('transactions.payment_status', 'verified')
            ->groupBy('users.id', 'users.name')
            ->orderBy('total_revenue', 'desc')
            ->get()
            ->toArray();

        return $cashierStats;
    }
}
