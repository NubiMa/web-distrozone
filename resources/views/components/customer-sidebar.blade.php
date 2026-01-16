@props(['active' => 'profile'])

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar -->
        <aside class="w-full lg:w-72 flex-shrink-0">
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden sticky top-24">
                <!-- User Info (Upper) -->
                <div class="p-6 border-b border-gray-100 text-center">
                    <div
                        class="w-20 h-20 mx-auto bg-gray-200 rounded-full overflow-hidden mb-4 border-2 border-white shadow-md">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0F172A&color=fff"
                            alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                    </div>
                    <h3 class="font-bold text-lg text-primary truncate">{{ Auth::user()->name }}</h3>
                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                </div>

                <!-- Navigation (Middle) -->
                <nav class="p-4 space-y-1">
                    <a href="{{ route('profile.show') }}"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold rounded-sm transition-colors {{ $active === 'profile' ? 'text-accent bg-orange-50' : 'text-gray-600 hover:text-primary hover:bg-gray-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Profil Saya
                    </a>
                    <a href="{{ route('wishlist.index') }}"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold rounded-sm transition-colors {{ $active === 'wishlist' ? 'text-accent bg-orange-50' : 'text-gray-600 hover:text-primary hover:bg-gray-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                            </path>
                        </svg>
                        Wishlist
                    </a>
                    <a href="{{ route('cart.index') }}"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold rounded-sm transition-colors {{ $active === 'cart' ? 'text-accent bg-orange-50' : 'text-gray-600 hover:text-primary hover:bg-gray-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Keranjang
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold rounded-sm transition-colors {{ $active === 'address' ? 'text-accent bg-orange-50' : 'text-gray-600 hover:text-primary hover:bg-gray-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Alamat
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold rounded-sm transition-colors {{ $active === 'settings' ? 'text-accent bg-orange-50' : 'text-gray-600 hover:text-primary hover:bg-gray-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Pengaturan
                    </a>
                </nav>

                <!-- Logout (Lower) -->
                <div class="p-4 border-t border-gray-100 mt-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-red-600 hover:bg-red-50 w-full rounded-sm transition-colors uppercase tracking-wide">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Slot -->
        <div class="flex-1 min-w-0">
            {{ $slot }}
        </div>
    </div>
</div>
