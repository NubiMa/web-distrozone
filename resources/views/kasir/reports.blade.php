<x-kasir-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header & Filter -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Cashier Reports</h1>
                <p class="text-gray-500 mt-1">Daily performance overview & analytics</p>
            </div>

            <!-- Filter -->
            <form action="{{ url('/kasir/reports') }}" method="GET"
                class="bg-white p-1.5 rounded-xl border border-gray-200 shadow-sm flex items-center gap-2">
                <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-50 rounded-lg border border-gray-200">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">FROM</span>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                        class="bg-transparent border-none p-0 text-sm font-medium text-gray-900 focus:ring-0">
                </div>
                <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-50 rounded-lg border border-gray-200">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">TO</span>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                        class="bg-transparent border-none p-0 text-sm font-medium text-gray-900 focus:ring-0">
                </div>
                <button type="submit"
                    class="bg-black text-white px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 hover:bg-gray-800 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter
                </button>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Transactions -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-gray-500 mb-1">Total Transactions</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-3xl font-bold text-gray-900">
                        {{ number_format($report['summary']['total_transactions']) }}</h3>
                </div>
            </div>

            <!-- Sales Today (Highlighted) -->
            <div class="bg-orange-50 p-6 rounded-2xl border border-orange-100 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg class="w-24 h-24 text-orange-500" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.15-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39h-2.01c-.15-.9-.6-1.38-1.77-1.38-1.4 0-2.14.71-2.14 1.43 0 .74.49 1.48 2.7 2.03 2.59.63 4.12 1.81 4.12 3.82 0 1.93-1.57 3.33-3.21 3.49z" />
                    </svg>
                </div>
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-orange-700 font-medium mb-1 relative z-10">My Sales Today</p>
                <h3 class="text-3xl font-bold text-orange-900 relative z-10">Rp
                    {{ number_format($todaySales, 0, ',', '.') }}</h3>
            </div>

            <!-- Avg Order Value -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-gray-500 mb-1">Avg. Order Value</p>
                <h3 class="text-3xl font-bold text-gray-900">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</h3>
            </div>

            <!-- Cash in Drawer -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-gray-500 mb-1">Cash in Drawer</p>
                <h3 class="text-3xl font-bold text-gray-900">Rp {{ number_format($cashSales, 0, ',', '.') }}</h3>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Payment Methods -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-900">Payment Methods</h3>
                        <button class="text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-6">
                        @foreach ($report['by_payment_method'] as $method => $data)
                            @php
                                $percentage =
                                    $report['summary']['total_revenue'] > 0
                                        ? ($data['total'] / $report['summary']['total_revenue']) * 100
                                        : 0;
                                $icon = match ($method) {
                                    'qris'
                                        => 'M12 4v1m6 11h2m-6 0h-2v4h2v-4zM6 6h2v2H6V6zm0 8h2v2H6v-2zm8-8h2v2h-2V6zm-8 4h2v2H6v-2zm4-4h2v2h-2V6zm4 0h2v2h-2V6zm-8 8h2v2H6v-2zm8 0h2v2h-2v-2zm4-4h2v2h-2v-2z', // Fake QR abstract
                                    'tunai'
                                        => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z', // Cash
                                    'transfer'
                                        => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', // Card/Bank
                                    default
                                        => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                                };
                                $bgClass = match ($method) {
                                    'qris' => 'bg-blue-50 text-blue-600',
                                    'tunai' => 'bg-green-50 text-green-600',
                                    'transfer' => 'bg-purple-50 text-purple-600',
                                    default => 'bg-gray-50 text-gray-600',
                                };
                            @endphp
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-lg {{ $bgClass }} flex items-center justify-center">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="{{ $icon }}" />
                                            </svg>
                                        </div>
                                        <span
                                            class="font-bold text-gray-900 capitalize">{{ $method == 'tunai' ? 'Cash' : ucfirst($method) }}</span>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-orange-600">Rp
                                            {{ number_format($data['total'], 0, ',', '.') }}</p>
                                        <p class="text-xs text-gray-400">{{ number_format($percentage, 0) }}%</p>
                                    </div>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-gray-900 h-full rounded-full" style="width: {{ $percentage }}%">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right: Recent Transactions -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="p-6 flex justify-between items-center border-b border-gray-50">
                        <h3 class="text-lg font-bold text-gray-900">Recent Transactions</h3>
                        <a href="{{ route('kasir.orders.index') }}"
                            class="text-sm font-bold text-orange-600 hover:text-orange-700 flex items-center gap-1">
                            View All <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full whitespace-nowrap">
                            <thead>
                                <tr class="bg-gray-50/50">
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                                        Time</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                                        Order ID</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                                        Items</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                                        Method</th>
                                    <th
                                        class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">
                                        Amount</th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">
                                        Receipt</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse ($transactions as $transaction)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                            {{ $transaction->created_at->format('H:i') }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            #{{ $transaction->transaction_code }}</td>
                                        <td class="px-6 py-4">
                                            @if ($transaction->details->count() > 0)
                                                <p class="text-sm font-bold text-gray-900">
                                                    {{ $transaction->details->first()->productVariant->product->brand ?? 'Unknown' }}
                                                </p>
                                                <p class="text-xs text-gray-400">
                                                    {{ $transaction->details->first()->productVariant->size ?? '-' }}
                                                    @if ($transaction->details->count() > 1)
                                                        <span
                                                            class="text-orange-500 font-bold ml-1">+{{ $transaction->details->count() - 1 }}
                                                            more</span>
                                                    @else
                                                        , Qty {{ $transaction->details->first()->quantity }}
                                                    @endif
                                                </p>
                                            @else
                                                <span class="text-sm text-gray-500">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $methodClass = match ($transaction->payment_method) {
                                                    'qris' => 'bg-blue-100 text-blue-700',
                                                    'tunai' => 'bg-green-100 text-green-700',
                                                    'transfer' => 'bg-purple-100 text-purple-700',
                                                    default => 'bg-gray-100 text-gray-700',
                                                };
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $methodClass }}">
                                                {{ $transaction->payment_method == 'tunai' ? 'Cash' : ucfirst($transaction->payment_method) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right text-sm font-bold text-orange-600">
                                            Rp {{ number_format($transaction->total, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <button class="text-gray-300 hover:text-gray-600 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">No
                                            transactions found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Simple Pagination -->
                    @if ($transactions->hasPages())
                        <div class="p-4 border-t border-gray-50 flex justify-end gap-2">
                            @if (!$transactions->onFirstPage())
                                <a href="{{ $transactions->previousPageUrl() }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7" />
                                    </svg>
                                </a>
                            @endif
                            @if ($transactions->hasMorePages())
                                <a href="{{ $transactions->nextPageUrl() }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-kasir-layout>
