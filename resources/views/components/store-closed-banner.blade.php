@php
    use App\Models\StoreSetting;
    $isStoreOpen = StoreSetting::isOnlineStoreOpen();
    $statusMessage = StoreSetting::getStoreStatusMessage();
@endphp

@if (!$isStoreOpen)
    <div class="bg-amber-50 border-b border-amber-200 w-full z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex items-center gap-3">
                <!-- Warning Icon -->
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <!-- Message -->
                <div class="flex-1">
                    <p class="text-sm font-semibold text-amber-900">
                        {{ $statusMessage }}
                    </p>
                    <p class="text-xs text-amber-700 mt-0.5">
                        Pesanan masih dapat dilakukan namun akan diverifikasi pada hari berikutnya atau saat toko
                        buka.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endif
