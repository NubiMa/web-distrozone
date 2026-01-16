<x-app-layout>
    <x-customer-sidebar active="orders">
        <!-- Main Content Area -->
        <div class="space-y-6">

            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold font-display text-primary">Riwayat Pesanan</h1>
                    <p class="text-gray-500 mt-1">Kelola dan lacak status pesanan terbaru Anda.</p>
                </div>
                <!-- Export button ignored as requested -->
            </div>

            <!-- Status Tabs -->
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 overflow-x-auto" aria-label="Tabs">
                    @php
                        $tabs = [
                            'all' => 'Semua Pesanan',
                            'pending' => 'Menunggu Verifikasi',
                            'processing' => 'Diproses',
                            'shipped' => 'Dikirim',
                            'completed' => 'Selesai',
                            'cancelled' => 'Dibatalkan',
                        ];
                        $currentStatus = request('status', 'all');
                    @endphp

                    @foreach ($tabs as $key => $label)
                        <a href="{{ route('orders.index', ['status' => $key]) }}"
                            class="{{ $currentStatus === $key
                                ? 'border-accent text-accent'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} 
                               whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm transition-colors">
                            {{ $label }}
                            @if ($key === 'all')
                                <span
                                    class="bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-xs ml-2">{{ $orders->total() }}</span>
                            @endif
                        </a>
                    @endforeach
                </nav>
            </div>

            <!-- Order List -->
            <div class="space-y-6">
                @if ($orders->isEmpty())
                    <div class="bg-white rounded-xl border border-dashed border-gray-300 p-12 text-center">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-primary mb-1">Belum Ada Pesanan</h3>
                        <p class="text-gray-500 text-sm mb-6">Anda belum memiliki pesanan dengan status ini.</p>
                        <a href="{{ url('/katalog') }}"
                            class="inline-block bg-accent text-white font-bold px-6 py-2.5 rounded-lg hover:bg-accent-light transition-colors shadow-lg shadow-accent/20">
                            Mulai Belanja
                        </a>
                    </div>
                @else
                    @foreach ($orders as $order)
                        <div
                            class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                            <div class="p-6 md:p-8">
                                <div class="flex flex-col lg:flex-row justify-between lg:items-start gap-6">

                                    <!-- Left Section: Status & Info -->
                                    <div class="flex-1">
                                        <!-- Status Indicator -->
                                        @php
                                            $statusColors = [
                                                'pending' => 'text-yellow-600 bg-yellow-50',
                                                'paid' => 'text-blue-600 bg-blue-50',
                                                'processing' => 'text-blue-600 bg-blue-50',
                                                'shipped' => 'text-purple-600 bg-purple-50',
                                                'completed' => 'text-green-600 bg-green-50',
                                                'cancelled' => 'text-red-600 bg-red-50',
                                            ];
                                            $statusDotColors = [
                                                'pending' => 'bg-yellow-500',
                                                'paid' => 'bg-blue-500',
                                                'processing' => 'bg-blue-500',
                                                'shipped' => 'bg-purple-500',
                                                'completed' => 'bg-green-500',
                                                'cancelled' => 'bg-red-500',
                                            ];
                                            $statusLabels = [
                                                'pending' => 'MENUNGGU VERIFIKASI',
                                                'paid' => 'SUDAH DIBAYAR',
                                                'processing' => 'DIPROSES',
                                                'shipped' => 'DALAM PENGIRIMAN',
                                                'completed' => 'SELESAI',
                                                'cancelled' => 'DIBATALKAN',
                                            ];
                                            $status = strtolower($order->order_status ?? 'pending');
                                            $activeColor = $statusColors[$status] ?? 'text-gray-600 bg-gray-50';
                                            $activeDot = $statusDotColors[$status] ?? 'bg-gray-500';
                                        @endphp

                                        <div class="flex items-center gap-2 mb-3">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $activeDot }}"></span>
                                            <span
                                                class="text-[10px] font-bold uppercase tracking-wider {{ str_replace('bg-', 'text-', $activeDot) }}">
                                                {{ $statusLabels[$status] ?? $status }}
                                            </span>
                                        </div>

                                        <h2 class="text-xl font-bold text-primary mb-1">
                                            #{{ $order->transaction_code ?? ($order->invoice_code ?? $order->id) }}
                                        </h2>
                                        <p class="text-gray-500 text-xs mb-6">
                                            Dipesan pada {{ $order->created_at->format('d F Y') }}
                                        </p>

                                        <!-- Product Thumbnails -->
                                        <div class="flex items-center gap-3">
                                            @foreach ($order->details->take(3) as $detail)
                                                <div
                                                    class="w-24 h-24 bg-gray-100 rounded-lg border border-gray-100 overflow-hidden flex-shrink-0 relative group">
                                                    <img src="{{ $detail->product->photo ? Storage::url($detail->product->photo) : 'https://placehold.co/100x100/F5F5F5/999999?text=No+Image' }}"
                                                        class="w-full h-full object-cover"
                                                        alt="{{ $detail->product->brand }}">

                                                    <!-- Tooltip on hover -->
                                                    <div
                                                        class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                        <span
                                                            class="text-white text-xs font-bold">x{{ $detail->quantity }}</span>
                                                    </div>
                                                </div>
                                            @endforeach

                                            @if ($order->details->count() > 3)
                                                <div
                                                    class="w-24 h-24 bg-gray-50 rounded-lg border border-gray-100 flex items-center justify-center text-gray-500 font-bold text-sm">
                                                    +{{ $order->details->count() - 3 }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Right Section: Price & Actions -->
                                    <div class="flex flex-col lg:items-end justify-between min-h-[140px]">
                                        <div class="text-left lg:text-right mb-6 lg:mb-0">
                                            <p
                                                class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">
                                                TOTAL BELANJA</p>
                                            <p class="text-2xl font-bold text-accent">
                                                Rp {{ number_format($order->total, 0, ',', '.') }}
                                            </p>
                                            <p class="text-[10px] text-gray-500 mt-1">
                                                {{ $order->details->sum('quantity') }} items included</p>
                                        </div>

                                        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                                            <!-- Track Order Button (Visible if active) -->
                                            @if (in_array($status, ['processing', 'shipped']))
                                                <button
                                                    onclick="window.location.href='{{ route('orders.show', $order->id) }}'"
                                                    class="flex items-center justify-center gap-2 bg-white border-2 border-primary text-primary px-5 py-2.5 rounded-lg font-bold text-sm hover:bg-gray-50 transition-colors uppercase tracking-wide">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 00-1 1v1H7v-3a1 1 0 00-1-1H3">
                                                        </path>
                                                    </svg>
                                                    Lacak Paket
                                                </button>
                                            @endif

                                            <a href="{{ route('orders.show', $order->id) }}"
                                                class="{{ in_array($status, ['processing', 'shipped']) ? 'text-gray-500 hover:text-primary font-bold text-sm flex items-center justify-center px-4' : 'flex items-center justify-center gap-2 bg-white border-2 border-gray-300 text-gray-600 px-5 py-2.5 rounded-lg font-bold text-sm hover:border-gray-400 hover:text-gray-800 transition-colors uppercase tracking-wide' }}">
                                                @if (in_array($status, ['processing', 'shipped']))
                                                    Lihat Detail
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                    Lihat Detail
                                                @endif
                                            </a>
                                        </div>

                                        <!-- Est Delivery / Status Text -->
                                        @if ($status === 'shipped')
                                            <div class="w-full mt-4 pt-4 border-t border-gray-100 sm:text-right">
                                                <span class="text-xs font-bold text-accent uppercase">EST. TIBA: 1-3
                                                    HARI</span>
                                            </div>
                                        @elseif($status === 'completed')
                                            <div
                                                class="w-full mt-4 pt-4 border-t border-gray-100 sm:text-right flex items-center justify-end gap-1 text-green-600">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="text-xs font-bold uppercase">PESANAN DITERIMA</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if ($orders->hasPages())
                        <div class="mt-8">
                            {{ $orders->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </x-customer-sidebar>
</x-app-layout>
