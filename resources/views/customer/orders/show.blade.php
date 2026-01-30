<x-app-layout>
    <x-customer-sidebar active="orders">
        <div class="space-y-6">

            <!-- Context Breadcrumb & Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                        <a href="{{ route('orders.index') }}" class="hover:text-primary">Pesanan</a>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                        <span
                            class="font-bold text-gray-800">#{{ $order->transaction_code ?? ($order->invoice_code ?? $order->id) }}</span>
                    </div>
                    <h1 class="text-3xl font-bold font-display text-primary">Detail Pesanan</h1>
                    <p class="text-gray-500 mt-1 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        Dipesan pada {{ $order->created_at->format('d F Y, H:i') }}
                    </p>
                </div>
                <a href="{{ route('checkout.success', $order->id) }}"
                    class="bg-white border-2 border-gray-300 text-gray-700 font-bold px-4 py-2 rounded-lg hover:bg-gray-50 hover:text-primary transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Bukti Pembayaran
                </a>
            </div>

            <!-- Progress Tracker -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 overflow-x-auto">
                <h3 class="font-bold text-lg text-primary mb-8 flex items-center gap-2">
                    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z">
                        </path>
                    </svg>
                    Status Pesanan
                </h3>

                @php
                    $steps = [
                        ['status' => 'pending', 'label' => 'Dipesan', 'date' => $order->created_at],
                        ['status' => 'processing', 'label' => 'Diverifikasi', 'date' => null],
                        ['status' => 'shipped', 'label' => 'Dikirim', 'date' => null],
                        ['status' => 'completed', 'label' => 'Diterima', 'date' => null],
                    ];

                    // Determine current Step Index
                    $currentStatus = strtolower($order->order_status ?? 'pending');
                    $statusMap = [
                        'pending' => 0,
                        'paid' => 1, // Paid maps to Verified step visually or stays at Pending? Usually Paid = Verified start. Let's map to Verified (1) or Pending (0). If paid but not verified manually, user sees Order -> Verified.
    // Wait, previous logic: pending=0, paid=1, processing=2.
    // Now: pending=0, processing=1, shipped=2, completed=3.
    // If status is 'paid', it should probably show as 'Dipesan' (0) or 'Diverifikasi' (1)?
    // The flowchart is: Order -> Pay -> Verify -> Ship.
    // If we hide Pay, then Order -> Verify.
    // So 'paid' status should effectively be step 0 (Dipesan) until it enters 'processing' (Verified).
    // Or if 'paid' means "Waiting for Verification", it's still 0.
                        'pending' => 0,
                        'paid' => 0,
                        'processing' => 1,
                        'shipped' => 2,
                        'completed' => 3,
                        'cancelled' => -1,
                    ];
                    $currentIndex = $statusMap[$currentStatus] ?? 0;
                @endphp

                <div class="relative flex items-center justify-between min-w-[600px]">
                    <!-- Progress Bar Background -->
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-gray-100 -z-10"></div>
                    <!-- Active Progress Bar -->
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-accent -z-10 transition-all duration-1000"
                        style="width: {{ $currentIndex * 33.33 }}%"></div>

                    @foreach ($steps as $index => $step)
                        <div class="flex flex-col items-center gap-2">
                            <div
                                class="w-8 h-8 rounded-full flex items-center justify-center border-2 {{ $index <= $currentIndex ? 'bg-accent border-accent text-white' : 'bg-white border-gray-300 text-gray-300' }}">
                                @if ($index <= $currentIndex)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                @else
                                    <span class="text-xs font-bold">{{ $index + 1 }}</span>
                                @endif
                            </div>
                            <div class="text-center">
                                <p
                                    class="text-xs font-bold {{ $index <= $currentIndex ? 'text-primary' : 'text-gray-400' }} uppercase tracking-wide">
                                    {{ $step['label'] }}</p>
                                @if ($index <= $currentIndex && $step['date'])
                                    <!-- <p class="text-[10px] text-gray-400">{{ $step['date']->format('d M, H:i') }}</p> -->
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($order->shipped_at)
                    @php
                        $shippingService = app(\App\Services\ShippingService::class);
                        $estimatedDays = $shippingService->getEstimatedDeliveryDays($order->shipping_destination);
                        $estimatedDelivery = $order->shipped_at->addDays($estimatedDays);
                    @endphp
                    <div class="mt-4 text-center">
                        <p class="text-xs text-gray-500">
                            Estimasi tiba: <span
                                class="font-bold text-accent">{{ $estimatedDelivery->format('d F Y') }}</span>
                            ({{ $estimatedDays }} hari kerja)
                        </p>
                    </div>
                @endif
            </div>

            <!-- Two Columns Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left Column (Items & Address) -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Order Items -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                            <h3 class="font-bold text-lg text-primary">Daftar Produk ({{ $order->details->count() }})
                            </h3>
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'paid' => 'bg-blue-100 text-blue-800',
                                    'processing' => 'bg-blue-100 text-blue-800',
                                    'shipped' => 'bg-blue-100 text-blue-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                ];
                                $statusLabels = [
                                    'pending' => 'MENUNGGU VERIFIKASI',
                                    'paid' => 'DIBAYAR',
                                    'processing' => 'DIPROSES',
                                    'shipped' => 'DIKIRIM',
                                    'completed' => 'SELESAI',
                                    'cancelled' => 'DIBATALKAN',
                                ];
                                $currentStatus = strtolower($order->order_status ?? 'pending');
                                $badgeColor = $statusColors[$currentStatus] ?? 'bg-gray-100 text-gray-800';
                                $statusLabel = $statusLabels[$currentStatus] ?? strtoupper($currentStatus);
                            @endphp
                            <span
                                class="{{ $badgeColor }} text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wide">
                                {{ $statusLabel }}
                            </span>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @foreach ($order->details as $detail)
                                <div class="p-6 flex items-center gap-4">
                                    <div
                                        class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0 border border-gray-100">
                                        @php
                                            // Priority: Variant Photo -> Product Photo -> Placeholder
                                            $photoPath =
                                                $detail->productVariant->photo ??
                                                ($detail->productVariant->product->photo ?? null);
                                            $imageUrl = $photoPath
                                                ? Storage::url($photoPath)
                                                : 'https://placehold.co/100x100/F5F5F5/999999?text=No+Image';
                                        @endphp
                                        <img src="{{ $imageUrl }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-[10px] text-accent font-bold uppercase tracking-wider mb-1">
                                            {{ $detail->productVariant->product->brand ?? '' }}</p>
                                        <h4 class="font-bold text-primary text-sm mb-1">
                                            {{ $detail->productVariant->product->name ?? 'Product Name' }}</h4>
                                        <p class="text-gray-500 text-[10px]">Size: {{ $detail->productVariant->size }}
                                            •
                                            Color: {{ $detail->productVariant->color }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[10px] text-gray-500 mb-1">Qty: {{ $detail->quantity }}</p>
                                        <p class="font-bold text-primary text-base">Rp
                                            {{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="font-bold text-lg text-primary mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Alamat Pengiriman
                        </h3>
                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900 mb-1">{{ $order->recipient_name }}</h4>
                                <p class="text-gray-600 text-sm leading-relaxed mb-4">
                                    {{ $order->shipping_address }}<br>
                                    {{ $order->shipping_destination }}
                                </p>
                                <p class="text-accent font-bold text-sm">{{ $order->recipient_phone }}</p>
                            </div>
                            <!-- Map Placeholder -->
                            {{-- <div
                                class="w-full md:w-1/2 h-32 bg-orange-50 rounded-lg border border-orange-100 flex items-center justify-center relative overflow-hidden group">
                                <div
                                    class="absolute inset-0 opacity-10 bg-[url('https://upload.wikimedia.org/wikipedia/commons/e/ec/World_map_blank_without_borders.svg')] bg-cover bg-center">
                                </div>
                                <button
                                    class="bg-white text-primary text-xs font-bold px-3 py-1.5 rounded shadow-sm z-10 border border-gray-200 group-hover:scale-105 transition-transform">
                                    Lihat di Peta
                                </button>
                            </div> --}}
                        </div>
                    </div>
                    @if ($order->notes)
                        <!-- Order Note -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <h3 class="font-bold text-lg text-primary mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                                Catatan Pesanan
                            </h3>
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                                <p class="text-gray-700 italic">"{{ $order->notes }}"</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Column (Payment & Summary) -->
                <div class="space-y-6">
                    <!-- Payment Method -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="font-bold text-lg text-primary mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                </path>
                            </svg>
                            Metode Pembayaran
                        </h3>

                        @php
                            $rawPayment = $order->payment_method ?? '';
                            $lowerPayment = strtolower($rawPayment);
                            $isBank = Str::contains($lowerPayment, [
                                'bank',
                                'transfer',
                                'bca',
                                'mandiri',
                                'bri',
                                'bni',
                                'cimb',
                                'permata',
                            ]);
                            $isQris = Str::contains($lowerPayment, 'qris');
                            $isEwallet = Str::contains($lowerPayment, ['gopay', 'ovo', 'dana', 'shopeepay', 'linkaja']);

                            // Parse "TYPE: DETAIL" format if exists
                            $parts = explode(':', $rawPayment);
                            $type = trim($parts[0] ?? $rawPayment);
                            $detail = trim($parts[1] ?? '');
                        @endphp

                        @if ($isQris)
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 text-center">
                                <p class="text-xs text-gray-500 uppercase tracking-widest font-bold mb-3">QRIS</p>
                                <div class="inline-block bg-white p-3 rounded-lg shadow-sm">
                                    <img src="{{ asset($storeSettings['qris_image'] ?? 'images/payment/qris-distrozone.png') }}"
                                        alt="QRIS DistroZone" class="w-32 h-auto mx-auto">
                                </div>
                                <p class="text-xs text-gray-600 mt-3">Scan QR Code untuk pembayaran</p>
                            </div>
                        @elseif($isBank)
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 space-y-3">
                                <p
                                    class="text-xs text-gray-500 uppercase tracking-widest font-bold border-b border-gray-200 pb-2 mb-2">
                                    TRANSFER BANK</p>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">Bank</span>
                                    <span class="font-bold text-gray-900 uppercase">
                                        {{ $detail ?: $storeSettings['bank_name'] ?? 'BCA' }}
                                    </span>
                                </div>
                                @if (empty($detail) || Str::contains($lowerPayment, ['manual', 'transfer']))
                                    {{-- Fallback to manual settings if no specific dynamic detail is present --}}
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-600">No. Rekening</span>
                                        <span
                                            class="font-bold text-gray-900">{{ $storeSettings['bank_account_number'] ?? '1234567890' }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-600">Atas Nama</span>
                                        <span
                                            class="font-bold text-gray-900">{{ $storeSettings['bank_account_holder'] ?? 'DistroZone Official' }}</span>
                                    </div>
                                @else
                                    {{-- If it's a dynamic VA (e.g. "BCA VIRTUAL ACCOUNT"), usually the number is in another field, 
                                         but if not available, just show the type. Assuming $detail holds the specific bank/VA name --}}
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-600">Metode</span>
                                        <span class="font-bold text-gray-900">{{ $detail }}</span>
                                    </div>
                                @endif
                            </div>
                        @elseif($isEwallet)
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 space-y-3">
                                <p
                                    class="text-xs text-gray-500 uppercase tracking-widest font-bold border-b border-gray-200 pb-2 mb-2">
                                    E-WALLET</p>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">Provider</span>
                                    {{-- Try to extract provider name from detail or raw string --}}
                                    <span class="font-bold text-gray-900 uppercase">
                                        {{ $detail ?: str_replace('_', ' ', $type) }}
                                    </span>
                                </div>
                            </div>
                        @else
                            {{-- Default Fallback --}}
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 space-y-3">
                                <p
                                    class="text-xs text-gray-500 uppercase tracking-widest font-bold border-b border-gray-200 pb-2 mb-2">
                                    {{ strtoupper($type) }} (LAINNYA)
                                </p>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">Detail</span>
                                    <span class="font-bold text-gray-900 text-right">
                                        {{ $detail ?: ucfirst($rawPayment) }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Order Summary -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="font-bold text-lg text-primary mb-4">Rincian Biaya</h3>
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-bold text-gray-900">Rp
                                    {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Ongkos Kirim ({{ $order->weight_kg }}kg)</span>
                                <span class="font-bold text-gray-900">Rp
                                    {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Pajak (Termasuk)</span>
                                <span class="font-bold text-gray-900">Rp 0</span>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-gray-100 flex justify-between items-end">
                            <span class="font-bold text-gray-900 text-xs uppercase tracking-wide">Total Tagihan</span>
                            <span class="font-bold text-2xl text-accent">Rp
                                {{ number_format($order->total, 0, ',', '.') }}</span>
                        </div>

                        @php
                            $isShipped = strtolower($order->order_status) === 'shipped';
                            $isCompleted = strtolower($order->order_status) === 'completed';
                        @endphp

                        @if ($isCompleted)
                            <button disabled
                                class="w-full mt-6 bg-green-100 text-green-700 font-bold py-3 rounded-lg border border-green-200 uppercase tracking-wide cursor-not-allowed">
                                ✓ Paket Telah Diterima
                            </button>
                        @else
                            <button id="confirmReceiptBtn" {{ $isShipped ? '' : 'disabled' }}
                                class="w-full mt-6 font-bold py-3 rounded-lg shadow-lg uppercase tracking-wide transition-all {{ $isShipped ? 'bg-accent text-white shadow-accent/20 hover:bg-accent-light cursor-pointer' : 'bg-gray-300 text-gray-500 cursor-not-allowed' }}">
                                Paket Telah di Terima
                            </button>
                        @endif

                        @if ($isShipped)
                            <script>
                                document.getElementById('confirmReceiptBtn').addEventListener('click', function() {
                                    if (confirm('Apakah Anda yakin telah menerima paket ini?')) {
                                        const button = this;
                                        button.disabled = true;
                                        button.textContent = 'Memproses...';

                                        fetch('{{ route('orders.mark-received', $order->id) }}', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                }
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.success) {
                                                    alert('Terima kasih! Pesanan telah dikonfirmasi diterima.');
                                                    location.reload();
                                                } else {
                                                    alert('Gagal: ' + data.message);
                                                    button.disabled = false;
                                                    button.textContent = 'Paket Telah di Terima';
                                                }
                                            })
                                            .catch(error => {
                                                alert('Terjadi kesalahan. Silakan coba lagi.');
                                                button.disabled = false;
                                                button.textContent = 'Paket Telah di Terima';
                                            });
                                    }
                                });
                            </script>
                        @endif
                    </div>

                    <!-- Help Card -->
                    <div class="bg-orange-50 border border-orange-100 rounded-xl p-6 text-center">
                        <div
                            class="w-12 h-12 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm text-accent">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </div>
                        <h4 class="font-bold text-primary mb-1">Butuh Bantuan?</h4>
                        <p class="text-xs text-gray-600 mb-4">Tim support kami siap membantu 24/7 jika ada masalah
                            dengan pesanan Anda.</p>
                        <a href="#" class="text-accent font-bold text-sm hover:underline">Chat Support Now</a>
                    </div>
                </div>
            </div>

        </div>
    </x-customer-sidebar>
</x-app-layout>
