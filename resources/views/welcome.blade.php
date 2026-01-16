<x-guest-layout>
    <!-- Hero Section -->
    <div class="relative min-h-[90vh] flex items-center justify-center overflow-hidden bg-primary">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1529139574466-a302d20525b5?q=80&w=2070&auto=format&fit=crop"
                class="w-full h-full object-cover object-center opacity-40 hover:scale-105 transition-transform duration-[20s]"
                alt="Streetwear Group">
            <div class="absolute inset-0 bg-gradient-to-t from-primary via-primary/50 to-transparent"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 text-center px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto mt-20">
            @auth
                <div class="mb-8 fade-in flex justify-center">
                    <div
                        class="py-2 px-6 rounded-full bg-white/5 border border-white/10 backdrop-blur-md flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        <span class="text-gray-300 text-sm font-medium tracking-wide">
                            SELAMAT DATANG KEMBALI, <span
                                class="text-white font-bold">{{ strtoupper(Auth::user()->name) }}</span>
                        </span>
                    </div>
                </div>
            @endauth

            <h1
                class="text-5xl md:text-7xl lg:text-9xl font-bold font-display text-white leading-[0.9] mb-8 tracking-tighter slide-up">
                OWN THE <br class="hidden md:block" /> STREETS.
                <br />
                <span class="text-accent">DEFINE THE ZONE.</span>
            </h1>

            <p
                class="text-lg md:text-xl text-gray-400 mb-12 font-light max-w-2xl mx-auto leading-relaxed fade-in delay-200">
                Streetwear premium. Kuantitas terbatas. Gaya tanpa batas.<br />
                Evolusi fashion urban dimulai di sini.
            </p>

            <div class="flex flex-col sm:flex-row justify-center gap-5 fade-in delay-300">
                <a href="{{ route('register') }}"
                    class="px-8 py-4 bg-accent hover:bg-accent-light text-white font-bold uppercase tracking-widest transition-all transform hover:-translate-y-1 shadow-[0_10px_30px_rgba(255,107,0,0.3)] rounded-sm">
                    GABUNG SEKARANG
                </a>
                <a href="{{ url('/products') }}"
                    class="px-8 py-4 border border-white/30 hover:bg-white/10 text-white font-bold uppercase tracking-widest transition-all backdrop-blur-sm rounded-sm">
                    LIHAT KATALOG
                </a>
            </div>
        </div>
    </div>

    <!-- Fresh Heat (Featured Products) -->
    <section class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-12">
                <h2 class="text-4xl font-bold font-display text-primary tracking-tight">KOLEKSI TERBARU</h2>
                <a href="{{ url('/products') }}"
                    class="group flex items-center gap-2 text-accent font-bold uppercase tracking-wide text-sm hover:text-accent-light transition-colors">
                    Lihat Semua
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-10">
                @foreach ($featuredProducts->take(4) as $index => $product)
                    <div class="group cursor-pointer">
                        <div class="relative bg-gray-100 aspect-[3/4] overflow-hidden mb-4 rounded-sm">
                            <img src="{{ $product->photo ?? 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?q=80&w=1000&auto=format&fit=crop' }}"
                                class="w-full h-full object-cover object-center transform group-hover:scale-110 transition-transform duration-700"
                                alt="{{ $product->name }}">

                            <!-- Badges -->
                            @if ($index == 0)
                                <div
                                    class="absolute top-3 left-3 bg-black text-white text-[10px] font-bold px-2 py-1 uppercase tracking-wider">
                                    BARU</div>
                            @elseif($index == 2)
                                <div
                                    class="absolute top-3 left-3 bg-accent text-white text-[10px] font-bold px-2 py-1 uppercase tracking-wider">
                                    HOT</div>
                            @endif

                            <!-- View Details Overlay -->
                            <div
                                class="absolute inset-x-0 bottom-0 p-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                <a href="{{ url('/products/' . $product->id) }}"
                                    class="block w-full bg-white text-primary font-bold py-3 uppercase text-xs tracking-widest hover:bg-black hover:text-white transition-colors text-center">
                                    LIHAT DETAIL ->
                                </a>
                            </div>
                        </div>

                        <div>
                            <h3
                                class="font-bold text-gray-900 text-base mb-1 group-hover:text-accent transition-colors truncate">
                                {{ $product->name }}</h3>
                            <div class="flex items-center gap-2">
                                <span class="text-accent font-bold text-sm">Rp
                                    {{ number_format($product->selling_price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Categories -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold font-display text-primary tracking-tight mb-10">KATEGORI</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 h-[600px]">
                <!-- Long Sleeve Category -->
                <div class="relative group overflow-hidden cursor-pointer h-full">
                    <img src="https://images.unsplash.com/photo-1549570412-2342cb5270c9?q=80&w=1000&auto=format&fit=crop"
                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                        alt="Long Sleeve">
                    <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors duration-300">
                    </div>
                    <div class="absolute bottom-10 left-10 text-white">
                        <h3 class="text-4xl font-bold font-display uppercase mb-2">LENGAN PANJANG</h3>
                        <div
                            class="flex items-center gap-2 text-sm font-bold uppercase tracking-widest opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                            Lihat Koleksi <span class="text-accent">-></span>
                        </div>
                    </div>
                </div>

                <!-- Short Sleeve Category -->
                <div class="relative group overflow-hidden cursor-pointer h-full">
                    <img src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?q=80&w=1000&auto=format&fit=crop"
                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                        alt="Short Sleeve">
                    <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors duration-300">
                    </div>
                    <div class="absolute bottom-10 left-10 text-white">
                        <h3 class="text-4xl font-bold font-display uppercase mb-2">LENGAN PENDEK</h3>
                        <div
                            class="flex items-center gap-2 text-sm font-bold uppercase tracking-widest opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                            Lihat Koleksi <span class="text-accent">-></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features / Values -->
    <section class="py-24 bg-gray-50 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center md:text-left">
                <!-- Feature 1 -->
                <div>
                    <div
                        class="w-12 h-12 bg-orange-100 text-accent rounded-full flex items-center justify-center mb-6 mx-auto md:mx-0">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold font-display text-primary mb-3 uppercase tracking-wider">EKSKLUSIVITAS
                        KURASI</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Produksi terbatas. Tidak ada produksi massal. Ketika habis, ia hilang untuk selamanya.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div>
                    <div
                        class="w-12 h-12 bg-orange-100 text-accent rounded-full flex items-center justify-center mb-6 mx-auto md:mx-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold font-display text-primary mb-3 uppercase tracking-wider">MATERIAL
                        PREMIUM</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Katun kelas berat, sablon tahan lama, dan kain yang dibuat untuk bertahan di jalanan.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div>
                    <div
                        class="w-12 h-12 bg-orange-100 text-accent rounded-full flex items-center justify-center mb-6 mx-auto md:mx-0">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold font-display text-primary mb-3 uppercase tracking-wider">KULTUR JALANAN
                    </h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Lahir dari beton, dibuat untukmu. Bergabunglah dengan komunitas yang mendefinisikan zona ini.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Never Miss a Drop (Newsletter) -->
    <section class="py-24 bg-[#111111] relative overflow-hidden">
        <!-- Background accents -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-accent opacity-5 rounded-full blur-[100px] pointer-events-none">
        </div>
        <div
            class="absolute bottom-0 left-0 w-64 h-64 bg-accent opacity-5 rounded-full blur-[80px] pointer-events-none">
        </div>

        <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
            <div class="text-accent mb-6 flex justify-center">
                <svg class="w-12 h-12" viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M19.48,13.03C19.07,12.76,18.82,12.29,18.82,11.78V11.23C18.82,9.39,17.2,7.91,15.35,8.15C13.88,8.34,12.73,9.45,12.5,10.92C12.35,11.85,11.79,12.63,11,13.05C9.79,13.63,9,14.88,9,16.27C9,18.33,10.67,20,12.73,20H13.29C15.33,20,17,18.34,17.03,16.3C17.06,14.77,18.06,13.43,19.48,13.03M13.29,22H12.73C9.57,22,7,19.43,7,16.27C7,14.12,8.19,12.19,10.05,11.23C9.97,9.37,10.68,7.6,12.03,6.29C13,5.34,14.28,4.71,15.71,4.55C18.91,4.19,21.72,6.56,21.96,9.75C21.99,10.15,22,10.56,22,10.96V11.78C22,12.91,22.61,13.91,23.53,14.5C22.25,18.82,18.23,22,13.29,22Z" />
                </svg>
            </div>
            <h2 class="text-4xl md:text-5xl font-bold font-display text-white mb-6 uppercase tracking-tight">JANGAN
                LEWATKAN DROP TERBARU</h2>
            <p class="text-gray-400 mb-10 text-lg leading-relaxed max-w-xl mx-auto">
                Berlangganan newsletter DistroZone. Dapatkan akses awal ke rilis terbatas, lookbook eksklusif, dan acara
                komunitas.
            </p>

            <form class="max-w-md mx-auto flex gap-3">
                <input type="email" placeholder="Masukkan email kamu"
                    class="flex-1 bg-white/5 border border-white/10 text-white placeholder-gray-500 px-6 py-4 rounded-sm focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all">
                <button type="button"
                    class="bg-accent hover:bg-accent-light text-white font-bold px-8 py-4 rounded-sm uppercase tracking-wider transition-all shadow-lg shadow-accent/20">
                    GABUNG
                </button>
            </form>

            <div class="mt-12 flex justify-center gap-8 text-gray-500 text-sm font-medium tracking-wide">
                <a href="#" class="hover:text-white transition-colors">INSTAGRAM</a>
                <a href="#" class="hover:text-white transition-colors">TWITTER</a>
                <a href="#" class="hover:text-white transition-colors">TIKTOK</a>
            </div>
            <p class="text-gray-700 text-xs mt-8">&copy; 2026 DistroZone Inc. All rights reserved.</p>
        </div>
    </section>
</x-guest-layout>
