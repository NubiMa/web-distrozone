<nav x-data="{ open: false, scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)"
    :class="{ 'bg-[#0f0f0f]/95 backdrop-blur-md shadow-lg': scrolled, 'bg-[#0f0f0f]': !scrolled }"
    class="fixed w-full z-50 transition-all duration-300 border-b border-white/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">

            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ url('/') }}" class="text-2xl font-bold font-display text-white tracking-tighter">
                    DISTRO<span class="text-gradient">ZONE</span>.
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ url('/') }}"
                    class="text-gray-300 hover:text-accent transition-colors text-sm font-medium">BERANDA</a>
                <a href="{{ url('/products') }}"
                    class="text-gray-300 hover:text-accent transition-colors text-sm font-medium">KATALOG</a>
                <a href="{{ url('/about') }}"
                    class="text-gray-300 hover:text-accent transition-colors text-sm font-medium">TENTANG KAMI</a>

                <!-- Search Bar -->
                <div class="relative group">
                    <input type="text"
                        class="bg-white/5 border border-white/10 rounded-full py-2 px-4 pl-10 text-sm text-white focus:outline-none focus:border-accent w-48 transition-all group-hover:bg-white/10"
                        placeholder="Cari produk...">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Right Icons -->
            <div class="hidden md:flex items-center space-x-6">
                @auth
                    <!-- Wishlist Icon -->
                    <a href="{{ route('wishlist.index') }}"
                        class="group relative text-gray-400 hover:text-accent transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                            </path>
                        </svg>
                    </a>

                    <!-- Cart Icon -->
                    <a href="{{ route('cart.index') }}"
                        class="group relative text-gray-400 hover:text-accent transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        @if (session('cart') && count(session('cart')) > 0)
                            <span
                                class="absolute -top-1 -right-1 bg-accent text-white text-[10px] font-bold px-1.5 rounded-full">{{ count(session('cart')) }}</span>
                        @endif
                    </a>

                    <!-- Profile Icon -->
                    <a href="{{ route('profile.show') }}"
                        class="group relative text-gray-400 hover:text-accent transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </a>
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}"
                            class="px-5 py-2 bg-white/5 hover:bg-white/10 text-white text-sm font-bold rounded-full transition-all border border-white/10 backdrop-blur-sm">
                            MASUK
                        </a>
                        <a href="{{ route('register') }}"
                            class="px-5 py-2 bg-accent hover:bg-accent-light text-white text-sm font-bold rounded-full transition-all shadow-[0_0_10px_rgba(255,107,0,0.3)] hover:shadow-[0_0_15px_rgba(255,107,0,0.5)]">
                            DAFTAR
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center">
                <button @click="open = !open" class="text-gray-300 hover:text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                        <path x-show="open" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" x-cloak class="md:hidden bg-primary border-b border-white/10">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="{{ url('/') }}"
                class="block px-3 py-2 text-white hover:text-accent text-base font-medium">Beranda</a>
            <a href="{{ url('/products') }}"
                class="block px-3 py-2 text-gray-300 hover:text-white text-base font-medium">Katalog</a>
            <a href="{{ url('/about') }}"
                class="block px-3 py-2 text-gray-300 hover:text-white text-base font-medium">Tentang Kami</a>
            @auth
                <a href="{{ url('/dashboard') }}"
                    class="block px-3 py-2 text-gray-300 hover:text-white text-base font-medium">Dashboard</a>
                <a href="{{ url('/cart') }}"
                    class="block px-3 py-2 text-gray-300 hover:text-white text-base font-medium">Keranjang</a>
            @else
                <a href="{{ route('login') }}" class="block px-3 py-2 text-accent font-medium">Masuk</a>
            @endauth
        </div>
    </div>
</nav>
