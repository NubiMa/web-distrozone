<nav x-data="{ open: false, profileOpen: false }" class="fixed w-full z-50 bg-[#0f0f0f] border-b border-white/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ url('/admin/dashboard') }}"
                    class="text-2xl font-bold font-display text-white tracking-tighter">
                    DISTRO<span class="text-gradient">ZONE</span>.
                </a>
                <span
                    class="ml-3 px-3 py-1 bg-gradient-to-r from-orange-600 to-orange-500 text-white text-xs font-bold rounded-full">ADMIN</span>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ url('/admin/dashboard') }}"
                    class="text-gray-300 hover:text-accent transition-colors text-sm font-medium">DASHBOARD</a>
                <a href="{{ url('/admin/staff') }}"
                    class="text-gray-300 hover:text-accent transition-colors text-sm font-medium">STAFF</a>
                <a href="{{ url('/admin/products') }}"
                    class="text-gray-300 hover:text-accent transition-colors text-sm font-medium">PRODUCTS</a>
                <a href="{{ url('/admin/reports') }}"
                    class="text-gray-300 hover:text-accent transition-colors text-sm font-medium">REPORTS</a>
                <a href="{{ url('/admin/settings') }}"
                    class="text-gray-300 hover:text-accent transition-colors text-sm font-medium">SETTINGS</a>
            </div>

            <!-- User Dropdown -->
            <div class="hidden md:flex items-center">
                <div class="relative">
                    <button @click="profileOpen = !profileOpen"
                        class="flex items-center gap-3 text-gray-300 hover:text-white transition-colors focus:outline-none">
                        <div class="text-right">
                            <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-400">Administrator</p>
                        </div>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="profileOpen" @click.away="profileOpen = false" x-transition
                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 border border-gray-200 z-50">
                        <a href="/admin/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Profile Settings
                        </a>
                        <div class="border-t border-gray-200 my-1"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                    </path>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
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
            <a href="{{ url('/admin/dashboard') }}"
                class="block px-3 py-2 text-white hover:text-accent text-base font-medium">Dashboard</a>
            <a href="{{ url('/admin/staff') }}"
                class="block px-3 py-2 text-gray-300 hover:text-white text-base font-medium">Staff</a>
            <a href="{{ url('/admin/products') }}"
                class="block px-3 py-2 text-gray-300 hover:text-white text-base font-medium">Products</a>
            <a href="{{ url('/admin/reports') }}"
                class="block px-3 py-2 text-gray-300 hover:text-white text-base font-medium">Reports</a>
            <a href="{{ url('/admin/settings') }}"
                class="block px-3 py-2 text-gray-300 hover:text-white text-base font-medium">Settings</a>
            <div class="border-t border-white/10 mt-2 pt-2">
                <div class="px-3 py-2 text-sm text-gray-400">
                    {{ Auth::user()->name }} (Admin)
                </div>
                <a href="/admin/profile"
                    class="block px-3 py-2 text-gray-300 hover:text-white text-base font-medium">Profile Settings</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="block w-full text-left px-3 py-2 text-red-400 font-medium">Logout</button>
                </form>
            </div>
        </div>
    </div>
</nav>
