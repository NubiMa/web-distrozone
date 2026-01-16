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
