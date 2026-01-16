<x-guest-layout>
    <div class="bg-white py-12 md:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-20">

                <!-- Image Gallery -->
                <div class="space-y-4">
                    <div class="aspect-[4/5] bg-bg-secondary w-full overflow-hidden border border-border group relative">
                        <img src="{{ $product->image ? Storage::url($product->image) : 'https://placehold.co/400x500/F5F5F5/111111?text=No+Image' }}"
                            alt="{{ $product->name }}"
                            class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700">
                    </div>
                </div>

                <!-- Product Info -->
                <div class="flex flex-col">
                    <div class="border-b border-border pb-6 mb-6">
                        <p class="text-sm text-accent font-bold tracking-widest uppercase mb-2">{{ $product->brand }}
                        </p>
                        <h1 class="text-3xl md:text-4xl font-bold font-display text-primary mb-4">{{ $product->name }}
                        </h1>
                        <div class="flex items-end gap-4">
                            <p class="text-2xl font-bold text-accent">Rp
                                {{ number_format($product->selling_price, 0, ',', '.') }}</p>
                            @if ($product->stock > 0)
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded font-bold mb-1">In
                                    Stock</span>
                            @else
                                <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded font-bold mb-1">Out of
                                    Stock</span>
                            @endif
                        </div>
                    </div>

                    <div class="prose prose-sm text-gray-600 mb-8 font-body">
                        <p>{{ $product->description }}</p>
                    </div>

                    <!-- Selectors -->
                    <div class="space-y-6 mb-8">
                        <!-- Size -->
                        <div>
                            <h3 class="font-bold text-primary mb-3 text-sm uppercase">Size</h3>
                            <div class="flex flex-wrap gap-2">
                                <span
                                    class="w-12 h-12 flex items-center justify-center border-2 border-primary bg-primary text-white font-bold cursor-pointer transition-colors">{{ $product->size }}</span>
                                <!-- Simulated other sizes for design -->
                                {{-- @foreach (['S', 'M', 'L', 'XL'] as $size)
                                    @if ($size !== $product->size)
                                    <span class="w-12 h-12 flex items-center justify-center border border-border text-gray-400 font-bold cursor-not-allowed opacity-50">{{ $size }}</span>
                                    @endif
                                @endforeach --}}
                            </div>
                        </div>

                        <!-- Color -->
                        <div>
                            <h3 class="font-bold text-primary mb-3 text-sm uppercase">Color</h3>
                            <div class="flex items-center gap-2">
                                <span class="w-8 h-8 rounded-full border border-gray-300 relative shadow-sm"
                                    style="background-color: {{ strtolower($product->color) }}"></span>
                                <span class="text-sm font-medium text-gray-600 capitalize">{{ $product->color }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quantity & Add to Cart -->
                    <div class="flex gap-4" x-data="{ qty: 1 }">
                        <div class="w-24 border border-border flex items-center">
                            <button type="button" @click="qty > 1 ? qty-- : null"
                                class="w-8 h-full flex items-center justify-center text-gray-500 hover:text-primary transition-colors hover:bg-gray-100">-</button>
                            <input type="text" x-model="qty"
                                class="w-full h-full text-center border-none p-0 focus:ring-0 text-sm font-bold"
                                readonly>
                            <button type="button" @click="qty < {{ $product->stock }} ? qty++ : null"
                                class="w-8 h-full flex items-center justify-center text-gray-500 hover:text-primary transition-colors hover:bg-gray-100">+</button>
                        </div>

                        @auth
                            <!-- Add to Cart Form -->
                            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="quantity" :value="qty">
                                <button type="submit"
                                    class="w-full bg-gradient-primary text-white font-bold tracking-wide uppercase py-4 hover:opacity-90 transition-opacity disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-accent/20"
                                    {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                    {{ $product->stock > 0 ? 'Add to Cart' : 'Sold Out' }}
                                </button>
                            </form>
                            <!-- Wishlist Button (Auth) -->
                            <form action="{{ route('wishlist.store') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit"
                                    class="w-14 h-full border border-border flex items-center justify-center text-gray-400 hover:text-accent hover:border-accent transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                        </path>
                                    </svg>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}"
                                class="flex-1 bg-gradient-primary text-white font-bold tracking-wide uppercase py-4 hover:opacity-90 transition-opacity text-center flex items-center justify-center shadow-lg shadow-accent/20">
                                {{ $product->stock > 0 ? 'Login to Buy' : 'Sold Out' }}
                            </a>
                            <!-- Wishlist Button (Guest) -->
                            <a href="{{ route('login') }}"
                                class="w-14 border border-border flex items-center justify-center text-gray-400 hover:text-accent hover:border-accent transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                    </path>
                                </svg>
                            </a>
                        @endauth
                    </div>

                    <div class="mt-8 pt-8 border-t border-border space-y-4">
                        <div class="flex items-center gap-3 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Free shipping on orders over Rp 300.000</span> <!-- Optional marketing text -->
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            <span>30 days return policy</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products (Backend logic pending) -->
            {{-- <div class="mt-20">
                <h2 class="text-2xl font-bold font-display text-primary mb-8">YOU MIGHT ALSO LIKE</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                     <x-product-card :product="$product" />
                </div>
            </div> --}}
        </div>
    </div>
</x-guest-layout>
