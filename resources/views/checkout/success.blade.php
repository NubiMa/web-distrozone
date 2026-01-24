<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Success Card -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">

                <!-- Header with Logo -->
                <div class="bg-gradient-to-r from-primary to-black text-white py-8 text-center relative overflow-hidden">
                    <div class="absolute inset-0 opacity-10">
                        <div
                            class="absolute top-0 left-0 w-64 h-64 bg-accent rounded-full blur-3xl transform -translate-x-32 -translate-y-32">
                        </div>
                        <div
                            class="absolute bottom-0 right-0 w-64 h-64 bg-orange-500 rounded-full blur-3xl transform translate-x-32 translate-y-32">
                        </div>
                    </div>
                    <div class="relative z-10">
                        <svg class="w-8 h-8 inline-block text-accent" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z" />
                        </svg>
                        <h1 class="text-3xl font-bold font-display mt-2">DISTROZONE</h1>
                    </div>
                </div>

                <!-- Success Icon & Message -->
                <div class="py-8 px-6 text-center">
                    <div
                        class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-orange-100 to-orange-50 rounded-full mb-4 shadow-lg shadow-orange-200/50">
                        <svg class="w-10 h-10 text-accent" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-primary mb-2">Pesanan Dikonfirmasi</h2>
                    <p class="text-gray-600">Terima kasih sudah berbelanja. Kami telah mengirim struk ke email Anda.</p>
                </div>

                <!-- Order Details -->
                <div class="px-6 pb-6">
                    <div class="bg-gray-50 rounded-xl p-6 mb-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Order ID</p>
                                <p class="text-xl font-bold text-primary">#{{ $transaction->transaction_code }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Total Dibayar
                                </p>
                                <p class="text-2xl font-bold text-accent">Rp
                                    {{ number_format($transaction->total, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Status Progress -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-bold text-primary">Menunggu Verifikasi</h3>
                            <span
                                class="text-xs font-bold text-accent bg-orange-100 px-3 py-1 rounded-full uppercase tracking-wide">Langkah
                                1 dari 3</span>
                        </div>

                        <!-- Progress Bar -->
                        <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                            <div class="bg-gradient-to-r from-accent to-orange-600 h-2 rounded-full" style="width: 33%">
                            </div>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <p class="text-sm text-blue-800">Pesanan bernilai tinggi akan diverifikasi secara manual
                                untuk mencegah fraud. Biasanya memakan waktu kurang dari 2 jam.</p>
                        </div>
                    </div>

                    <!-- Order Information -->
                    <div class="space-y-3 text-sm border-t border-gray-200 pt-6">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Metode Pembayaran</span>
                            <div class="flex items-center gap-2">
                                @if ($transaction->payment_method === 'transfer')
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                        </path>
                                    </svg>
                                    <span class="font-bold text-gray-900">Transfer Bank</span>
                                @else
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                                        </path>
                                    </svg>
                                    <span class="font-bold text-gray-900">QRIS</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Estimasi Pengiriman</span>
                            <span class="font-bold text-gray-900">
                                {{ now()->addDays(3)->format('d M') }} - {{ now()->addDays(7)->format('d M Y') }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Kirim Ke</span>
                            <span
                                class="font-bold text-gray-900 text-right max-w-xs">{{ $transaction->shipping_destination }}</span>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-gray-600">Alamat Lengkap</span>
                            <span class="font-medium text-gray-700 text-right max-w-xs text-xs leading-relaxed">
                                {{ $transaction->shipping_address }}
                            </span>
                        </div>
                    </div>

                    <!-- Products Ordered -->
                    <div class="border-t border-gray-200 mt-6 pt-6">
                        <h3 class="font-bold text-primary mb-4">Produk yang Dipesan</h3>
                        <div class="space-y-3">
                            @foreach ($transaction->details as $detail)
                                <div class="flex gap-3">
                                    <div class="w-16 h-16 bg-gray-100 rounded flex-shrink-0 overflow-hidden">
                                        <img src="{{ $detail->productVariant->photo ?? $detail->productVariant->product->photo ? Storage::url($detail->productVariant->photo ?? $detail->productVariant->product->photo) : 'https://placehold.co/100x100/F5F5F5/999999?text=No+Image' }}"
                                            alt="{{ $detail->productVariant->product->name ?? 'Product' }}"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-bold text-sm text-gray-900">
                                            {{ $detail->productVariant->product->name ?? 'Product' }}</p>
                                        <p class="text-xs text-gray-500">{{ $detail->productVariant->color }} •
                                            {{ $detail->productVariant->size }} •
                                            {{ $detail->productVariant->product->type ?? '' }}</p>
                                        <p class="text-xs text-gray-600 font-medium mt-1">{{ $detail->quantity }}x Rp
                                            {{ number_format($detail->price, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-sm text-primary">Rp
                                            {{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 mt-8 pt-6 border-t border-gray-200">
                        <a href="{{ route('checkout.receipt.download', $transaction->id) }}"
                            class="flex-1 bg-green-600 text-white font-bold py-3 rounded-lg hover:bg-green-700 transition-colors text-center uppercase tracking-wide text-sm flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Download PDF
                        </a>
                        <a href="{{ url('/') }}"
                            class="flex-1 bg-white border-2 border-gray-300 text-gray-700 font-bold py-3 rounded-lg hover:bg-gray-50 transition-colors text-center uppercase tracking-wide text-sm">
                            Kembali ke Toko
                        </a>
                        <a href="{{ route('profile.show') }}"
                            class="flex-1 bg-gradient-to-r from-accent to-orange-600 text-white font-bold py-3 rounded-lg hover:from-accent-light hover:to-orange-500 transition-all shadow-lg shadow-accent/20 text-center uppercase tracking-wide text-sm flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Lihat Pesanan Saya
                        </a>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 py-4 text-center border-t border-gray-200">
                    <p class="text-xs text-gray-500">© {{ date('Y') }} DistroZone. Belanja aman untuk semua.</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
