<x-admin-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <nav class="flex text-sm text-gray-500 mb-1">
                    <span class="hover:text-gray-700 cursor-pointer">Inventory Management</span>
                    <span class="mx-2">/</span>
                    <span class="text-gray-900 font-medium">Kaos Inventory</span>
                </nav>
                <h1 class="text-3xl font-bold text-gray-900">Kaos Inventory</h1>
                <p class="text-gray-500 mt-1">Manage your streetwear products, track real-time stock levels, and update
                    pricing.</p>
            </div>
            <a href="{{ route('admin.products.create') }}"
                class="px-6 py-3 bg-orange-600 text-white font-bold rounded-xl hover:bg-orange-700 transition-colors flex items-center gap-2 shadow-lg shadow-orange-600/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add New Product
            </a>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div
                class="mb-6 p-4 bg-green-100 border border-green-200 text-green-800 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Filter Bar -->
        <div
            class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row gap-4 justify-between items-center">
            <div class="relative w-full md:w-96">
                <form method="GET" action="{{ route('admin.products.index') }}">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search by name, SKU, or brand..."
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
            </div>

            <div class="flex gap-3 w-full md:w-auto">
                <select name="brand" onchange="this.form.submit()"
                    class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm font-medium text-gray-700">
                    <option value="">All Brands</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>
                            {{ $brand }}</option>
                    @endforeach
                </select>

                <select name="type" onchange="this.form.submit()"
                    class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm font-medium text-gray-700">
                    <option value="">All Types</option>
                    <option value="lengan panjang" {{ request('type') == 'lengan panjang' ? 'selected' : '' }}>Long
                        Sleeve</option>
                    <option value="lengan pendek" {{ request('type') == 'lengan pendek' ? 'selected' : '' }}>Short
                        Sleeve</option>
                </select>
                </form>
            </div>
        </div>

        <!-- Products Table -->
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-orange-50/50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 uppercase tracking-wider">
                                Product</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 uppercase tracking-wider">
                                Merek</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 uppercase tracking-wider">
                                Type</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 uppercase tracking-wider">
                                Warna</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 uppercase tracking-wider">
                                Size</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-orange-800 uppercase tracking-wider">
                                Harga</th>
                            <th
                                class="px-6 py-4 text-center text-xs font-bold text-orange-800 uppercase tracking-wider">
                                Stok</th>
                            <th
                                class="px-6 py-4 text-center text-xs font-bold text-orange-800 uppercase tracking-wider">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($products as $product)
                            <tr class="hover:bg-gray-50 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden border border-gray-100 flex-shrink-0">
                                            <img src="{{ $product->photo_url }}" alt="{{ $product->name }}"
                                                class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900">{{ $product->name }}</div>
                                            <div class="text-xs text-gray-500">
                                                {{ Str::limit($product->description, 30) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-700">{{ $product->brand }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600 capitalize">{{ $product->type }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex -space-x-2">
                                        @foreach ($product->available_colors as $index => $color)
                                            @if ($index < 3)
                                                <div class="w-6 h-6 rounded-full border border-white shadow-sm ring-1 ring-gray-100"
                                                    style="background-color: {{ $color }};"
                                                    title="{{ $color }}"></div>
                                            @endif
                                        @endforeach
                                        @if (count($product->available_colors) > 3)
                                            <div
                                                class="w-6 h-6 rounded-full bg-gray-100 border border-white flex items-center justify-center text-[10px] font-bold text-gray-500">
                                                +{{ count($product->available_colors) - 3 }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1 capitalize">
                                        {{ implode(', ', array_slice($product->available_colors, 0, 2)) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex gap-1 flex-wrap max-w-[120px]">
                                        @foreach ($product->available_sizes as $size)
                                            <span
                                                class="px-1.5 py-0.5 bg-gray-100 border border-gray-200 rounded text-[10px] font-bold text-gray-600 uppercase">
                                                {{ $size }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm font-bold text-gray-900">{{ $product->price_range }}</div>
                                    <div class="text-xs text-gray-400 line-through">
                                        @php
                                            // Simulated cost price range display or just base price
                                            $base = $product->base_price;
                                            $margin = $base * 1.5;
                                        @endphp
                                        {{-- Rp {{ number_format($margin, 0, ',', '.') }}  --}}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if ($product->total_stock > 10)
                                        <span
                                            class="px-2.5 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">
                                            {{ $product->total_stock }}
                                        </span>
                                    @elseif($product->total_stock > 0)
                                        <span
                                            class="px-2.5 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold">
                                            {{ $product->total_stock }}
                                        </span>
                                    @else
                                        <span
                                            class="px-2.5 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">
                                            Out
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.products.edit', $product->id) }}"
                                            class="p-2 bg-white border border-gray-200 rounded-lg text-gray-600 hover:text-orange-600 hover:border-orange-200 transition-colors shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product->id) }}"
                                            method="POST" class="inline"
                                            onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 bg-white border border-gray-200 rounded-lg text-gray-600 hover:text-red-600 hover:border-red-200 transition-colors shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                    No products found matching your filters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($products->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        Showing <span
                            class="font-bold text-gray-900">{{ $products->firstItem() }}-{{ $products->lastItem() }}</span>
                        of {{ $products->total() }} results
                    </div>
                    <div>
                        {{ $products->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
