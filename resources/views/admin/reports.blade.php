<x-admin-layout>
    <x-slot name="head">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Laporan Keuangan</h1>
                <p class="text-orange-600 mt-1">Laporan Penjualan & Analisis Bulanan</p>
            </div>
            {{-- <div class="flex items-center gap-2 text-sm text-gray-500 mt-4 md:mt-0">
                <span>Beranda</span>
                <span class="text-gray-300">></span>
                <span>Laporan</span>
                <span class="text-gray-300">></span>
                <span class="font-medium text-gray-900">Ringkasan</span>
            </div> --}}
        </div>

        <!-- Filters Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
            <div class="flex flex-col md:flex-row gap-6 justify-between items-end md:items-center">

                <!-- Search Box -->
                <div class="w-full md:w-1/3 relative">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Cari Transaksi</label>
                    <div class="relative">
                        <input type="text" id="searchInput" placeholder="Cari ID transaksi, kasir..."
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Filter Buttons and Cashier -->
                <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto items-end">

                    <!-- Cashier Select -->
                    <div class="w-full md:w-56">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Filter Kasir</label>
                        <select id="cashierFilter"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                            <option value="">Semua Kasir</option>
                            @foreach ($cashiers as $cashier)
                                <option value="{{ $cashier->id }}">{{ $cashier->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Time Filter Buttons -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Filter Pendapatan</label>
                        <div class="flex bg-gray-50 p-1 rounded-xl border border-gray-200">
                            <button onclick="setFilter('today')" id="btn-today"
                                class="filter-btn px-6 py-2 rounded-lg text-sm font-bold transition-all text-gray-500 hover:text-gray-900">
                                Hari Ini
                            </button>
                            <button onclick="setFilter('week')" id="btn-week"
                                class="filter-btn px-6 py-2 rounded-lg text-sm font-bold transition-all text-gray-500 hover:text-gray-900">
                                Minggu Ini
                            </button>
                            <button onclick="setFilter('month')" id="btn-month"
                                class="filter-btn active bg-white text-orange-600 shadow-sm px-6 py-2 rounded-lg text-sm font-bold transition-all">
                                Bulan Ini
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <!-- Total Transaction -->
            <div
                class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative group hover:border-orange-200 transition-all">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-1">Total Transaksi</p>
                        <h3 class="text-3xl font-bold text-gray-900" id="totalTransactions">
                            {{ number_format($report['summary']['total_transactions']) }}
                        </h3>
                    </div>
                    <div
                        class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 group-hover:bg-orange-50 group-hover:text-orange-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Laba / Rugi (Net) -->
            <div
                class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative group hover:border-orange-200 transition-all">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-1">Laba Bersih</p>
                        <h3 class="text-3xl font-bold text-orange-600">Rp <span
                                id="totalProfit">{{ number_format($report['summary']['total_profit'] ?? 0, 0, ',', '.') }}</span>
                        </h3>
                    </div>
                </div>
                <div
                    class="flex items-center gap-1 text-xs font-bold text-green-600 bg-green-50 px-2.5 py-1 rounded-lg w-fit">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    +<span id="profitMargin">{{ number_format($report['summary']['profit_margin'] ?? 0, 1) }}</span>%
                    Margin
                </div>
            </div>

            <!-- Total Revenue -->
            <div
                class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative group hover:border-orange-200 transition-all">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-1">Total Pendapatan</p>
                        <h3 class="text-3xl font-bold text-orange-600">Rp <span
                                id="totalRevenue">{{ number_format($report['summary']['total_revenue'], 0, ',', '.') }}</span>
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center text-orange-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500">Volume Penjualan Kotor</p>
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
                    Riwayat Transaksi
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-orange-50/50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Tanggal / Waktu</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">ID
                                Transaksi</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Kasir</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Item</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Pembayaran</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Total</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Margin</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Status</th>
                        </tr>
                    </thead>
                    <tbody id="transactionTableBody" class="divide-y divide-gray-100">
                        @include('admin.reports.partials.transaction_table')
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div id="paginationContainer" class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-end">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>

    <script>
        let currentFilter = 'month';
        let debounceTimer;

        document.getElementById('searchInput').addEventListener('input', function(e) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                fetchData(currentFilter, e.target.value);
            }, 300);
        });

        // Add event listener for Cashier Filter
        document.getElementById('cashierFilter').addEventListener('change', function() {
            fetchData(currentFilter, document.getElementById('searchInput').value);
        });

        function setFilter(filter) {
            currentFilter = filter;

            // Update UI buttons
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active', 'bg-white', 'text-orange-600', 'shadow-sm');
                btn.classList.add('text-gray-500');
            });
            const activeBtn = document.getElementById('btn-' + filter);
            activeBtn.classList.add('active', 'bg-white', 'text-orange-600', 'shadow-sm');
            activeBtn.classList.remove('text-gray-500');

            // Fetch Data
            fetchData(filter, document.getElementById('searchInput').value);
        }

        function fetchData(filter, search) {
            const cashierId = document.getElementById('cashierFilter').value;
            let url = `{{ route('admin.reports') }}?filter=${filter}&search=${search}&cashier_id=${cashierId}`;

            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Update Summary Cards
                    document.getElementById('totalTransactions').innerText = data.summary.total_transactions;
                    document.getElementById('totalProfit').innerText = data.summary.total_profit;
                    document.getElementById('profitMargin').innerText = data.summary.profit_margin;
                    document.getElementById('totalRevenue').innerText = data.summary.total_revenue;

                    // Update Table
                    document.getElementById('transactionTableBody').innerHTML = data.html;
                    document.getElementById('paginationContainer').innerHTML = data.pagination;
                })
                .catch(err => console.error(err));
        }
    </script>
</x-admin-layout>
