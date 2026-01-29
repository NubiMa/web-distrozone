<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- 1. Active Order Card (Conditional) -->
            @if ($activeOrder)
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mb-10 slide-up">
                    <div class="h-1 bg-accent w-full"></div>
                    <div class="p-6 md:p-8 flex flex-col md:flex-row gap-8 items-center">
                        <div class="flex-1 w-full">
                            <div class="flex items-center gap-3 mb-2">
                                <span
                                    class="text-xs font-bold px-2 py-0.5 uppercase tracking-wider rounded-sm
                                    {{ $activeOrder->order_status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $activeOrder->order_status == 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $activeOrder->order_status == 'shipped' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                    {{ $activeOrder->order_status == 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $activeOrder->order_status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ strtoupper($activeOrder->order_status) }}
                                </span>
                                <span class="text-gray-400 text-xs">Update terakhir:
                                    {{ $activeOrder->updated_at->diffForHumans() }}</span>
                            </div>

                            <h2 class="text-2xl font-bold text-primary mb-1">Pesanan
                                #{{ $activeOrder->transaction_code }}
                                sedang diproses!</h2>
                            <p class="text-gray-500 mb-6 text-sm">Diperkirakan tiba dalam 3-5 hari kerja.</p>

                            <!-- Progress Bar -->
                            <div class="relative pt-4 pb-2">
                                <div
                                    class="flex mb-2 items-center justify-between text-xs font-bold text-gray-400 uppercase tracking-widest">
                                    <span
                                        class="{{ in_array($activeOrder->order_status, ['pending', 'processing', 'shipped', 'completed']) ? 'text-accent' : '' }}">Proses</span>
                                    <span
                                        class="{{ in_array($activeOrder->order_status, ['processing', 'shipped', 'completed']) ? 'text-accent' : '' }}">Terverifikasi</span>
                                    <span
                                        class="{{ in_array($activeOrder->order_status, ['shipped', 'completed']) ? 'text-accent' : '' }}">Dikirim</span>
                                </div>
                                <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-100">
                                    @php
                                        $width = '10%';
                                        if ($activeOrder->order_status == 'pending') {
                                            $width = '33%';
                                        }
                                        if ($activeOrder->order_status == 'processing') {
                                            $width = '66%';
                                        }
                                        if (in_array($activeOrder->order_status, ['shipped', 'completed'])) {
                                            $width = '100%';
                                        }
                                    @endphp
                                    <div style="width:{{ $width }}"
                                        class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-accent transition-all duration-1000">
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-3 mt-4">
                                <a href="{{ route('orders.show', $activeOrder->id) }}"
                                    class="bg-gray-100 hover:bg-gray-200 text-primary px-6 py-2.5 rounded-sm text-sm font-bold uppercase tracking-wide transition-colors flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 00-1 1v1H7v-3a1 1 0 00-1-1H3">
                                        </path>
                                    </svg>
                                    Lacak Pesanan
                                </a>
                                <a href="{{ route('orders.index') }}"
                                    class="bg-white border border-gray-200 hover:border-gray-400 text-gray-600 px-6 py-2.5 rounded-sm text-sm font-bold uppercase tracking-wide transition-colors flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                        </path>
                                    </svg>
                                    Lihat Semua Pesanan
                                </a>
                            </div>
                        </div>

                        <!-- Mini Product Preview -->
                        <div class="w-32 h-32 md:w-48 md:h-48 bg-gray-100 rounded-lg overflow-hidden relative shrink-0">
                            @if ($activeOrder->details->first())
                                <img src="{{ $activeOrder->details->first()->productVariant->photo ? asset('images/products/variants/' . $activeOrder->details->first()->productVariant->photo) : $activeOrder->details->first()->productVariant->product->photo_url }}"
                                    class="w-full h-full object-cover" alt="Order Preview">
                                <div
                                    class="absolute bottom-2 left-2 bg-white px-2 py-1 text-[10px] font-bold rounded-sm shadow-sm">
                                    {{ $activeOrder->details->count() }} Item
                                </div>
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-200">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- 2. Header & Recommendations -->
            <div class="mb-12">
                <h1 class="text-4xl font-bold font-display text-primary mb-2">Halo,
                    {{ explode(' ', Auth::user()->name)[0] }}</h1>
                <div class="flex justify-between items-end mb-8">
                    <p class="text-gray-500">Koleksi terbaru dipilih khusus untukmu berdasarkan gaya terakhirmu.</p>
                    <a href="{{ url('/products') }}"
                        class="text-accent font-bold text-sm hover:text-accent-light flex items-center gap-1">
                        Lihat Semua Rekomendasi <svg class="w-4 h-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($recommendedProducts as $product)
                        <x-product-card :product="$product" /> <!-- Reusing your component -->
                    @endforeach
                </div>
            </div>

            <!-- 3. Member Exclusive Offers (Static Banner) -->
            <div class="mb-8 flex items-center gap-2">
                <svg class="w-6 h-6 text-accent" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd"></path>
                </svg>
                <h2 class="text-2xl font-bold font-display text-primary">Penawaran Eksklusif Member</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Offer 1 -->
                <div class="relative h-64 rounded-lg overflow-hidden group cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1550614000-4b9519e02d48?q=80&w=1000&auto=format&fit=crop"
                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                        alt="Offer">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-8">
                        <span
                            class="bg-accent text-white text-[10px] font-bold px-2 py-1 uppercase tracking-wider mb-3 inline-block">Member
                            Only</span>
                        <h3 class="text-3xl font-bold text-white mb-2 leading-none">Akses Awal:<br>Midnight Drop</h3>
                        <p class="text-gray-300 text-sm mb-4 max-w-xs">Dapatkan koleksi hitam-hitam edisi terbatas 24
                            jam sebelum publik.</p>
                        <button
                            class="bg-white text-primary px-6 py-2 rounded-sm text-sm font-bold uppercase tracking-wide hover:bg-gray-100 transition-colors">Lihat
                            Koleksi</button>
                    </div>
                </div>

                <!-- Offer 2 -->
                <div class="bg-[#1a1a1a] rounded-lg p-8 relative overflow-hidden flex flex-col justify-center">
                    <div class="absolute right-0 top-1/2 transform -translate-y-1/2 w-1/2 h-full">
                        <img src="https://images.unsplash.com/photo-1549298916-b41d501d3772?q=80&w=1000&auto=format&fit=crop"
                            class="w-full h-full object-cover mask-gradient-left" alt="Shoes">
                        <div class="absolute inset-0 bg-gradient-to-r from-[#1a1a1a] to-transparent"></div>
                    </div>

                    <div class="relative z-10 max-w-[60%]">
                        <h3 class="text-3xl font-bold text-white mb-3 leading-tight">Minggu Poin Ganda</h3>
                        <p class="text-gray-400 text-sm mb-6">Dapatkan 2x DistroPoints untuk semua pembelian sepatu
                            minggu ini.</p>
                        <button
                            class="bg-accent text-white px-6 py-2 rounded-sm text-sm font-bold uppercase tracking-wide hover:bg-accent-light transition-colors shadow-lg shadow-accent/20">
                            Belanja Sepatu
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
