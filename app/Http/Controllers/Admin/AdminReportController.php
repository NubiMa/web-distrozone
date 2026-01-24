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
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // Get financial report for ALL cashiers (no cashier_id filter)
        $report = $this->reportService->getFinancialReport($startDate, $endDate, null);
        
        // Get daily sales for charts
        $dailySales = $this->reportService->getDailySales($startDate, $endDate, null);

        // Get recent transactions (all cashiers)
        $transactions = \App\Models\Transaction::with(['details.productVariant.product', 'user', 'cashier'])
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->latest()
            ->paginate(15);

        // Get cashier performance
        $cashierPerformance = $this->reportService->getCashierPerformance($startDate, $endDate);

        // Calculate additional metrics
        $todayRevenue = \App\Models\Transaction::verified()
            ->whereDate('verified_at', today())
            ->sum('total');
        
        $avgOrderValue = $report['summary']['total_revenue'] > 0 && $report['summary']['total_transactions'] > 0
            ? $report['summary']['total_revenue'] / $report['summary']['total_transactions']
            : 0;

        // Get all cashiers for filter
        $cashiers = \App\Models\User::where('role', 'kasir')->get();

        return view('admin.reports', compact(
            'report',
            'dailySales',
            'transactions',
            'cashierPerformance',
            'startDate',
            'endDate',
            'todayRevenue',
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
