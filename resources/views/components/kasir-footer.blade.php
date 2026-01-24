<footer class="bg-primary text-white pt-12 pb-6 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pb-8 border-b border-white/10">
            <!-- Brand Section -->
            <div>
                <h2 class="text-2xl font-bold font-display mb-4">
                    DISTRO<span class="text-gradient">ZONE</span>.
                </h2>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Kasir Panel - Manage online orders and verify customer payments.
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="font-bold text-lg mb-4">Quick Links</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ url('/kasir/dashboard') }}"
                            class="text-gray-400 hover:text-accent transition-colors">Dashboard</a></li>
                    <li><a href="{{ url('/kasir/orders') }}"
                            class="text-gray-400 hover:text-accent transition-colors">Online Orders</a></li>
                    <li><a href="{{ url('/kasir/reports') }}"
                            class="text-gray-400 hover:text-accent transition-colors">Reports</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h3 class="font-bold text-lg mb-4">Store Info</h3>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li>Jln. Raya Pegangsaan Timur No.29H</li>
                    <li>Kelapa Gading, Jakarta</li>
                    <li class="pt-2">
                        <span class="font-semibold text-white">Operating Hours:</span><br>
                        Online: 10:00 - 17:00 Daily
                    </li>
                </ul>
            </div>
        </div>

        <!-- Bottom Section -->
        <div class="pt-6 flex flex-col md:flex-row justify-between items-center text-sm text-gray-400">
            <p>© {{ date('Y') }} DistroZone. Kasir Panel.</p>
            <p class="mt-2 md:mt-0">Logged in as: <span class="text-white font-medium">{{ Auth::user()->name }}</span>
            </p>
        </div>
    </div>
</footer>