<x-admin-layout>
    <x-slot name="head">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Financial Overview</h1>
                <p class="text-orange-600 mt-1">Monthly Sales Report & Analytics</p>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mt-4 md:mt-0">
                <span>Home</span>
                <span class="text-gray-300">></span>
                <span>Reports</span>
                <span class="text-gray-300">></span>
                <span class="font-medium text-gray-900">Overview</span>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
            <form method="GET" action="{{ route('admin.reports') }}"
                class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                <!-- Start Date -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Start Date</label>
                    <div class="relative">
                        <input type="date" name="start_date" value="{{ $startDate }}"
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>

                <!-- End Date -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">End Date</label>
                    <div class="relative">
                        <input type="date" name="end_date" value="{{ $endDate }}"
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>

                <!-- Cashier -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Cashier</label>
                    <div class="relative">
                        <select name="cashier_id"
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all appearance-none">
                            <option value="">All Staff Members</option>
                            @foreach ($cashiers as $cashier)
                                <option value="{{ $cashier->id }}"
                                    {{ request('cashier_id') == $cashier->id ? 'selected' : '' }}>{{ $cashier->name }}
                                </option>
                            @endforeach
                        </select>
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <svg class="w-4 h-4 text-gray-400 absolute right-3 top-4 pointer-events-none" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                <!-- Apply Button -->
                <button type="submit"
                    class="w-full py-3 bg-[#0f0f0f] text-white font-bold rounded-xl hover:bg-gray-800 transition-colors flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Apply Filter
                </button>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <!-- Total Transaction -->
            <div
                class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative group hover:border-orange-200 transition-all">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-1">Total Transaction</p>
                        <h3 class="text-3xl font-bold text-gray-900">
                            {{ number_format($report['summary']['total_transactions']) }}</h3>
                    </div>
                    <div
                        class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 group-hover:bg-orange-50 group-hover:text-orange-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
                {{-- <div class="flex items-center gap-1 text-xs font-bold text-green-600 bg-green-50 px-2.5 py-1 rounded-lg w-fit">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    +12.5% vs last month
                </div> --}}
            </div>

            <!-- Laba / Rugi (Net) -->
            <div
                class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative group hover:border-orange-200 transition-all">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-1">Laba / Rugi (Net)</p>
                        <h3 class="text-3xl font-bold text-orange-600">Rp
                            {{ number_format($report['summary']['total_profit'] ?? 0, 0, ',', '.') }}</h3>
                    </div>
                </div>
                <div
                    class="flex items-center gap-1 text-xs font-bold text-green-600 bg-green-50 px-2.5 py-1 rounded-lg w-fit">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    +{{ number_format($report['summary']['profit_margin'] ?? 0, 1) }}% Margin
                </div>
            </div>

            <!-- Total Revenue -->
            <div
                class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative group hover:border-orange-200 transition-all">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-1">Total Revenue</p>
                        <h3 class="text-3xl font-bold text-orange-600">Rp
                            {{ number_format($report['summary']['total_revenue'], 0, ',', '.') }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center text-orange-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500">Gross Sales Volume</p>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Recent Transactions
                </h3>
                <button class="text-sm font-bold text-orange-600 hover:text-orange-700 flex items-center gap-1">
                    Export to CSV
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-orange-50/50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Date / Time</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Transaction ID</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Cashier</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Items</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Payment</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Total Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($transactions as $transaction)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">
                                        {{ $transaction->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $transaction->created_at->format('H:i') }}
                                        WIB</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="text-sm font-bold text-gray-900">#{{ $transaction->transaction_code }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                                            @if ($transaction->cashier && $transaction->cashier->photo)
                                                <img src="{{ asset('storage/' . $transaction->cashier->photo) }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <span
                                                    class="text-xs font-bold text-gray-500">{{ substr($transaction->cashier->name ?? '?', 0, 1) }}</span>
                                            @endif
                                        </div>
                                        <span
                                            class="text-sm font-medium text-gray-700">{{ $transaction->cashier->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-700 line-clamp-1">
                                        @foreach ($transaction->details as $detail)
                                            {{ $detail->productVariant->product->name }} ({{ $detail->quantity }}),
                                        @endforeach
                                    </div>
                                    <div class="text-xs text-gray-400 mt-0.5">{{ $transaction->details->count() }}
                                        Items</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        @if ($transaction->payment_method == 'qris')
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        @endif
                                        {{ ucfirst($transaction->payment_method) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold text-orange-600">Rp
                                        {{ number_format($transaction->total, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClasses = [
                                            'verified' => 'bg-green-100 text-green-700',
                                            'pending' => 'bg-yellow-100 text-yellow-700',
                                            'rejected' => 'bg-red-100 text-red-700',
                                        ];
                                        $statusLabel = [
                                            'verified' => 'Completed',
                                            'pending' => 'Pending',
                                            'rejected' => 'Refunded', // Adjusting label to match design style if needed
                                        ];
                                    @endphp
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-bold {{ $statusClasses[$transaction->payment_status] ?? 'bg-gray-100' }}">
                                        {{ $statusLabel[$transaction->payment_status] ?? ucfirst($transaction->payment_status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    No transactions found for this period
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($transactions->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <div class="text-sm text-gray-500">
                        Showing <span
                            class="font-bold">{{ $transactions->firstItem() }}-{{ $transactions->lastItem() }}</span>
                        of {{ $transactions->total() }} results
                    </div>
                    <div>
                        {{ $transactions->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
