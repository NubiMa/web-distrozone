<x-kasir-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Cashier Dashboard</h1>
            <p class="text-gray-600 mt-1">Welcome back, {{ Auth::user()->name }}. Ready for the drop?</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <!-- Today's Sales -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg class="w-24 h-24 text-orange-500" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.15-1.46-3.27-3.4h1.96c.1 1.05 1.18 1.91 2.53 1.91 1.29 0 2.13-.81 2.13-1.88 0-1.1-.92-1.76-2.93-2.12-2.35-.42-3.48-1.57-3.48-3.3 0-1.81 1.44-2.95 3.06-3.33V4h2.67v1.89c1.47.34 2.76 1.34 3.01 3.16h-1.92c-.15-.81-1.01-1.49-2.31-1.49-1.07 0-1.97.83-1.97 1.83 0 .97.88 1.7 2.92 2.13 2.37.5 3.51 1.57 3.51 3.33.01 1.95-1.55 3.19-3.24 3.55z" />
                    </svg>
                </div>
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-4">
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">PENJUALAN HARI INI</p>
                        @php
                            $diff = $todaysSales - $yesterdaysSales;
                            $percentage =
                                $yesterdaysSales > 0 ? ($diff / $yesterdaysSales) * 100 : ($todaysSales > 0 ? 100 : 0);
                            $isPositive = $percentage >= 0;
                        @endphp
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $isPositive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $isPositive ? '+' : '' }}{{ number_format($percentage, 0) }}%
                        </span>
                    </div>
                    <h3 class="text-4xl font-bold text-gray-900">Rp {{ number_format($todaysSales, 0, ',', '.') }}</h3>
                    <p class="text-sm text-gray-400 mt-2">vs. Rp {{ number_format($yesterdaysSales, 0, ',', '.') }}
                        kemarin</p>
                </div>
            </div>

            <!-- Pending Verifications -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg class="w-24 h-24 text-orange-500" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z" />
                    </svg>
                </div>
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-4">
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">VERIFIKASI TERTUNDA</p>
                        @if ($pendingVerifications > 0)
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                Perhatian
                            </span>
                        @endif
                    </div>
                    <h3 class="text-4xl font-bold text-gray-900">{{ $pendingVerifications }} Pesanan</h3>
                    <p class="text-sm text-orange-500 mt-2 flex items-center gap-1">
                        @if ($pendingVerifications > 0)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Butuh persetujuan segera
                        @else
                            <span class="text-green-500">Semua aman terkendali</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Items Sold -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg class="w-24 h-24 text-orange-500" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4zM6 18.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm13.5-9l1.96 2.5H17V9.5h2.5zm-1.5 9c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z" />
                    </svg>
                </div>
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-4">
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">ITEM TERJUAL</p>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                            Penjualan Bagus
                        </span>
                    </div>
                    <h3 class="text-4xl font-bold text-gray-900">{{ $itemsSold }} Unit</h3>
                    <div class="flex items-center mt-2 space-x-1">
                        <!-- Tiny visual indicator of recent items -->
                        <div class="flex -space-x-2 overflow-hidden">
                            <div class="w-6 h-6 rounded-full bg-gray-200 border-2 border-white"></div>
                            <div class="w-6 h-6 rounded-full bg-gray-300 border-2 border-white"></div>
                            <div class="w-6 h-6 rounded-full bg-gray-400 border-2 border-white"></div>
                        </div>
                        <span class="text-xs text-gray-500 pl-2">Top movers today</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900">Transaksi Terkini</h3>
                <a href="{{ url('/kasir/orders') }}"
                    class="text-sm font-medium text-orange-600 hover:text-orange-700 hover:underline">
                    View All Orders
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <thead>
                        <tr class="text-left bg-gray-50/50">
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">ORDER ID
                            </th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">WAKTU
                            </th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">ITEM</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">TOTAL
                            </th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">STATUS
                            </th>
                            <th
                                class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">
                                AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentTransactions as $transaction)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <span
                                        class="text-sm font-bold text-gray-900">{{ $transaction->transaction_code }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-sm font-medium text-blue-600">{{ $transaction->created_at->format('h:i A') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        @foreach ($transaction->details->take(1) as $detail)
                                            <span
                                                class="text-sm text-gray-900 font-medium">{{ $detail->productVariant->product->brand }}
                                                {{ $detail->productVariant->product->category->name ?? '' }}</span>
                                        @endforeach
                                        @if ($transaction->details->count() > 1)
                                            <span
                                                class="text-xs text-gray-500">+{{ $transaction->details->count() - 1 }}
                                                item lainnya</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-gray-900">Rp
                                        {{ number_format($transaction->total, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($transaction->payment_status == 'verified' || $transaction->order_status == 'completed')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Paid
                                        </span>
                                    @elseif($transaction->payment_status == 'pending')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            Pending
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            {{ ucfirst($transaction->payment_status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                    Belum ada transaksi hari ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination or View All Footer (Optional) -->
            <div
                class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-between items-center text-xs text-gray-500">
                <span>Showing {{ $recentTransactions->count() }} recent transactions</span>
                <div class="flex gap-2">
                    <button
                        class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 bg-white hover:bg-gray-50 disabled:opacity-50"
                        disabled>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button
                        class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 bg-white hover:bg-gray-50 disabled:opacity-50"
                        disabled>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-kasir-layout>
