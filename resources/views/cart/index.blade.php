<x-app-layout>
    <x-customer-sidebar active="cart">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-end gap-4 mb-4">
                <h1 class="text-3xl font-bold font-display text-primary uppercase tracking-wide">Keranjang Belanja</h1>
                <span class="text-gray-500 text-lg mb-1 font-medium">({{ count($cart) }} Item)</span>
            </div>

            @if (count($cart) > 0)
                <div class="flex flex-col xl:flex-row gap-8">

                    <!-- Main Content (Cart Items) -->
                    <div class="flex-1 space-y-6">

                        <!-- Shipping Optimizer Banner -->
                        <div
                            class="bg-orange-50 border border-orange-100 rounded-xl p-5 flex items-center gap-4 shadow-sm">
                            <div
                                class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center text-accent flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-orange-900 text-sm md:text-base">Optimasi Ongkir: 1kg = 3 Kaos
                                </h3>
                                <p class="text-xs md:text-sm text-orange-700 mt-1">Estimasi berat saat ini: <span
                                        class="font-bold">0.{{ 2 * count($cart) }}kg</span>. Tambah item lagi!</p>
                            </div>
                            <a href="{{ url('/products') }}"
                                class="ml-auto text-sm font-bold text-accent hover:text-accent-light whitespace-nowrap flex items-center gap-1">
                                Tambah <span class="hidden sm:inline">Item</span> &rarr;
                            </a>
                        </div>

                        <!-- Cart Items List -->
                        <div class="space-y-4">
                            @foreach ($cart as $id => $details)
                                <div
                                    class="bg-white border border-gray-200 rounded-xl p-4 sm:p-6 flex flex-col sm:flex-row gap-6 relative group hover:border-accent/30 transition-colors shadow-sm">

                                    <!-- Delete Button -->
                                    <form action="{{ route('cart.remove') }}" method="POST"
                                        class="absolute top-4 right-4 z-10">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <button type="submit"
                                            class="text-gray-400 hover:text-red-500 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>

                                    <!-- Image -->
                                    <div
                                        class="w-full sm:w-28 aspect-[4/5] bg-gray-50 rounded-lg overflow-hidden flex-shrink-0 border border-gray-100">
                                        <img src="{{ $details['image'] ? Storage::url($details['image']) : 'https://placehold.co/400x500/F5F5F5/111111?text=No+Image' }}"
                                            alt="{{ $details['name'] }}"
                                            class="w-full h-full object-cover object-center">
                                    </div>

                                    <!-- Details -->
                                    <div class="flex-1 flex flex-col justify-between">
                                        <div class="pr-8">
                                            <span
                                                class="inline-block px-2 py-0.5 bg-gray-100 text-gray-500 text-[10px] font-bold uppercase tracking-wider rounded mb-2">Streetwear
                                                Collection</span>
                                            <h3 class="text-lg font-bold text-primary mb-1">
                                                <a href="{{ url('/products/' . $details['id']) }}"
                                                    class="hover:text-accent transition-colors">{{ $details['name'] }}</a>
                                            </h3>
                                            <div class="flex items-center gap-3 text-sm text-gray-500 mb-4">
                                                <span>Size: L</span>
                                                <span class="text-gray-300">|</span>
                                                <span>Color: Black</span>
                                            </div>
                                        </div>

                                        <div class="flex items-end justify-between mt-auto">
                                            <!-- Quantity -->
                                            <form action="{{ route('cart.update') }}" method="POST"
                                                class="flex items-center border border-gray-200 rounded-lg h-9 w-28 bg-gray-50">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $id }}">
                                                <button type="button"
                                                    onclick="this.nextElementSibling.stepDown(); this.nextElementSibling.form.submit()"
                                                    class="w-8 h-full flex items-center justify-center text-gray-500 hover:bg-gray-100 hover:text-primary rounded-l-lg transition-colors font-bold">-</button>
                                                <input type="number" name="quantity"
                                                    value="{{ $details['quantity'] }}" min="1" max="5"
                                                    class="w-full h-full text-center border-none p-0 focus:ring-0 text-sm font-bold text-primary appearance-none bg-transparent"
                                                    onchange="this.form.submit()">
                                                <button type="button"
                                                    onclick="this.previousElementSibling.stepUp(); this.previousElementSibling.form.submit()"
                                                    class="w-8 h-full flex items-center justify-center text-gray-500 hover:bg-gray-100 hover:text-primary rounded-r-lg transition-colors font-bold">+</button>
                                            </form>

                                            <!-- Price -->
                                            <p class="font-bold text-lg text-primary">Rp
                                                {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Sidebar (Order Summary) -->
                    <div class="w-full xl:w-80 flex-shrink-0">
                        <div class="bg-white border border-gray-200 rounded-xl p-6 sticky top-24 shadow-sm">
                            <h2 class="text-lg font-bold font-display text-primary mb-5">Ringkasan Pesanan</h2>

                            <div class="space-y-3 mb-5 pb-5 border-b border-gray-100">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="font-bold text-primary">Rp
                                        {{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Pengiriman</span>
                                    <span class="text-gray-500 text-xs italic">Cek saat checkout</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Pajak (11%)</span>
                                    <span class="text-gray-500 text-xs">Termasuk</span>
                                </div>
                            </div>

                            <!-- Promo Code -->
                            <div class="mb-5">
                                <label
                                    class="block text-[10px] font-bold text-gray-500 uppercase tracking-wide mb-2">Kode
                                    Promo</label>
                                <div class="flex gap-2">
                                    <input type="text" placeholder="KODE"
                                        class="flex-1 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-accent focus:bg-white transition-colors uppercase">
                                    <button
                                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold px-3 py-2 rounded-lg text-xs transition-colors">OK</button>
                                </div>
                            </div>

                            <!-- Total -->
                            <div class="flex justify-between items-end mb-6">
                                <span class="font-bold text-base text-primary">Total</span>
                                <div class="text-right">
                                    <p class="font-bold text-2xl text-accent leading-none">Rp
                                        {{ number_format($total, 0, ',', '.') }}</p>
                                </div>
                            </div>

                            <a href="{{ url('/checkout') }}"
                                class="block w-full bg-accent hover:bg-accent-light text-white text-center py-3.5 rounded-lg font-bold uppercase tracking-wider text-sm shadow-lg shadow-accent/20 transition-transform transform hover:-translate-y-0.5">
                                Lanjut Pembayaran
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-20 border border-dashed border-gray-300 rounded-lg">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Keranjang Belanja Kosong</h3>
                    <p class="mt-1 text-sm text-gray-500">Sepertinya kamu belum menambahkan apapun. Yuk mulai
                        eksplorasi!</p>
                    <div class="mt-6">
                        <a href="{{ url('/products') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-accent hover:bg-accent-light focus:outline-none uppercase tracking-wide">
                            Mulai Belanja
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </x-customer-sidebar>
</x-app-layout>
