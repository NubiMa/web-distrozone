<x-app-layout>
    <x-customer-sidebar active="address">
        <div class="space-y-8">
            <!-- Header & Add Button -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold font-display text-primary mb-1">Alamat</h1>
                    <p class="text-gray-500 text-sm">Kelola alamat pengiriman pesanan kamu di sini.</p>
                </div>
                <button onclick="document.getElementById('addAddressModal').classList.remove('hidden')"
                    class="bg-accent hover:bg-accent-light text-white font-bold py-3 px-6 rounded-lg uppercase tracking-wide text-sm shadow-lg shadow-accent/20 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Alamat Baru
                </button>
            </div>

            <!-- Address Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($addresses as $address)
                    <div
                        class="relative bg-white border {{ $address->is_primary ? 'border-accent bg-orange-50/30' : 'border-gray-200' }} rounded-xl p-6 transition-all hover:shadow-md group">

                        @if ($address->is_primary)
                            <div
                                class="absolute -top-3 -right-3 bg-accent text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm">
                                UTAMA
                            </div>
                        @endif

                        <div class="flex items-center gap-3 mb-4">
                            <h3 class="font-bold text-lg text-primary">{{ $address->recipient_name }}</h3>
                            <span
                                class="bg-gray-100 text-gray-600 text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider">
                                {{ strtoupper($address->label) }}
                            </span>
                        </div>

                        <div class="space-y-3 mb-6">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                    </path>
                                </svg>
                                <p class="text-gray-600 text-sm">{{ $address->phone }}</p>
                            </div>
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    {{ $address->address }}, {{ $address->city }}, {{ $address->postal_code }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between border-t border-gray-100 pt-4">
                            <div class="flex gap-4">
                                <button
                                    class="text-accent font-bold text-sm hover:text-accent-light flex items-center gap-1 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                        </path>
                                    </svg>
                                    Ubah
                                </button>
                                <form action="{{ route('address.destroy', $address->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus alamat ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-gray-400 font-bold text-sm hover:text-red-500 flex items-center gap-1 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>

                            @if ($address->is_primary)
                                <div
                                    class="flex items-center gap-1 text-accent font-bold text-xs uppercase tracking-wider">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Terpilih
                                </div>
                            @else
                                <form action="{{ route('address.setPrimary', $address->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="text-gray-400 font-bold text-xs uppercase tracking-wider hover:text-primary transition-colors">
                                        Set Utama
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Map Placeholder -->
            <div class="rounded-xl overflow-hidden border border-gray-200 relative group">
                <img src="https://images.unsplash.com/photo-1569336415962-a4bd9f69cd83?q=80&w=2062&auto=format&fit=crop"
                    alt="Map"
                    class="w-full h-64 object-cover object-center grayscale opacity-60 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-500">
                <div class="absolute inset-0 flex flex-col items-center justify-center bg-black/10">
                    <div class="bg-white/90 backdrop-blur-sm px-6 py-3 rounded-full shadow-lg flex items-center gap-3">
                        <div class="w-3 h-3 bg-accent rounded-full animate-pulse"></div>
                        <span class="font-bold text-primary">{{ count($addresses) }} Lokasi Tersimpan</span>
                    </div>
                    <p class="text-white text-xs mt-3 drop-shadow-md font-medium">Semua alamat kamu tersinkronisasi
                        untuk pengiriman cepat</p>
                </div>
            </div>

        </div>
    </x-customer-sidebar>

    <!-- Add Address Modal -->
    <div id="addAddressModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"
            onclick="document.getElementById('addAddressModal').classList.add('hidden')"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <form action="{{ route('address.store') }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <h3 class="text-xl font-bold font-display text-primary mb-6" id="modal-title">Tambah Alamat
                                Baru</h3>

                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-gray-500 uppercase mb-1">Label</label>
                                        <input type="text" name="label" placeholder="Rumah, Kantor..."
                                            class="w-full border-gray-200 rounded-lg focus:ring-accent focus:border-accent text-sm py-3 px-4"
                                            required>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-gray-500 uppercase mb-1">Penerima</label>
                                        <input type="text" name="recipient_name" placeholder="Nama Penerima"
                                            value="{{ Auth::user()->name }}"
                                            class="w-full border-gray-200 rounded-lg focus:ring-accent focus:border-accent text-sm py-3 px-4"
                                            required>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nomor
                                        Telepon</label>
                                    <input type="text" name="phone" placeholder="08..."
                                        class="w-full border-gray-200 rounded-lg focus:ring-accent focus:border-accent text-sm py-3 px-4"
                                        required>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Alamat
                                        Lengkap</label>
                                    <textarea name="address" rows="3" placeholder="Nama Jalan, No. Rumah, RT/RW, Patokan..."
                                        class="w-full border-gray-200 rounded-lg focus:ring-accent focus:border-accent text-sm py-3 px-4" required></textarea>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-gray-500 uppercase mb-1">Kota/Kabupaten</label>
                                        <input type="text" name="city" placeholder="Jakarta Selatan"
                                            class="w-full border-gray-200 rounded-lg focus:ring-accent focus:border-accent text-sm py-3 px-4"
                                            required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Kode
                                            Pos</label>
                                        <input type="text" name="postal_code" placeholder="12345"
                                            class="w-full border-gray-200 rounded-lg focus:ring-accent focus:border-accent text-sm py-3 px-4"
                                            required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="submit"
                                class="inline-flex w-full justify-center rounded-md bg-accent px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-accent-light sm:ml-3 sm:w-auto">Simpan
                                Alamat</button>
                            <button type="button"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
                                onclick="document.getElementById('addAddressModal').classList.add('hidden')">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
