<x-guest-layout>
    <div class="bg-white" x-data="productFilters()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold font-display text-primary">All Products</h1>
                    <p class="text-text-muted text-sm mt-1">Showing <span x-text="filteredProducts.length"></span> of
                        <span x-text="allProducts.length"></span> products
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Search Bar -->
                    <div class="relative flex-1 md:w-64">
                        <input type="text" x-model="search" placeholder="Search products..."
                            class="w-full border-border text-sm py-2 pl-10 pr-4 rounded-lg focus:ring-accent focus:border-accent">
                        <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>

                    <!-- Mobile Filter Toggle -->
                    <button @click="mobileFiltersOpen = true"
                        class="md:hidden flex items-center gap-2 text-primary border border-border px-4 py-2 hover:bg-bg-secondary rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                            </path>
                        </svg>
                        Filters
                        <span x-show="getActiveFilterCount() > 0" x-text="getActiveFilterCount()"
                            class="bg-accent text-white text-xs px-2 py-0.5 rounded-full"></span>
                    </button>

                    <!-- Sorting -->
                    <select x-model="sort"
                        class="border-border text-sm py-2 px-4 rounded-lg focus:ring-accent focus:border-accent">
                        <option value="newest">Newest Arrivals</option>
                        <option value="price_low">Price: Low to High</option>
                        <option value="price_high">Price: High to Low</option>
                    </select>
                </div>
            </div>

            <!-- Active Filters Bar -->
            <div x-show="getActiveFilterCount() > 0" class="mb-6 flex items-center gap-2 flex-wrap">
                <span class="text-sm text-gray-600">Active filters:</span>
                <template x-for="brand in selectedBrands" :key="brand">
                    <button @click="toggleBrand(brand)"
                        class="inline-flex items-center gap-1 bg-accent/10 text-accent px-3 py-1 rounded-full text-sm">
                        <span x-text="brand"></span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </template>
                <template x-for="type in selectedTypes" :key="type">
                    <button @click="toggleType(type)"
                        class="inline-flex items-center gap-1 bg-accent/10 text-accent px-3 py-1 rounded-full text-sm">
                        <span x-text="type"></span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </template>
                <template x-for="color in selectedColors" :key="color">
                    <button @click="toggleColor(color)"
                        class="inline-flex items-center gap-1 bg-accent/10 text-accent px-3 py-1 rounded-full text-sm">
                        <span x-text="color"></span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </template>
                <template x-if="selectedPriceRange">
                    <button @click="selectedPriceRange = ''"
                        class="inline-flex items-center gap-1 bg-accent/10 text-accent px-3 py-1 rounded-full text-sm">
                        <span x-text="getPriceRangeLabel(selectedPriceRange)"></span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </template>
                <button @click="clearFilters()" class="text-sm text-gray-500 hover:text-accent underline">
                    Clear all
                </button>
            </div>

            <div class="flex flex-col md:flex-row gap-8">
                <!-- Sidebar Filters (Desktop) -->
                <div class="hidden md:block w-64 shrink-0 space-y-8">
                    <!-- Brand Filter -->
                    <div>
                        <h3 class="font-bold text-primary mb-4">Brands</h3>
                        <div class="space-y-2">
                            <template x-for="brand in allBrands" :key="brand">
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox" :checked="selectedBrands.includes(brand)"
                                        @change="toggleBrand(brand)"
                                        class="rounded border-gray-300 text-accent focus:ring-accent">
                                    <span class="text-sm text-gray-600 group-hover:text-accent transition-colors"
                                        x-text="brand"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <!-- Type Filter -->
                    <div>
                        <h3 class="font-bold text-primary mb-4">Type</h3>
                        <div class="space-y-2">
                            <template x-for="type in allTypes" :key="type">
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox" :checked="selectedTypes.includes(type)"
                                        @change="toggleType(type)"
                                        class="rounded border-gray-300 text-accent focus:ring-accent">
                                    <span
                                        class="text-sm text-gray-600 group-hover:text-accent transition-colors capitalize"
                                        x-text="type"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <!-- Color Filter -->
                    <div>
                        <h3 class="font-bold text-primary mb-4">Colors</h3>
                        <div class="space-y-2">
                            <template x-for="color in allColors" :key="color">
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox" :checked="selectedColors.includes(color)"
                                        @change="toggleColor(color)"
                                        class="rounded border-gray-300 text-accent focus:ring-accent">
                                    <span
                                        class="text-sm text-gray-600 group-hover:text-accent transition-colors capitalize"
                                        x-text="color"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <!-- Price Filter -->
                    <div>
                        <h3 class="font-bold text-primary mb-4">Price</h3>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="price" value="under_100k"
                                    :checked="selectedPriceRange === 'under_100k'"
                                    @change="selectedPriceRange = 'under_100k'"
                                    class="border-gray-300 text-accent focus:ring-accent">
                                <span class="text-sm text-gray-600 group-hover:text-accent transition-colors">Under Rp
                                    100k</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="price" value="100k_200k"
                                    :checked="selectedPriceRange === '100k_200k'"
                                    @change="selectedPriceRange = '100k_200k'"
                                    class="border-gray-300 text-accent focus:ring-accent">
                                <span class="text-sm text-gray-600 group-hover:text-accent transition-colors">Rp 100k -
                                    200k</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="price" value="above_200k"
                                    :checked="selectedPriceRange === 'above_200k'"
                                    @change="selectedPriceRange = 'above_200k'"
                                    class="border-gray-300 text-accent focus:ring-accent">
                                <span class="text-sm text-gray-600 group-hover:text-accent transition-colors">Above Rp
                                    200k</span>
                            </label>
                            <label x-show="selectedPriceRange" class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="price" value=""
                                    @change="selectedPriceRange = ''"
                                    class="border-gray-300 text-accent focus:ring-accent">
                                <span class="text-sm text-gray-600 group-hover:text-accent transition-colors">Any
                                    Price</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Product Grid -->
                <div class="flex-1">
                    <div class="grid grid-cols-2 lg:grid-cols-3 gap-6">
                        <template x-for="product in filteredProducts" :key="product.id">
                            <div
                                class="group relative bg-white border border-border rounded-lg overflow-hidden hover:shadow-lg transition-all">
                                <a :href="'/products/' + product.id">
                                    <div class="aspect-square bg-gray-100 overflow-hidden">
                                        <img :src="product.photo_url" :alt="product.name"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                    </div>
                                    <div class="p-4">
                                        <p class="text-xs text-text-muted uppercase tracking-wide mb-1"
                                            x-text="product.brand"></p>
                                        <h3 class="font-medium text-primary group-hover:text-accent transition-colors mb-2 line-clamp-2"
                                            x-text="product.name"></h3>
                                        <p class="text-accent font-bold" x-text="product.price_range"></p>
                                    </div>
                                </a>
                            </div>
                        </template>
                    </div>

                    <!-- Empty State -->
                    <div x-show="filteredProducts.length === 0" class="py-20 text-center">
                        <p class="text-text-muted">No products found matching your criteria.</p>
                        <button @click="clearFilters()" class="mt-4 text-accent hover:underline">Clear all
                            filters</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Filter Drawer -->
        <div x-show="mobileFiltersOpen" class="fixed inset-0 z-50 flex md:hidden" role="dialog" aria-modal="true"
            x-cloak>
            <div x-show="mobileFiltersOpen" x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-black/50" @click="mobileFiltersOpen = false">
            </div>

            <div x-show="mobileFiltersOpen" x-transition:enter="transition ease-in-out duration-300 transform"
                x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in-out duration-300 transform"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                class="relative max-w-xs w-full bg-white shadow-xl flex flex-col py-4 pb-6 overflow-y-auto">

                <div class="px-4 flex items-center justify-between mb-4">
                    <h2 class="text-lg font-medium text-primary">Filters</h2>
                    <button @click="mobileFiltersOpen = false"
                        class="-mr-2 w-10 h-10 bg-white p-2 rounded-md flex items-center justify-center text-gray-400 hover:text-gray-500">
                        <span class="sr-only">Close menu</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Mobile Filters (same structure as desktop) -->
                <div class="px-4 space-y-6">
                    <!-- Brands -->
                    <div>
                        <h3 class="font-bold text-primary mb-4">Brands</h3>
                        <div class="space-y-2">
                            <template x-for="brand in allBrands" :key="brand">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" :checked="selectedBrands.includes(brand)"
                                        @change="toggleBrand(brand)"
                                        class="rounded border-gray-300 text-accent focus:ring-accent">
                                    <span class="text-sm text-gray-600" x-text="brand"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <!-- Types -->
                    <div>
                        <h3 class="font-bold text-primary mb-4">Type</h3>
                        <div class="space-y-2">
                            <template x-for="type in allTypes" :key="type">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" :checked="selectedTypes.includes(type)"
                                        @change="toggleType(type)"
                                        class="rounded border-gray-300 text-accent focus:ring-accent">
                                    <span class="text-sm text-gray-600 capitalize" x-text="type"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <!-- Colors -->
                    <div>
                        <h3 class="font-bold text-primary mb-4">Colors</h3>
                        <div class="space-y-2">
                            <template x-for="color in allColors" :key="color">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" :checked="selectedColors.includes(color)"
                                        @change="toggleColor(color)"
                                        class="rounded border-gray-300 text-accent focus:ring-accent">
                                    <span class="text-sm text-gray-600 capitalize" x-text="color"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <!-- Price -->
                    <div>
                        <h3 class="font-bold text-primary mb-4">Price</h3>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="price_mobile" value="under_100k"
                                    :checked="selectedPriceRange === 'under_100k'"
                                    @change="selectedPriceRange = 'under_100k'"
                                    class="border-gray-300 text-accent focus:ring-accent">
                                <span class="text-sm text-gray-600">Under Rp 100k</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="price_mobile" value="100k_200k"
                                    :checked="selectedPriceRange === '100k_200k'"
                                    @change="selectedPriceRange = '100k_200k'"
                                    class="border-gray-300 text-accent focus:ring-accent">
                                <span class="text-sm text-gray-600">Rp 100k - 200k</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="price_mobile" value="above_200k"
                                    :checked="selectedPriceRange === 'above_200k'"
                                    @change="selectedPriceRange = 'above_200k'"
                                    class="border-gray-300 text-accent focus:ring-accent">
                                <span class="text-sm text-gray-600">Above Rp 200k</span>
                            </label>
                            <label x-show="selectedPriceRange" class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="price_mobile" value=""
                                    @change="selectedPriceRange = ''"
                                    class="border-gray-300 text-accent focus:ring-accent">
                                <span class="text-sm text-gray-600">Any Price</span>
                            </label>
                        </div>
                    </div>

                    <!-- Apply Filters Button (Mobile) -->
                    <div class="pt-4 border-t">
                        <button @click="mobileFiltersOpen = false"
                            class="w-full bg-accent text-white py-3 rounded-lg font-medium hover:bg-accent/90 transition">
                            View <span x-text="filteredProducts.length"></span> Results
                        </button>
                        <button @click="clearFilters(); mobileFiltersOpen = false"
                            class="w-full mt-2 border border-gray-300 text-gray-700 py-3 rounded-lg font-medium hover:bg-gray-50 transition">
                            Clear All Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function productFilters() {
            return {
                // All products from server
                allProducts: @json($productsData),

                // Filter options
                allBrands: @json($allBrands),
                allTypes: @json($allTypes),
                allColors: @json($allColors),

                // Selected filters
                selectedBrands: [],
                selectedTypes: [],
                selectedColors: [],
                selectedPriceRange: '',
                search: '',
                sort: 'newest',

                // UI state
                mobileFiltersOpen: false,

                // Computed: filtered products
                get filteredProducts() {
                    let products = this.allProducts;

                    // Apply search filter
                    if (this.search) {
                        const searchLower = this.search.toLowerCase();
                        products = products.filter(p =>
                            p.name.toLowerCase().includes(searchLower) ||
                            p.brand.toLowerCase().includes(searchLower)
                        );
                    }

                    // Apply brand filter
                    if (this.selectedBrands.length > 0) {
                        products = products.filter(p => this.selectedBrands.includes(p.brand));
                    }

                    // Apply type filter
                    if (this.selectedTypes.length > 0) {
                        products = products.filter(p => this.selectedTypes.includes(p.type));
                    }

                    // Apply color filter
                    if (this.selectedColors.length > 0) {
                        products = products.filter(p =>
                            p.colors.some(color => this.selectedColors.includes(color))
                        );
                    }

                    // Apply price range filter
                    if (this.selectedPriceRange) {
                        products = products.filter(p => {
                            const minPrice = p.min_price;
                            const maxPrice = p.max_price;

                            switch (this.selectedPriceRange) {
                                case 'under_100k':
                                    return minPrice < 100000;
                                case '100k_200k':
                                    return maxPrice >= 100000 && minPrice <= 200000;
                                case 'above_200k':
                                    return maxPrice > 200000;
                                default:
                                    return true;
                            }
                        });
                    }

                    // Apply sorting
                    products = [...products]; // Clone array before sorting
                    switch (this.sort) {
                        case 'price_low':
                            products.sort((a, b) => a.min_price - b.min_price);
                            break;
                        case 'price_high':
                            products.sort((a, b) => b.max_price - a.max_price);
                            break;
                        case 'newest':
                        default:
                            // Already sorted by latest from server
                            break;
                    }

                    return products;
                },

                toggleBrand(brand) {
                    const index = this.selectedBrands.indexOf(brand);
                    if (index > -1) {
                        this.selectedBrands.splice(index, 1);
                    } else {
                        this.selectedBrands.push(brand);
                    }
                },

                toggleType(type) {
                    const index = this.selectedTypes.indexOf(type);
                    if (index > -1) {
                        this.selectedTypes.splice(index, 1);
                    } else {
                        this.selectedTypes.push(type);
                    }
                },

                toggleColor(color) {
                    const index = this.selectedColors.indexOf(color);
                    if (index > -1) {
                        this.selectedColors.splice(index, 1);
                    } else {
                        this.selectedColors.push(color);
                    }
                },

                clearFilters() {
                    this.selectedBrands = [];
                    this.selectedTypes = [];
                    this.selectedColors = [];
                    this.selectedPriceRange = '';
                    this.search = '';
                    this.sort = 'newest';
                },

                getActiveFilterCount() {
                    return this.selectedBrands.length + this.selectedTypes.length +
                        this.selectedColors.length + (this.selectedPriceRange ? 1 : 0);
                },

                getPriceRangeLabel(range) {
                    switch (range) {
                        case 'under_100k':
                            return 'Under Rp 100k';
                        case '100k_200k':
                            return 'Rp 100k - 200k';
                        case 'above_200k':
                            return 'Above Rp 200k';
                        default:
                            return '';
                    }
                }
            }
        }
    </script>
</x-guest-layout>
