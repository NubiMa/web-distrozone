@php
    use App\Models\StoreSetting;
    $isStoreOpen = StoreSetting::isOnlineStoreOpen();
    $statusMessage = StoreSetting::getStoreStatusMessage();
@endphp

@if (!$isStoreOpen)
    <!-- Store Closed Overlay - Cannot be dismissed manually -->
    <div x-data="{
        show: true,
        checkStatus() {
            // Poll every 60 seconds to see if store is now open
            setInterval(() => {
                fetch('/api/store-status')
                    .then(r => r.json())
                    .then(data => {
                        if (data.is_open) {
                            this.show = false;
                        }
                    });
            }, 60000);
        },
        logout() {
            // Create a form to submit logout
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('logout') }}';
    
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
    
            form.appendChild(csrfToken);
            document.body.appendChild(form);
            form.submit();
        }
    }" x-init="checkStatus()" x-show="show"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[9999] flex items-center justify-center p-4"
        style="pointer-events: auto;">

        <div x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 relative">

            <!-- Warning Icon -->
            <div class="mx-auto w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mb-6">
                <svg class="w-12 h-12 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>

            <!-- Title -->
            <h2 class="text-2xl font-bold text-gray-900 text-center mb-3">
                Toko Sedang Tutup
            </h2>

            <!-- Message -->
            <p class="text-gray-600 text-center mb-2 font-semibold">
                {{ $statusMessage }}
            </p>
            <p class="text-sm text-gray-500 text-center mb-8">
                Sistem kasir tidak dapat digunakan saat toko tutup. Silakan logout dan coba lagi saat jam operasional.
            </p>

            <!-- Logout Button (NOT dismissible) -->
            <button @click="logout()" type="button"
                class="w-full px-6 py-3 bg-orange-600 text-white font-bold rounded-xl hover:bg-orange-700 transition-colors shadow-lg shadow-orange-600/20">
                Saya Mengerti
            </button>

            <!-- Auto-hide notice -->
            <p class="text-xs text-gray-400 text-center mt-4">
                Notifikasi ini akan hilang otomatis saat toko buka
            </p>
        </div>
    </div>
@endif
