<x-kasir-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header & Search -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Stok Produk</h1>
                <p class="text-gray-500 mt-1">Cek ketersediaan produk di toko</p>
            </div>

            <!-- Search -->
            <form action="{{ route('kasir.inventory') }}" method="GET" class="w-full md:w-auto relative">
                <input type="text" name="search" value="{{ $search }}"
                    placeholder="Cari nama produk, brand, kode SKU..."
                    class="w-full md:w-80 pl-12 pr-4 py-3 bg-white border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 rounded-xl text-sm transition-all shadow-sm">
                <svg class="absolute left-4 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </form>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            @forelse($products as $product)
                <div
                    class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-all group flex flex-col sm:flex-row h-full">
                    <!-- Left: Image & Info -->
                    <div
                        class="p-6 sm:w-2/5 flex flex-col border-b sm:border-b-0 sm:border-r border-gray-100 bg-gray-50/50">
                        <div
                            class="aspect-square bg-white rounded-xl overflow-hidden mb-4 relative shadow-sm border border-gray-100">
                            @if ($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif

                            @if (!$product->is_active)
                                <div class="absolute inset-0 bg-black/60 flex items-center justify-center">
                                    <span
                                        class="px-3 py-1 bg-red-600 text-white text-xs font-bold rounded-full">NON-AKTIF</span>
                                </div>
                            @endif
                        </div>

                        <div class="mt-auto">
                            <div class="flex items-center justify-between mb-2">
                                <span
                                    class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ $product->brand }}</span>
                                <span
                                    class="text-[10px] font-bold px-2 py-0.5 rounded bg-white border border-gray-200 text-gray-600 uppercase tracking-wider">{{ $product->category->name ?? 'N/A' }}</span>
                            </div>
                            <h3 class="font-bold text-lg text-gray-900 leading-tight mb-2" title="{{ $product->name }}">
                                {{ $product->name }}
                            </h3>
                            <p class="text-xl font-bold text-orange-600">
                                @if ($product->base_price > 0)
                                    Rp {{ number_format($product->base_price, 0, ',', '.') }}
                                @else
                                    Rp {{ number_format($product->variants->min('price'), 0, ',', '.') }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Right: Stock Variants -->
                    <div class="p-6 sm:w-3/5 flex flex-col bg-white">
                        <div class="flex justify-between items-center mb-4 pb-2 border-b border-gray-100">
                            <h4 class="font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                Stock Details
                            </h4>
                            <span class="text-xs font-medium text-gray-500">Total:
                                {{ $product->variants->sum('stock') }} items</span>
                        </div>

                        <div class="flex-1 overflow-y-auto custom-scrollbar pr-2 space-y-5">
                            @forelse ($product->variants->groupBy('color') as $color => $variants)
                                <div>
                                    <div class="flex items-center gap-2 mb-3">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-900"></span>
                                        <h5 class="text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            {{ $color }}</h5>
                                    </div>
                                    <div class="grid grid-cols-2 lg:grid-cols-3 gap-3">
                                        @foreach ($variants as $variant)
                                            <div
                                                class="flex items-center justify-between p-2.5 rounded-lg border {{ $variant->stock > 0 ? 'border-gray-200 bg-white hover:border-gray-300' : 'border-red-100 bg-red-50' }} transition-colors">
                                                <div class="flex items-center gap-2">
                                                    <span
                                                        class="w-8 h-8 rounded-md bg-gray-50 flex items-center justify-center text-sm font-bold text-gray-700 border border-gray-100">
                                                        {{ $variant->size }}
                                                    </span>
                                                </div>
                                                <div class="text-right">
                                                    <span
                                                        class="block text-lg font-bold {{ $variant->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                                        {{ $variant->stock }}
                                                    </span>
                                                    <span
                                                        class="text-[9px] font-bold text-gray-400 uppercase">PCS</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <div class="h-full flex flex-col items-center justify-center text-gray-400 py-8">
                                    <svg class="w-10 h-10 mb-2 opacity-50" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 12H4" />
                                    </svg>
                                    <p class="text-sm">No variants available</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-gray-100 border-dashed">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Produk tidak ditemukan</h3>
                    <p class="text-gray-500">Coba gunakan kata kunci pencarian lain atau ubah filter.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8 px-4">
            {{ $products->appends(['search' => $search])->links() }}
        </div>
    </div>
</x-kasir-layout>
