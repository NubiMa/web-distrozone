<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Breadcrumb -->
            <div class="mb-6 text-sm text-gray-500">
                <a href="{{ route('cart.index') }}" class="hover:text-accent">Keranjang</a>
                <span class="mx-2">›</span>
                <span class="text-gray-900 font-medium">Checkout</span>
            </div>

            <!-- Page Title -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold font-display text-primary">Checkout</h1>
                <p class="text-gray-500 mt-1">Selesaikan pembelian Anda dengan aman.</p>
            </div>

            <form action="{{ route('checkout.store') }}" method="POST" enctype="multipart/form-data"
                @submit="if(!proofPreview) { alert('Harap upload bukti pembayaran terlebih dahulu!'); $event.preventDefault(); return false; } if(!shippingDestination) { alert('Harap pilih kota tujuan terlebih dahulu!'); $event.preventDefault(); return false; }"
                x-data="{
                    paymentMethod: 'transfer',
                    shippingDestination: '',
                    shippingCost: 0,
                    subtotal: {{ $subtotal }},
                    proofPreview: null,
                    addressModalOpen: false,
                    selectedAddress: {{ Js::from($primaryAddress) }},
                    get total() { return this.subtotal + this.shippingCost; }
                }">
                @csrf

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293-1.293a1 1 0 00-1.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700 font-bold">
                                    Terdapat kesalahan pada input Anda:
                                </p>
                                <ul class="mt-1 list-disc list-inside text-sm text-red-600">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column: Checkout Details -->
                    <div class="lg:col-span-2 space-y-6">

                        <!-- Shipping Address -->
                        <div class="bg-gray-900 text-white rounded-xl p-6 relative overflow-hidden shadow-lg">
                            <!-- Decorative Circle -->
                            <div
                                class="absolute top-0 right-0 w-64 h-64 bg-accent/20 rounded-full blur-3xl transform translate-x-32 -translate-y-32">
                            </div>

                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                            </path>
                                        </svg>
                                        <span class="text-xs font-bold uppercase tracking-wider text-gray-300">Kirim
                                            Ke</span>
                                    </div>
                                    <button type="button" @click="addressModalOpen = true"
                                        class="bg-white/10 hover:bg-white/20 px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wide transition-colors flex items-center gap-1 text-white">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                            </path>
                                        </svg>
                                        Ubah
                                    </button>
                                </div>
                                <h3 class="text-xl font-bold mb-1 text-white" x-text="selectedAddress.recipient_name">
                                </h3>
                                <p class="text-gray-300 text-sm mb-2" x-text="selectedAddress.phone"></p>
                                <p class="text-gray-300 text-sm leading-relaxed border-t border-gray-700 pt-2 mt-2"
                                    x-text="selectedAddress.address + ', ' + selectedAddress.city + ', ' + selectedAddress.postal_code">
                                </p>
                                <input type="hidden" name="address_id" :value="selectedAddress.id">
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="bg-white border border-gray-200 rounded-xl p-6">
                            <h2 class="text-lg font-bold text-primary mb-4">Metode Pembayaran</h2>

                            <!-- Payment Tabs -->
                            <div class="flex gap-2 mb-6 border-b border-gray-200">
                                <button type="button" @click="paymentMethod = 'transfer'"
                                    :class="paymentMethod === 'transfer' ? 'border-b-2 border-accent text-accent' :
                                        'text-gray-500'"
                                    class="px-4 py-2 font-bold text-sm uppercase tracking-wide transition-colors">
                                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                        </path>
                                    </svg>
                                    Transfer Bank
                                </button>
                                <button type="button" @click="paymentMethod = 'qris'"
                                    :class="paymentMethod === 'qris' ? 'border-b-2 border-accent text-accent' :
                                        'text-gray-500'"
                                    class="px-4 py-2 font-bold text-sm uppercase tracking-wide transition-colors">
                                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                                        </path>
                                    </svg>
                                    QRIS
                                </button>
                            </div>

                            <!-- Bank Transfer Instructions -->
                            <div x-show="paymentMethod === 'transfer'" x-transition
                                class="bg-orange-50 border border-orange-200 rounded-lg p-6">
                                <div class="flex items-start gap-3 mb-4">
                                    <div class="bg-orange-100 rounded-full p-2">
                                        <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-bold text-sm text-orange-800 mb-1">Instruksi Transfer Manual</p>
                                        <p class="text-xs text-orange-700">Transfer total pembayaran ke rekening di
                                            bawah ini. <strong>Verifikasi diperlukan</strong>.</p>
                                    </div>
                                </div>

                                <div class="bg-white border border-orange-200 rounded-lg p-4 space-y-3">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                            </path>
                                        </svg>
                                        <div>
                                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                                                Nama
                                                Bank</p>
                                            <p class="font-bold text-primary">
                                                {{ $storeSettings['bank_name'] ?? 'Bank BCA' }}</p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p
                                                class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">
                                                No. Rekening</p>
                                            <div class="flex items-center gap-2">
                                                <p class="font-mono font-bold text-primary">
                                                    {{ $storeSettings['bank_account_number'] ?? '1234567890' }}</p>
                                                <button type="button"
                                                    onclick="navigator.clipboard.writeText('{{ $storeSettings['bank_account_number'] ?? '1234567890' }}')"
                                                    class="text-accent hover:text-accent-light">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <div>
                                            <p
                                                class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">
                                                Atas Nama</p>
                                            <p class="font-bold text-primary">
                                                {{ $storeSettings['bank_account_holder'] ?? 'DistroZone Inc.' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 text-xs text-gray-600 flex items-start gap-2">
                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <p>Pesanan akan diproses setelah kasir memverifikasi pembayaran Anda. Mohon upload
                                        bukti transfer di bawah.</p>
                                </div>
                            </div>

                            <!-- QRIS Instructions -->
                            <div x-show="paymentMethod === 'qris'" x-transition
                                class="bg-orange-50 border border-orange-200 rounded-lg p-6 text-center">
                                <p class="text-sm font-bold text-gray-700 mb-4">Scan QR Code di bawah untuk melakukan
                                    pembayaran</p>
                                <div class="inline-block bg-white p-4 rounded-xl shadow-md">
                                    <img src="{{ asset($storeSettings['qris_image'] ?? 'images/payment/qris-distrozone.png') }}"
                                        alt="QRIS DistroZone" class="w-64 h-auto mx-auto">
                                </div>
                                <p class="text-xs text-gray-600 mt-4">Setelah pembayaran, upload bukti transfer di
                                    bawah.</p>
                            </div>

                            <input type="hidden" name="payment_method" x-model="paymentMethod">
                        </div>

                        <!-- Shipping Destination -->
                        <div class="bg-white border border-gray-200 rounded-xl p-6">
                            <h2 class="text-lg font-bold text-primary mb-4">Tujuan Pengiriman</h2>
                            <select name="shipping_destination" x-model="shippingDestination"
                                @change="shippingCost = parseFloat($event.target.selectedOptions[0].dataset.rate) * {{ $weightKg }}"
                                class="w-full border-gray-200 rounded-lg focus:ring-accent focus:border-accent"
                                required>
                                <option value="">Pilih kota tujuan...</option>
                                @foreach ($shippingRates as $rate)
                                    <option value="{{ $rate->destination }}" data-rate="{{ $rate->rate_per_kg }}">
                                        {{ $rate->destination }} - Rp
                                        {{ number_format($rate->rate_per_kg, 0, ',', '.') }}/kg
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-2">
                                <strong>Berat:</strong> {{ $weightKg }} kg ({{ $totalQuantity }} item)
                                <span class="text-orange-600">• 1 kg = maks. 3 kaos</span>
                            </p>
                            @error('shipping_destination')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Proof Upload -->
                        <div class="bg-white border border-gray-200 rounded-xl p-6">
                            <h2 class="text-lg font-bold text-primary mb-2">Bukti Pembayaran <span
                                    class="text-red-500">*</span></h2>
                            <p class="text-sm text-gray-500 mb-4">Upload screenshot/foto bukti transfer Anda (Max. 2MB)
                            </p>

                            <!-- Upload State -->
                            <div x-show="!proofPreview"
                                class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-accent hover:bg-accent/5 transition-all cursor-pointer relative group"
                                @click="$refs.fileInput.click()"
                                @dragover.prevent="$el.classList.add('border-accent', 'bg-accent/5')"
                                @dragleave.prevent="$el.classList.remove('border-accent', 'bg-accent/5')"
                                @drop.prevent="$el.classList.remove('border-accent', 'bg-accent/5'); const file = $event.dataTransfer.files[0]; if(file) { proofPreview = URL.createObjectURL(file); $refs.fileInput.files = $event.dataTransfer.files; }">

                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4 group-hover:text-accent transition-colors"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                    </path>
                                </svg>
                                <p class="text-sm font-medium text-gray-600 group-hover:text-gray-900">Klik untuk
                                    upload atau drag and drop</p>
                                <p class="text-xs text-gray-500 mt-1">SVG, PNG, JPG atau GIF (max. 5MB)</p>
                            </div>

                            <!-- Preview State -->
                            <div x-show="proofPreview" style="display: none;"
                                class="relative rounded-lg overflow-hidden border border-gray-200 group">
                                <img :src="proofPreview" class="w-full h-64 object-cover object-center bg-gray-50">

                                <!-- Hover Overlay -->
                                <div
                                    class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <button type="button" @click="$refs.fileInput.click()"
                                        class="bg-white text-gray-900 font-bold py-2 px-4 rounded-lg shadow hover:bg-gray-100 transform translate-y-2 group-hover:translate-y-0 transition-all flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12">
                                            </path>
                                        </svg>
                                        Ganti Foto
                                    </button>
                                </div>
                                <div class="absolute bottom-2 right-2 flex gap-2">
                                    <span
                                        class="bg-black/70 text-white text-[10px] px-2 py-1 rounded font-bold uppercase tracking-wider">Preview</span>
                                </div>
                            </div>

                            <input type="file" x-ref="fileInput" name="payment_proof" accept="image/*"
                                class="hidden" required
                                @change="const file = $event.target.files[0]; if(file) { if(file.size > 5242880) { alert('Ukuran file maksimal 5MB'); $event.target.value = ''; return; } proofPreview = URL.createObjectURL(file); }">
                            @error('payment_proof')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <!-- Right Column: Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white border border-gray-200 rounded-xl p-6 sticky top-24">
                            <h2 class="text-lg font-bold text-primary mb-4">Ringkasan Pesanan</h2>

                            <!-- Cart Items -->
                            <div class="space-y-3 mb-4 max-h-64 overflow-y-auto">
                                @foreach ($cartItems as $item)
                                    <div class="flex gap-3">
                                        <div class="w-16 h-16 bg-gray-100 rounded flex-shrink-0 overflow-hidden">
                                            <img src="{{ $item->product->photo ? Storage::url($item->product->photo) : 'https://placehold.co/100x100/F5F5F5/999999?text=No+Image' }}"
                                                alt="{{ $item->product->brand }}" class="w-full h-full object-cover">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-sm text-gray-900 truncate">
                                                {{ $item->product->brand }}</p>
                                            <p class="text-xs text-gray-500">{{ $item->product->color }} •
                                                {{ $item->product->size }}</p>
                                            <p class="text-xs text-gray-600 font-medium">{{ $item->quantity }}x Rp
                                                {{ number_format($item->product->selling_price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="border-t border-gray-200 pt-4 space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="font-bold text-gray-900">Rp
                                        {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Ongkir ({{ $weightKg }} kg)</span>
                                    <span class="font-bold text-gray-900"
                                        x-text="'Rp ' + shippingCost.toLocaleString('id-ID')">Rp 0</span>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500 italic">
                                    <span>PPN (11%)</span>
                                    <span>Included</span>
                                </div>
                            </div>

                            <div class="border-t border-gray-200 mt-4 pt-4">
                                <div class="flex justify-between items-center mb-6">
                                    <span class="text-lg font-bold text-primary">Total</span>
                                    <span class="text-2xl font-bold text-accent"
                                        x-text="'Rp ' + total.toLocaleString('id-ID')">Rp
                                        {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>

                                <button type="submit"
                                    class="w-full bg-gradient-to-r from-accent to-orange-600 hover:from-accent-light hover:to-orange-500 text-white font-bold py-4 rounded-lg uppercase tracking-wide transition-all shadow-lg shadow-accent/30 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Kirim Pesanan
                                </button>

                                <p class="text-xs text-gray-500 text-center mt-4">
                                    <svg class="w-4 h-4 inline-block text-green-500" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Transaksi Aman & Terenkripsi
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Selection Modal -->

                <!-- Address Selection Modal -->
                <div x-show="addressModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
                    role="dialog" aria-modal="true">

                    <!-- Backdrop -->
                    <div x-show="addressModalOpen" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/50 backdrop-blur-sm"
                        aria-hidden="true" @click="addressModalOpen = false"></div>

                    <!-- Modal Panel -->
                    <div class="flex min-h-full items-center justify-center p-4">
                        <div x-show="addressModalOpen" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                            class="w-full max-w-2xl bg-white rounded-2xl shadow-xl overflow-hidden relative">

                            <!-- Header -->
                            <div
                                class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50">
                                <h3 class="text-lg font-bold text-primary">Pilih Alamat Pengiriman</h3>
                                <button @click="addressModalOpen = false"
                                    class="text-gray-400 hover:text-gray-600 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <!-- Address List -->
                            <div class="p-6 max-h-[60vh] overflow-y-auto space-y-4">
                                @foreach ($addresses as $address)
                                    <div class="border rounded-xl p-4 transition-all hover:border-accent group"
                                        :class="selectedAddress.id === {{ $address->id }} ? 'border-accent bg-orange-50' :
                                            'border-gray-200'">
                                        <div class="flex items-start justify-between gap-4">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="font-bold text-primary">{{ $address->label }}</span>
                                                    @if ($address->is_primary)
                                                        <span
                                                            class="bg-gray-200 text-gray-600 text-[10px] px-2 py-0.5 rounded font-bold uppercase">Utama</span>
                                                    @endif
                                                </div>
                                                <p class="font-bold text-gray-900">{{ $address->recipient_name }}</p>
                                                <p class="text-sm text-gray-600">{{ $address->phone }}</p>
                                                <p class="text-sm text-gray-600 mt-1 leading-relaxed">
                                                    {{ $address->address }}, {{ $address->city }},
                                                    {{ $address->postal_code }}
                                                </p>
                                            </div>
                                            <button type="button"
                                                @click="selectedAddress = {{ Js::from($address) }}; addressModalOpen = false"
                                                class="px-4 py-2 rounded-lg font-bold text-sm transition-colors"
                                                :class="selectedAddress.id === {{ $address->id }} ?
                                                    'bg-accent text-white shadow-lg shadow-accent/20' :
                                                    'bg-gray-100 text-gray-600 hover:bg-gray-200'">
                                                <span
                                                    x-text="selectedAddress.id === {{ $address->id }} ? 'Terpilih' : 'Pilih'"></span>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Add New Address Button -->
                                <a href="{{ route('address.index') }}"
                                    class="block w-full border-2 border-dashed border-gray-300 rounded-xl p-4 text-center text-gray-500 hover:border-accent hover:text-accent hover:bg-accent/5 transition-all font-bold text-sm">
                                    + Tambah Alamat Baru
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
