<x-admin-layout>
    <x-slot name="head">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Dashboard Overview</h1>
                <p class="text-gray-500 mt-1">Welcome back, Admin. Here's what's happening today.</p>
            </div>
            <div class="flex gap-3 mt-4 md:mt-0">
                <button
                    class="px-4 py-2.5 bg-white border border-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export
                </button>
                <a href="{{ route('admin.products.create') }}"
                    class="px-4 py-2.5 bg-orange-600 text-white font-bold rounded-xl hover:bg-orange-700 transition-colors flex items-center gap-2 shadow-lg shadow-orange-600/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Product
                </a>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Sales -->
            <div
                class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group hover:border-orange-200 transition-all">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div
                        class="flex items-center gap-1 text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded-lg">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        +15.2%
                    </div>
                </div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Sales</h3>
                <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                <div class="absolute right-0 bottom-0 opacity-5">
                    <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
            </div>

            <!-- Active Employees -->
            <div
                class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group hover:border-orange-200 transition-all">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div
                        class="flex items-center gap-1 text-xs font-bold text-gray-500 bg-gray-100 px-2 py-1 rounded-lg">
                        0%
                    </div>
                </div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Active Employees</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $totalStaff }}</p>
                <div class="absolute right-0 bottom-0 opacity-5">
                    <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
            </div>

            <!-- Total Products -->
            <div
                class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group hover:border-orange-200 transition-all">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <div
                        class="flex items-center gap-1 text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded-lg">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        +5%
                    </div>
                </div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Products</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $totalProducts }}</p>
                <div class="absolute right-0 bottom-0 opacity-5">
                    <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>

            <!-- Low Stock Alerts -->
            <div
                class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group hover:border-red-200 transition-all">
                <div class="absolute -right-6 -top-6 bg-red-500/10 w-24 h-24 rounded-full blur-2xl"></div>
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center text-red-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 text-xs font-bold text-red-600 bg-red-50 px-2 py-1 rounded-lg">
                        Action Needed
                    </div>
                </div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Low Stock Alerts</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $lowStockCount }}</p>
                <div class="absolute right-0 bottom-0 opacity-5">
                    <svg class="w-24 h-24 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Sales Performance Chart -->
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Sales Performance</h3>
                        <p class="text-sm text-gray-500">Weekly revenue overview</p>
                    </div>
                    <select
                        class="text-xs font-bold bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 cursor-pointer focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none">
                        <option>This Week</option>
                        <option>Last Week</option>
                    </select>
                </div>
                <div class="h-80 w-full">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Recent Activity</h3>
                    <a href="{{ route('admin.reports') }}"
                        class="text-xs font-bold text-orange-600 hover:text-orange-700">View All</a>
                </div>
                <div class="flow-root">
                    <ul class="-my-5 divide-y divide-gray-100">
                        @foreach ($recentActivities as $activity)
                            <li class="py-5">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        @if ($activity->cashier && $activity->cashier->photo)
                                            <img class="h-10 w-10 rounded-full object-cover"
                                                src="{{ asset('storage/' . $activity->cashier->photo) }}"
                                                alt="">
                                        @else
                                            <div
                                                class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-500">
                                                {{ substr($activity->cashier->name ?? '?', 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            Sold Order #{{ $activity->transaction_code }}
                                        </p>
                                        <p class="text-xs text-gray-500 truncate">
                                            by {{ $activity->cashier->name ?? 'Unknown' }}
                                        </p>
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ $activity->created_at->diffForHumans(null, true, true) }}
                                    </div>
                                </div>
                            </li>
                        @endforeach

                        @if ($recentActivities->isEmpty())
                            <li class="py-5 text-center text-sm text-gray-500">
                                No recent activity found.
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Script -->
    <script>
        document.addEventListener('alpine:init', () => {
            // ...
        });

        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('salesChart').getContext('2d');

            // Prepare Data
            const labels = @json($weeklySales->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('D')));
            const data = @json($weeklySales->pluck('total'));

            // If empty data (fill with explicit zeros for days)
            // Ideally we should process this in controller to handle missing days, 
            // but for now simple display is fine.

            // Gradient
            let gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(234, 88, 12, 0.2)'); // Orange-600 with opacity
            gradient.addColorStop(1, 'rgba(234, 88, 12, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels.length ? labels : ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Revenue',
                        data: data.length ? data : [0, 0, 0, 0, 0, 0, 0],
                        borderColor: '#ea580c', // Orange-600
                        backgroundColor: gradient,
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#ea580c',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1f2937',
                            padding: 12,
                            titleFont: {
                                size: 13
                            },
                            bodyFont: {
                                size: 13,
                                weight: 'bold'
                            },
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed
                                        .y);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [4, 4],
                                color: '#f3f4f6',
                                drawBorder: false
                            },
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) +
                                        'M';
                                    if (value >= 1000) return 'Rp ' + (value / 1000).toFixed(0) + 'k';
                                    return value;
                                },
                                font: {
                                    size: 11
                                },
                                color: '#9ca3af'
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                font: {
                                    size: 11
                                },
                                color: '#9ca3af'
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-admin-layout>
