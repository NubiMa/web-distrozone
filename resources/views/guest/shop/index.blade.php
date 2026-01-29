<x-guest-layout>
    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex text-sm">
                <a href="/" class="text-gray-500 hover:text-[#FF6B00] transition-colors">Home</a>
                <span class="mx-2 text-gray-400">/</span>
                <span class="text-gray-900 font-medium">Shop All</span>
            </nav>
        </div>
    </div>

    <div class="bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <form action="{{ route('products.index') }}" method="GET" id="filterForm">
                @if (request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                <div class="flex flex-col lg:flex-row gap-8">
                    {{-- Filter Sidebar --}}
                    <aside class="lg:w-64 flex-shrink-0">
                        <div class="sticky top-24 space-y-6">
                            {{-- Filter Header --}}
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-bold text-[#111111]">Filters</h2>
                                @if (request()->anyFilled(['types', 'brands', 'sizes', 'colors', 'price']))
                                    <a href="{{ route('products.index') }}"
                                        class="text-sm text-[#FF6B00] hover:underline">
                                        Clear All
                                    </a>
                                @endif
                            </div>

                            {{-- TYPE Filter --}}
                            @if ($availableTypes->isNotEmpty())
                                <div class="border-b border-gray-200 pb-6">
                                    <h3 class="text-sm font-bold text-[#111111] mb-4 uppercase tracking-wide">TYPE</h3>
                                    <div class="space-y-3">
                                        @foreach ($availableTypes as $type)
                                            <label class="flex items-center cursor-pointer group">
                                                <input type="checkbox" name="types[]" value="{{ $type }}"
                                                    {{ in_array($type, request('types', [])) ? 'checked' : '' }}
                                                    onchange="this.form.submit()"
                                                    class="w-4 h-4 rounded border-gray-300 text-[#FF6B00] focus:ring-[#FF6B00]">
                                                <span
                                                    class="ml-3 text-sm text-gray-700 group-hover:text-[#FF6B00] transition-colors">
                                                    {{ ucfirst($type) }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- SIZE Filter --}}
                            @if ($availableSizes->isNotEmpty())
                                <div class="border-b border-gray-200 pb-6">
                                    <h3 class="text-sm font-bold text-[#111111] mb-4 uppercase tracking-wide">SIZE</h3>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($availableSizes as $size)
                                            <label class="cursor-pointer">
                                                <input type="checkbox" name="sizes[]" value="{{ $size }}"
                                                    {{ in_array($size, request('sizes', [])) ? 'checked' : '' }}
                                                    onchange="this.form.submit()" class="peer sr-only">
                                                <div
                                                    class="px-4 py-2 border border-gray-300 rounded text-sm font-medium text-gray-700
                                                peer-checked:border-[#FF6B00] peer-checked:bg-[#FF6B00] peer-checked:text-white
                                                hover:border-[#FF6B00] transition-all">
                                                    {{ strtoupper($size) }}
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- COLOR Filter --}}
                            @if ($availableColors->isNotEmpty())
                                <div class="border-b border-gray-200 pb-6">
                                    <h3 class="text-sm font-bold text-[#111111] mb-4 uppercase tracking-wide">COLOR</h3>
                                    <div class="flex flex-wrap gap-3">
                                        @foreach ($availableColors as $color)
                                            <label class="cursor-pointer group relative">
                                                <input type="checkbox" name="colors[]" value="{{ $color }}"
                                                    {{ in_array($color, request('colors', [])) ? 'checked' : '' }}
                                                    onchange="this.form.submit()" class="peer sr-only">
                                                @php
                                                    $colorMap = [
                                                        'Black' => '#000000',
                                                        'White' => '#FFFFFF',
                                                        'Red' => '#DC2626',
                                                        'Blue' => '#2563EB',
                                                        'Green' => '#16A34A',
                                                        'Orange' => '#FF6B00',
                                                        'Navy' => '#1E3A8A',
                                                        'Grey' => '#6B7280',
                                                        'Gray' => '#6B7280',
                                                    ];
                                                    $bgColor = $colorMap[$color] ?? '#E5E7EB';
                                                @endphp
                                                <div class="w-8 h-8 rounded-full border-2 transition-all
                                                {{ $color === 'White' ? 'border-gray-300' : 'border-transparent' }}
                                                peer-checked:ring-2 peer-checked:ring-[#FF6B00] peer-checked:ring-offset-2
                                                hover:scale-110"
                                                    style="background-color: {{ $bgColor }}">
                                                </div>
                                                <div
                                                    class="absolute -bottom-6 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <span
                                                        class="text-xs text-gray-600 whitespace-nowrap">{{ $color }}</span>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- PRICE RANGE Filter --}}
                            <div class="pb-6">
                                <h3 class="text-sm font-bold text-[#111111] mb-4 uppercase tracking-wide">PRICE RANGE
                                </h3>
                                <div class="space-y-3">
                                    <label class="flex items-center cursor-pointer group">
                                        <input type="radio" name="price" value="low"
                                            {{ request('price') == 'low' ? 'checked' : '' }}
                                            onchange="this.form.submit()"
                                            class="w-4 h-4 border-gray-300 text-[#FF6B00] focus:ring-[#FF6B00]">
                                        <span
                                            class="ml-3 text-sm text-gray-700 group-hover:text-[#FF6B00] transition-colors">
                                            Under Rp 100k
                                        </span>
                                    </label>
                                    <label class="flex items-center cursor-pointer group">
                                        <input type="radio" name="price" value="mid"
                                            {{ request('price') == 'mid' ? 'checked' : '' }}
                                            onchange="this.form.submit()"
                                            class="w-4 h-4 border-gray-300 text-[#FF6B00] focus:ring-[#FF6B00]">
                                        <span
                                            class="ml-3 text-sm text-gray-700 group-hover:text-[#FF6B00] transition-colors">
                                            Rp 100k - 200k
                                        </span>
                                    </label>
                                    <label class="flex items-center cursor-pointer group">
                                        <input type="radio" name="price" value="high"
                                            {{ request('price') == 'high' ? 'checked' : '' }}
                                            onchange="this.form.submit()"
                                            class="w-4 h-4 border-gray-300 text-[#FF6B00] focus:ring-[#FF6B00]">
                                        <span
                                            class="ml-3 text-sm text-gray-700 group-hover:text-[#FF6B00] transition-colors">
                                            Above Rp 200k
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </aside>

                    {{-- Product Grid --}}
                    <main class="flex-1">
                        {{-- Header with title and sort --}}
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h1 class="text-2xl font-bold text-[#111111]">
                                    New Arrivals
                                    <span class="text-gray-400 text-lg font-normal">({{ $products->total() }})</span>
                                </h1>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-gray-600">Sort by:</span>
                                <select name="sort" onchange="this.form.submit()"
                                    class="text-sm border-gray-300 rounded-md focus:border-[#FF6B00] focus:ring-[#FF6B00]">
                                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest
                                        Drops</option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                                        Price: Low to High</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                                        Price: High to Low</option>
                                    <option value="best_selling"
                                        {{ request('sort') == 'best_selling' ? 'selected' : '' }}>Best Selling</option>
                                </select>
                            </div>
                        </div>

                        {{-- Product Grid --}}
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @forelse($products as $product)
                                <div
                                    class="group bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                    {{-- Product Image --}}
                                    <div class="relative aspect-[4/5] overflow-hidden bg-gray-100">
                                        <a href="{{ url('/products/' . $product->id) }}">
                                            <img src="{{ $product->photo_url }}" alt="{{ $product->name }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        </a>

                                        {{-- Badges --}}
                                        @php
                                            $isNew = $product->created_at->diffInDays(now()) < 7;
                                            $totalStock = $product->total_stock;
                                        @endphp
                                        @if ($isNew)
                                            <span
                                                class="absolute top-2 left-2 px-2 py-1 bg-[#FF6B00] text-white text-xs font-bold uppercase tracking-wider">
                                                NEW
                                            </span>
                                        @elseif($totalStock <= 0)
                                            <span
                                                class="absolute top-2 left-2 px-2 py-1 bg-gray-800 text-white text-xs font-bold uppercase tracking-wider">
                                                SOLD OUT
                                            </span>
                                        @endif

                                        {{-- Quick Add to Cart Button (hover) --}}
                                        <button
                                            class="absolute bottom-4 right-4 w-10 h-10 bg-[#0F0F0F] text-white rounded-full 
                                            flex items-center justify-center opacity-0 group-hover:opacity-100 
                                            hover:bg-[#FF6B00] transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </button>
                                    </div>

                                    {{-- Product Info --}}
                                    <div class="p-4">
                                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">
                                            {{ $product->type }}</p>
                                        <h3
                                            class="text-sm font-medium text-[#111111] line-clamp-2 mb-2 group-hover:text-[#FF6B00] transition-colors">
                                            <a href="{{ url('/products/' . $product->id) }}">
                                                {{ $product->name }}
                                            </a>
                                        </h3>
                                        <p class="text-base font-bold text-[#FF6B00]">
                                            {!! $product->price_range !!}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full py-20 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="mt-4 text-gray-600 font-medium">No products found</p>
                                    @if (request()->anyFilled(['search', 'brands', 'types', 'price', 'sizes', 'colors']))
                                        <a href="{{ route('products.index') }}"
                                            class="inline-block mt-2 text-[#FF6B00] hover:underline">
                                            Clear all filters
                                        </a>
                                    @endif
                                </div>
                            @endforelse
                        </div>

                        {{-- Pagination --}}
                        @if ($products->hasPages())
                            <div class="mt-12">
                                <div class="flex items-center justify-center gap-1">
                                    {{-- Previous --}}
                                    @if ($products->onFirstPage())
                                        <span class="px-3 py-2 text-gray-400 cursor-not-allowed">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 19l-7-7 7-7" />
                                            </svg>
                                        </span>
                                    @else
                                        <a href="{{ $products->previousPageUrl() }}"
                                            class="px-3 py-2 text-gray-600 hover:text-[#FF6B00] transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 19l-7-7 7-7" />
                                            </svg>
                                        </a>
                                    @endif

                                    {{-- Page Numbers --}}
                                    @foreach (range(1, $products->lastPage()) as $page)
                                        @if ($page == $products->currentPage())
                                            <span class="px-4 py-2 bg-[#FF6B00] text-white font-medium rounded">
                                                {{ $page }}
                                            </span>
                                        @elseif($page == 1 || $page == $products->lastPage() || abs($page - $products->currentPage()) < 2)
                                            <a href="{{ $products->url($page) }}"
                                                class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded transition-colors">
                                                {{ $page }}
                                            </a>
                                        @elseif(abs($page - $products->currentPage()) == 2)
                                            <span class="px-2 text-gray-400">...</span>
                                        @endif
                                    @endforeach

                                    {{-- Next --}}
                                    @if ($products->hasMorePages())
                                        <a href="{{ $products->nextPageUrl() }}"
                                            class="px-3 py-2 text-gray-600 hover:text-[#FF6B00] transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    @else
                                        <span class="px-3 py-2 text-gray-400 cursor-not-allowed">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </main>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
