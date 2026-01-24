@props(['product'])

<div
    class="group bg-white border border-border overflow-hidden transition-all duration-300 hover:shadow-lg hover:border-accent/30 relative">
    <!-- Badges -->
    <div class="absolute top-2 left-2 z-10 flex flex-col gap-1">
        @php
            $isNew = $product->created_at->diffInDays(now()) < 7;
            $totalStock = $product->total_stock; // Sum of all variant stocks
        @endphp

        @if ($totalStock <= 0)
            <span class="px-2 py-1 bg-gray-800 text-white text-[10px] uppercase font-bold tracking-wider">HABIS</span>
        @elseif($isNew)
            <span class="px-2 py-1 bg-accent text-white text-[10px] uppercase font-bold tracking-wider">BARU</span>
        @endif
    </div>

    <!-- Image -->
    <div class="aspect-[4/5] overflow-hidden bg-gray-100 relative">
        <a href="{{ url('/products/' . $product->id) }}">
            <img src="{{ $product->image ? Storage::url($product->image) : 'https://placehold.co/400x500/F5F5F5/111111?text=No+Image' }}"
                alt="{{ $product->name }}"
                class="w-full h-full object-cover object-center transition-transform duration-700 group-hover:scale-105">
        </a>

        <!-- Quick Action (Desktop Hover) -->
        <div
            class="absolute bottom-0 left-0 right-0 p-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300 hidden md:block">
            <a href="{{ url('/products/' . $product->id) }}"
                class="block w-full bg-white text-primary border border-primary py-3 font-bold text-xs hover:bg-primary hover:text-white transition-colors text-center uppercase tracking-widest">
                LIHAT DETAIL ->
            </a>
        </div>
    </div>

    <!-- Details -->
    <div class="p-4">
        <div class="flex justify-between items-start mb-1">
            <h3 class="text-sm font-medium text-text-main line-clamp-1 group-hover:text-accent transition-colors">
                <a href="{{ url('/products/' . $product->id) }}">
                    {{ $product->name }}
                </a>
            </h3>
            <p class="text-sm font-bold text-accent">
                {!! $product->price_range !!}
            </p>
        </div>
        <p class="text-xs text-text-muted mb-3">{{ $product->brand }} &bull; {{ $product->type }}</p>

        <!-- Mobile Action Button -->
        <div class="md:hidden">
            @auth
                <button
                    class="w-full bg-primary text-white py-2 text-xs font-bold uppercase hover:bg-accent transition-colors">
                    + Keranjang
                </button>
            @else
                <a href="{{ url('/products/' . $product->id) }}"
                    class="block w-full border border-primary text-primary py-2 text-xs font-bold uppercase hover:bg-primary hover:text-white transition-colors text-center">
                    Detail
                </a>
            @endauth
        </div>
    </div>
</div>
