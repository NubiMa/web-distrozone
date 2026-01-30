<?php


namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;

class KasirReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Display reports dashboard
     */
    public function index(Request $request)
    {
        $cashierId = auth()->id();

        // Handle filter presets (today, week, month)
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
                default:
                    $startDate = now()->startOfMonth()->format('Y-m-d');
                    $endDate = now()->endOfMonth()->format('Y-m-d');
                    break;
            }
        } else {
            $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->input('end_date', now()->format('Y-m-d'));
        }

        // 1. Filtered Report (Base)
        $report = $this->reportService->getFinancialReport($startDate, $endDate, $cashierId);
        
        // 2. Daily Sales for Charts (Filtered)
        $dailySales = $this->reportService->getDailySales($startDate, $endDate, $cashierId);

        // 3. Recent Transactions (Filtered)
        $query = \App\Models\Transaction::with(['details.productVariant.product', 'user'])
            ->where('cashier_id', $cashierId)
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('transaction_code', 'LIKE', "%{$search}%");
        }

        // Payment method filter
        if ($request->has('payment_method') && $request->payment_method != '') {
            $query->where('payment_method', $request->payment_method);
        }

        $transactions = $query->latest()->paginate(8);

        // 4. Metric: Sales Today (Strictly Today)
        $todayStart = now()->startOfDay();
        $todayEnd = now()->endOfDay();
        $todaySales = \App\Models\Transaction::verified()
            ->where('cashier_id', $cashierId)
            ->whereBetween('verified_at', [$todayStart, $todayEnd])
            ->sum('total');

        // 5. Metric: Avg Order Value (Filtered)
        $totalTx = $report['summary']['total_transactions'];
        $totalRev = $report['summary']['total_revenue'];
        $avgOrderValue = $totalTx > 0 ? $totalRev / $totalTx : 0;

        // 6. Metric: Cash in Drawer (Filtered - 'tunai' only)
        $cashSales = \App\Models\Transaction::verified()
            ->where('cashier_id', $cashierId)
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('payment_method', 'tunai')
            ->sum('total');

        // Handle AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'html' => view('kasir.reports.partials.transaction_table', compact('transactions'))->render(),
                'summary' => [
                    'total_transactions' => number_format($report['summary']['total_transactions']),
                    'total_revenue' => number_format($totalRev, 0, ',', '.'),
                    'todaySales' => number_format($todaySales, 0, ',', '.'),
                    'avgOrderValue' => number_format($avgOrderValue, 0, ',', '.'),
                    'cashSales' => number_format($cashSales, 0, ',', '.'),
                ],
            ]);
        }

        return view('kasir.reports', compact(
            'report', 
            'dailySales', 
            'transactions', 
            'startDate', 
            'endDate',
            'todaySales',
            'avgOrderValue',
            'cashSales'
        ));
    }

    /**
     * Get financial report for current kasir only
     */
    public function financial(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Filter by current kasir's transactions only
        $report = $this->reportService->getFinancialReport(
            $validated['start_date'] ?? null,
            $validated['end_date'] ?? null,
            auth()->id() // Only this kasir's transactions
        );

        return response()->json([
            'success' => true,
            'data' => $report,
        ]);
    }

    /**
     * Get daily sales for current kasir
     */
    public function dailySales(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $sales = $this->reportService->getDailySales(
            $validated['start_date'] ?? null,
            $validated['end_date'] ?? null,
            auth()->id() // Only this kasir's transactions
        );

        return response()->json([
            'success' => true,
            'data' => $sales,
        ]);
    }
}
