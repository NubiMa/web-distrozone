<x-guest-layout>
    <div class="bg-white py-12 md:py-20" x-data="{
        selectedSize: '{{ $availableSizes->first() ?? '' }}',
        selectedColor: '{{ $availableColors->first() ?? '' }}',
        variants: {{ $product->variants->toJson() }},
        quantity: 1,
    
        get selectedVariant() {
            return this.variants.find(v => v.size === this.selectedSize && v.color === this.selectedColor);
        },
    
        get currentPrice() {
            return this.selectedVariant ? this.selectedVariant.price : {{ $product->base_price ?? 0 }};
        },
    
        get currentStock() {
            return this.selectedVariant ? this.selectedVariant.stock : 0;
        },
    
        get variantId() {
            return this.selectedVariant ? this.selectedVariant.id : null;
        },
    
        availableSizesForColor(color) {
            return [...new Set(this.variants.filter(v => v.color === color).map(v => v.size))];
        },
    
        availableColorsForSize(size) {
            return [...new Set(this.variants.filter(v => v.size === size).map(v => v.color))];
        },
    
        isVariantAvailable(size, color) {
            return this.variants.some(v => v.size === size && v.color === color && v.stock > 0);
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-20">

                <!-- Image Gallery -->
                <div class="space-y-4">
                    <div class="aspect-[4/5] bg-bg-secondary w-full overflow-hidden border border-border group relative">
                        <img :src="selectedVariant && selectedVariant.photo ? '/storage/' + selectedVariant.photo :
                            '{{ $product->photo ? Storage::url($product->photo) : 'https://placehold.co/400x500/F5F5F5/111111?text=No+Image' }}'"
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
                            <p class="text-2xl font-bold text-accent"
                                x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(currentPrice)"></p>
                            <span x-show="currentStock > 0"
                                class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded font-bold mb-1">In
                                Stock</span>
                            <span x-show="currentStock <= 0"
                                class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded font-bold mb-1">Out of
                                Stock</span>
                        </div>
                    </div>

                    <div class="prose prose-sm text-gray-600 mb-8 font-body">
                        <p>{{ $product->description }}</p>
                    </div>

                    <!-- Variant Selectors -->
                    <div class="space-y-6 mb-8">
                        <!-- Size Selector -->
                        <div>
                            <h3 class="font-bold text-primary mb-3 text-sm uppercase">Size</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($availableSizes as $size)
                                    <button type="button" @click="selectedSize = '{{ $size }}'"
                                        :class="{
                                            'border-2 border-primary bg-primary text-white': selectedSize === '{{ $size }}',
                                            'border border-border text-gray-700 hover:border-primary': selectedSize !== '{{ $size }}' &&
                                                isVariantAvailable('{{ $size }}', selectedColor),
                                            'border border-gray-200 text-gray-300 cursor-not-allowed': !
                                                isVariantAvailable('{{ $size }}', selectedColor)
                                        }"
                                        :disabled="!isVariantAvailable('{{ $size }}', selectedColor)"
                                        class="w-12 h-12 flex items-center justify-center font-bold text-sm transition-all">
                                        {{ $size }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Color Selector -->
                        <div>
                            <h3 class="font-bold text-primary mb-3 text-sm uppercase">Color</h3>
                            <div class="flex flex-wrap items-center gap-3">
                                @foreach ($availableColors as $color)
                                    <button type="button" @click="selectedColor = '{{ $color }}'"
                                        :disabled="!isVariantAvailable(selectedSize, '{{ $color }}')"
                                        class="group flex items-center gap-2 transition-opacity"
                                        :class="!isVariantAvailable(selectedSize, '{{ $color }}') ?
                                            'opacity-40 cursor-not-allowed' : 'cursor-pointer'">
                                        <span class="w-8 h-8 rounded-full border-2 transition-all relative"
                                            :class="selectedColor === '{{ $color }}' ?
                                                'border-primary ring-2 ring-primary ring-offset-2' : 'border-gray-300'"
                                            style="background-color: {{ strtolower($color) }}">
                                            <span x-show="!isVariantAvailable(selectedSize, '{{ $color }}')"
                                                class="absolute inset-0 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </span>
                                        </span>
                                        <span
                                            class="text-sm font-medium text-gray-700 capitalize group-hover:text-primary transition-colors">{{ $color }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Quantity & Add to Cart -->
                    <div class="flex gap-4">
                        <div class="w-24 border border-border flex items-center">
                            <button type="button" @click="quantity > 1 ? quantity-- : null"
                                class="w-8 h-full flex items-center justify-center text-gray-500 hover:text-primary transition-colors hover:bg-gray-100">-</button>
                            <input type="text" x-model="quantity"
                                class="w-full h-full text-center border-none p-0 focus:ring-0 text-sm font-bold"
                                readonly>
                            <button type="button" @click="quantity < currentStock ? quantity++ : null"
                                class="w-8 h-full flex items-center justify-center text-gray-500 hover:text-primary transition-colors hover:bg-gray-100">+</button>
                        </div>

                        @auth
                            <!-- Add to Cart Form -->
                            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="quantity" :value="quantity">
                                <input type="hidden" name="variant_id" :value="variantId">
                                <button type="submit" :disabled="currentStock <= 0"
                                    class="w-full bg-gradient-primary text-white font-bold tracking-wide uppercase py-4 hover:opacity-90 transition-opacity disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-accent/20"
                                    x-text="currentStock > 0 ? 'Add to Cart' : 'Sold Out'">
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
                                class="flex-1 bg-gradient-primary text-white font-bold tracking-wide uppercase py-4 hover:opacity-90 transition-opacity text-center flex items-center justify-center shadow-lg shadow-accent/20"
                                x-text="currentStock > 0 ? 'Login to Buy' : 'Sold Out'">
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
                            <span>Free shipping on orders over Rp 300.000</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            <span>30 days return policy</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
