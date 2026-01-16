<x-guest-layout>
    <div class="bg-white" x-data="{ mobileFiltersOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold font-display text-primary">All Products</h1>
                    <p class="text-text-muted text-sm mt-1">Showing {{ $products->count() }} results</p>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Mobile Filter Toggle -->
                    <button @click="mobileFiltersOpen = true"
                        class="md:hidden flex items-center gap-2 text-primary border border-border px-4 py-2 hover:bg-bg-secondary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                            </path>
                        </svg>
                        Filters
                    </button>

                    <!-- Sorting -->
                    <select class="border-border text-sm py-2 px-4 focus:ring-accent focus:border-accent">
                        <option>Newest Arrivals</option>
                        <option>Price: Low to High</option>
                        <option>Price: High to Low</option>
                        <option>Best Selling</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-8">
                <!-- Sidebar Filters (Desktop) -->
                <div class="hidden md:block w-64 flex-shrink-0 space-y-8">
                    <!-- Brand Filter -->
                    <div>
                        <h3 class="font-bold text-primary mb-4">Brands</h3>
                        <div class="space-y-2">
                            @foreach (['Nike', 'Adidas', 'Uniqlo', 'H&M'] as $brand)
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox"
                                        class="rounded border-gray-300 text-accent focus:ring-accent">
                                    <span
                                        class="text-sm text-gray-600 group-hover:text-accent transition-colors">{{ $brand }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Type Filter -->
                    <div>
                        <h3 class="font-bold text-primary mb-4">Type</h3>
                        <div class="space-y-2">
                            @foreach (['T-Shirt', 'Shorts', 'Shirt', 'Accessories'] as $type)
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox"
                                        class="rounded border-gray-300 text-accent focus:ring-accent">
                                    <span
                                        class="text-sm text-gray-600 group-hover:text-accent transition-colors">{{ $type }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Filter -->
                    <div>
                        <h3 class="font-bold text-primary mb-4">Price</h3>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="price"
                                    class="border-gray-300 text-accent focus:ring-accent">
                                <span class="text-sm text-gray-600 group-hover:text-accent transition-colors">Under Rp
                                    100k</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="price"
                                    class="border-gray-300 text-accent focus:ring-accent">
                                <span class="text-sm text-gray-600 group-hover:text-accent transition-colors">Rp 100k -
                                    200k</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="price"
                                    class="border-gray-300 text-accent focus:ring-accent">
                                <span class="text-sm text-gray-600 group-hover:text-accent transition-colors">Above Rp
                                    200k</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Product Grid -->
                <div class="flex-1">
                    <div class="grid grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($products as $product)
                            <x-product-card :product="$product" />
                        @empty
                            <div class="col-span-full py-20 text-center">
                                <p class="text-text-muted">No products found matching your criteria.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="mt-12">
                        <!-- Custom pagination component would go here -->
                        {{-- {{ $products->links() }} --}}
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Filter Drawer -->
        <div x-show="mobileFiltersOpen" class="fixed inset-0 z-50 flex md:hidden" role="dialog" aria-modal="true"
            x-cloak>
            <div x-show="mobileFiltersOpen" x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/50" @click="mobileFiltersOpen = false">
            </div>

            <div x-show="mobileFiltersOpen" x-transition:enter="transition ease-in-out duration-300 transform"
                x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in-out duration-300 transform"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                class="relative max-w-xs w-full bg-white shadow-xl flex flex-col py-4 pb-6 overflow-y-auto">

                <div class="px-4 flex items-center justify-between">
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

                <!-- Resuable Mobile Filters (Same structure as desktop) -->
                <div class="mt-4 px-4 border-t border-gray-200 pt-4 space-y-6">
                    <!-- Brands -->
                    <div>
                        <h3 class="font-bold text-primary mb-4">Brands</h3>
                        <div class="space-y-2">
                            @foreach (['Nike', 'Adidas', 'Uniqlo', 'H&M'] as $brand)
                                <label class="flex items-center gap-2">
                                    <input type="checkbox"
                                        class="rounded border-gray-300 text-accent focus:ring-accent">
                                    <span class="text-sm text-gray-600">{{ $brand }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
