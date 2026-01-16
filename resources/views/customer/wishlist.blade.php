<x-app-layout>
    <x-customer-sidebar active="wishlist">
        <div class="space-y-8">

            <!-- Header Section -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-3xl font-bold font-display text-primary">Wishlist Saya</h1>
                        <span class="text-accent text-lg">💡</span>
                    </div>
                    <p class="text-gray-500 text-sm mt-1">{{ count($wishlistItems) }} item disimpan.</p>
                </div>

                <!-- Move All to Cart Button -->
                @if (count($wishlistItems) > 0)
                    <form action="{{ route('wishlist.moveAll') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="bg-white border border-gray-200 hover:border-accent hover:text-accent text-primary font-bold py-2.5 px-6 rounded-lg shadow-sm transition-all flex items-center gap-2 text-sm uppercase tracking-wide">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Pindahkan Semua ke Keranjang
                        </button>
                    </form>
                @endif
            </div>

            <!-- Cloud/Grid of Items -->
            @if (count($wishlistItems) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($wishlistItems as $product)
                        <div
                            class="bg-white border border-gray-200 rounded-xl p-4 relative group hover:shadow-lg transition-shadow">
                            <!-- Remove Button -->
                            <form action="{{ route('wishlist.destroy', $product->id) }}" method="POST"
                                class="absolute top-4 right-4 z-10">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-8 h-8 flex items-center justify-center bg-white rounded-full text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors shadow-sm border border-gray-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </form>

                            <!-- Image -->
                            <div
                                class="aspect-square bg-gray-50 rounded-lg overflow-hidden mb-4 flex items-center justify-center relative">
                                @if ($product->image)
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                        class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="flex flex-col items-center justify-center text-gray-300">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif

                                <!-- Stock Badge -->
                                <div class="absolute bottom-2 left-2">
                                    @if ($product->stock > 0)
                                        <span
                                            class="bg-black/80 text-white text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider backdrop-blur-sm">In
                                            Stock</span>
                                    @else
                                        <span
                                            class="bg-red-600/90 text-white text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider backdrop-blur-sm">Sold
                                            Out</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Details -->
                            <div>
                                <h3 class="font-bold text-gray-900 line-clamp-1 mb-1">{{ $product->name }}</h3>
                                <p class="text-xs text-gray-500 mb-3">Size: {{ $product->size }} • Color:
                                    {{ $product->color }}</p>

                                <div class="flex items-center justify-between mb-4">
                                    <p class="font-bold text-accent text-lg">Rp
                                        {{ number_format($product->selling_price, 0, ',', '.') }}</p>
                                </div>

                                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full bg-accent hover:bg-accent-light text-white font-bold py-3 rounded-lg uppercase text-xs tracking-wider transition-all shadow-lg shadow-accent/20">
                                        Tambah ke Keranjang
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach

                    <!-- Browse More Card (Visual filler) -->
                    <div
                        class="bg-gray-50 border border-dashed border-gray-300 rounded-xl p-4 flex flex-col items-center justify-center text-center group cursor-pointer hover:bg-white hover:border-accent transition-all min-h-[300px]">
                        <div
                            class="w-12 h-12 rounded-full bg-white border border-gray-200 flex items-center justify-center text-accent mb-3 shadow-sm group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-900">Jelajahi Produk Baru</h3>
                        <p class="text-xs text-gray-500 mt-1">Temukan item favoritmu lainnya</p>
                    </div>

                </div>
            @else
                <!-- Empty State (Matched to Cart Page) -->
                <div class="text-center py-20 border border-dashed border-gray-300 rounded-lg">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Wishlist Anda kosong</h3>
                    <p class="mt-1 text-sm text-gray-500">Mulai simpan item favoritmu sekarang.</p>
                    <div class="mt-6">
                        <a href="{{ url('/products') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-accent hover:bg-accent-light focus:outline-none uppercase tracking-wide">
                            Jelajahi Katalog
                        </a>
                    </div>
            @endif

        </div>
    </x-customer-sidebar>
</x-app-layout>
