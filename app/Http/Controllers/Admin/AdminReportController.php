<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Display admin financial reports (all cashiers)
     */
    public function index(Request $request)
    {
        // Default timeframe (this month)
        $startDate = now()->startOfMonth()->format('Y-m-d');
        $endDate = now()->endOfMonth()->format('Y-m-d');
        
        // Handle Preset Filters
        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'today':
                    $startDate = now()->format('Y-m-d');
                    $endDate = now()->format('Y-m-d');
                    break;
                case 'week':
                    $startDate = now()->startOfWeek()->format('Y-m-d');
                    $endDate = now()->endOfWeek()->format('Y-m-d');
                    break;
                case 'month':
                    $startDate = now()->startOfMonth()->format('Y-m-d');
                    $endDate = now()->endOfMonth()->format('Y-m-d');
                    break;
            }
        } elseif ($request->has('start_date') && $request->has('end_date')) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
        }

        // Check for cashier and payment method filter
        $cashierId = $request->get('cashier_id');
        $paymentMethod = $request->get('payment_method');

        // Get financial report for ALL or specific cashier
        $report = $this->reportService->getFinancialReport($startDate, $endDate, $cashierId, $paymentMethod);
        
        // Get daily sales for charts
        $dailySales = $this->reportService->getDailySales($startDate, $endDate, $cashierId, $paymentMethod);

        // Get recent transactions
        $query = \App\Models\Transaction::with(['details.productVariant.product', 'user', 'cashier.employee'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        // Search Filter
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_code', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('cashier', function($q3) use ($search) {
                      $q3->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Apply cashier filter to transaction list as well
        if ($cashierId) {
            $query->where('cashier_id', $cashierId);
        }

        // Apply payment method filter
        if ($paymentMethod) {
            if ($paymentMethod === 'ewallet') {
                $query->where('payment_method', 'like', '%Dana%');
            } elseif ($paymentMethod === 'transfer') {
                $query->where(function($q) {
                    $q->where('payment_method', 'like', '%transfer%')
                      ->orWhere('payment_method', 'like', '%Virtual Account%')
                      ->orWhere('payment_method', 'like', '%Bank%');
                })->where('payment_method', 'not like', '%Dana%');
            } elseif ($paymentMethod === 'qris') {
                $query->where('payment_method', 'like', '%Qris%');
            } elseif ($paymentMethod === 'tunai') {
                $query->where('payment_method', 'like', '%tunai%');
            } else {
                $query->where('payment_method', $paymentMethod);
            }
        }

        $transactions = $query->latest()->paginate(15);

        // Get cashier performance
        $cashierPerformance = $this->reportService->getCashierPerformance($startDate, $endDate);

        // Calculate additional metrics (using filtered dates for consistency, or today as requested? Summary usually follows filter)
        // User asked for "Pendapatan... separate into today/week/month". 
        // Logic: specific "todayRevenue" var might be redundant if we use the Filtered Revenue.
        // I will keep "todayRevenue" as "Revenue for the selected period" to be consistent with the new UI.
        
        $avgOrderValue = $report['summary']['total_revenue'] > 0 && $report['summary']['total_transactions'] > 0
            ? $report['summary']['total_revenue'] / $report['summary']['total_transactions']
            : 0;

        // AJAX Response
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.reports.partials.transaction_table', compact('transactions'))->render(),
                'summary' => [
                    'total_transactions' => number_format($report['summary']['total_transactions']),
                    'total_profit' => number_format($report['summary']['total_profit'] ?? 0, 0, ',', '.'),
                    'profit_margin' => number_format($report['summary']['profit_margin'] ?? 0, 1),
                    'total_revenue' => number_format($report['summary']['total_revenue'], 0, ',', '.'),
                ],
                'pagination' => (string) $transactions->links()
            ]);
        }

        $cashiers = \App\Models\User::where('role', 'kasir')->get();

        return view('admin.reports', compact(
            'report',
            'dailySales',
            'transactions',
            'cashierPerformance',
            'startDate',
            'endDate',
            'avgOrderValue',
            'cashiers'
        ));
    }

    /**
     * Get daily sales report
     */
    public function dailySales(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $sales = $this->reportService->getDailySales(
            $validated['start_date'] ?? null,
            $validated['end_date'] ?? null
        );

        return response()->json([
            'success' => true,
            'data' => $sales,
        ]);
    }

    /**
     * Get top selling products
     */
    public function topProducts(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'limit' => 'nullable|integer|min:1|max:50',
        ]);

        $topProducts = $this->reportService->getTopSellingProducts(
            $validated['start_date'] ?? null,
            $validated['end_date'] ?? null,
            $validated['limit'] ?? 10
        );

        return response()->json([
            'success' => true,
            'data' => $topProducts,
        ]);
    }

    /**
     * Get cashier performance report
     */
    public function cashierPerformance(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $performance = $this->reportService->getCashierPerformance(
            $validated['start_date'] ?? null,
            $validated['end_date'] ?? null
        );

        return response()->json([
            'success' => true,
            'data' => $performance,
        ]);
    }
}
