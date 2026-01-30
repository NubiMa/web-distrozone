<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Determine period for chart data
        $period = $request->input('period', 'this_week');
        
        // Calculate date range based on period
        if ($period === 'last_week') {
            $startDate = now()->subWeek()->startOfWeek();
            $endDate = now()->subWeek()->endOfWeek();
        } else { // this_week
            $startDate = now()->startOfWeek();
            $endDate = now()->endOfWeek();
        }

        // Summary stats - with safe defaults
        $totalRevenue = Transaction::where('payment_status', 'verified')->sum('total') ?? 0;
        $totalTransactions = Transaction::where('payment_status', 'verified')->count() ?? 0;
        $totalProducts = Product::where('is_active', true)->count() ?? 0;
        $totalStaff = User::where('role', 'kasir')->where('is_active', true)->count() ?? 0;
        
        // Low Stock Alerts (variants with stock < 5)
        $lowStockCount = \App\Models\ProductVariant::where('stock', '<', 5)->count();

        // Recent Activity (Transactions)
        $recentActivities = Transaction::with(['user', 'cashier'])
            ->latest()
            ->limit(5)
            ->get();

        // Weekly Sales Chart Data (based on period filter)
        $weeklySales = Transaction::where('payment_status', 'verified')
            ->whereBetween('verified_at', [$startDate, $endDate])
            ->selectRaw('DATE(verified_at) as date, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // If AJAX request, return JSON
        if ($request->ajax()) {
            $labels = $weeklySales->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('D'));
            $data = $weeklySales->pluck('total');
            
            return response()->json([
                'labels' => $labels,
                'data' => $data,
            ]);
        }

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalTransactions',
            'totalProducts',
            'totalStaff',
            'lowStockCount',
            'recentActivities',
            'weeklySales'
        ));
    }
}
