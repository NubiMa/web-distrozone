@php
    $defaultHours = [
        'Monday' => ['start' => '09:00', 'end' => '21:00', 'closed' => false],
        'Tuesday' => ['start' => '09:00', 'end' => '21:00', 'closed' => false],
        'Wednesday' => ['start' => '09:00', 'end' => '21:00', 'closed' => false],
        'Thursday' => ['start' => '09:00', 'end' => '21:00', 'closed' => false],
        'Friday' => ['start' => '09:00', 'end' => '22:00', 'closed' => false],
        'Saturday' => ['start' => '10:00', 'end' => '22:00', 'closed' => false],
        'Sunday' => ['start' => '10:00', 'end' => '21:00', 'closed' => false],
    ];

    // Ensure we have all keys even if DB has partial data
    $hoursData = array_merge($defaultHours, $settings['online_hours'] ?? []);

    $dayTranslations = [
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu',
        'Sunday' => 'Minggu',
    ];

    $dayOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
@endphp
<x-admin-layout>
    <x-slot name="head">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <style>
            .flatpickr-calendar {
                font-family: 'Inter', sans-serif;
                border-radius: 12px;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                border: 1px solid #f3f4f6;
            }

            .flatpickr-time input:hover,
            .flatpickr-time .flatpickr-am-pm:hover,
            .flatpickr-time input:focus,
            .flatpickr-time .flatpickr-am-pm:focus {
                background: #fff7ed;
            }
        </style>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8"
        x-data='{
            hours: {!! json_encode($hoursData) !!},
            labels: {!! json_encode($dayTranslations) !!},
            days: {!! json_encode($dayOrder) !!},
            initPicker(el, modelName) {
                flatpickr(el, {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true,
                    defaultDate: this.hours[modelName.split(".")[0]][modelName.split(".")[1]],
                    onChange: (selectedDates, dateStr) => {
                        // Manually update the model path
                        let parts = modelName.split(".");
                        this.hours[parts[0]][parts[1]] = dateStr;
                    }
                });
            }
         }'>
        <!-- ... form ... -->
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Header -->
            <!-- ... (header content kept implicitly or assumed unchanged if not targeted) ... -->
            <!-- WAIT, replace_file_content replaces a block. I must target the specific inputs block inside the template -->

            <!-- Let's target the exact inputs inside the loop to avoid massive replacement -->

            <!-- I will Cancel this Request and do a targeted replacement for the INPUTS and separate replacement for the Head Slot -->

            @csrf

            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <nav class="flex text-sm text-gray-500 mb-1">
                        <span class="hover:text-gray-700 cursor-pointer">Pengaturan</span>
                        <span class="mx-2">/</span>
                        <span class="text-orange-600 font-medium">Toko & Operasional</span>
                    </nav>
                    <h1 class="text-3xl font-bold text-gray-900 uppercase">PENGATURAN TOKO</h1>
                    <p class="text-gray-500 mt-1">Kelola detail flagship DistroZone, preferensi regional, dan jadwal
                        operasional mingguan Anda.</p>
                </div>
                <div class="flex gap-3 mt-4 md:mt-0 sticky top-24 z-10">
                    <button type="button" onclick="window.history.back()"
                        class="px-6 py-3 bg-white border border-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition-colors shadow-sm">
                        Batalkan
                    </button>
                    <button type="submit"
                        class="px-6 py-3 bg-orange-600 text-white font-bold rounded-xl hover:bg-orange-700 transition-colors shadow-lg shadow-orange-600/20">
                        Simpan Perubahan
                    </button>
                </div>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div
                    class="mb-6 p-4 bg-green-100 border border-green-200 text-green-800 rounded-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- General Information -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center text-orange-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-gray-900 uppercase">Informasi Umum</h2>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Store Name -->
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama
                                    Toko</label>
                                <input type="text" name="store_name"
                                    value="{{ $settings['store_name'] ?? 'DistroZone Flagship - LA' }}"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 font-bold text-gray-900">
                            </div>

                            <!-- Store ID -->
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">ID
                                    Toko</label>
                                <div class="relative">
                                    <input type="text" value="DZ-8842-X" readonly
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-500 cursor-not-allowed">
                                    <svg class="w-5 h-5 text-gray-400 absolute right-3 top-3.5" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="col-span-2">
                                <label
                                    class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Deskripsi
                                    Toko</label>
                                <textarea name="store_description" rows="3"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 font-medium text-gray-700 resize-none">{{ $settings['store_description'] ?? 'Destinasi streetwear utama yang berlokasi di jantung distrik seni.' }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Location & Contact -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center text-orange-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-gray-900 uppercase">Lokasi & Kontak</h2>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Address -->
                            <div class="col-span-2">
                                <label
                                    class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Alamat
                                    Lengkap</label>
                                <div class="relative">
                                    <input type="text" name="store_address"
                                        value="{{ $settings['store_address'] ?? '1200 S Hope St, Los Angeles, CA 90015' }}"
                                        class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 font-bold text-gray-900">
                                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-span-2 md:col-span-1">
                                <label
                                    class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email
                                    Dukungan</label>
                                <input type="email" name="store_email"
                                    value="{{ $settings['store_email'] ?? 'help@distrozone.com' }}"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 font-medium text-gray-700">
                            </div>

                            <!-- Phone -->
                            <div class="col-span-2 md:col-span-1">
                                <label
                                    class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nomor
                                    Telepon</label>
                                <input type="text" name="store_phone"
                                    value="{{ $settings['store_phone'] ?? '+1 (213) 555-0199' }}"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 font-medium text-gray-700">
                            </div>

                            <!-- Currency -->
                            {{-- <div class="col-span-2 md:col-span-1">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Mata
                                    Uang</label>
                                <select name="store_currency"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 font-medium text-gray-700">
                                    <option value="IDR"
                                        {{ ($settings['store_currency'] ?? 'IDR') == 'IDR' ? 'selected' : '' }}>IDR -
                                        Rupiah Indonesia</option>
                                    <option value="USD"
                                        {{ ($settings['store_currency'] ?? '') == 'USD' ? 'selected' : '' }}>USD -
                                        Dolar
                                        AS</option>
                                </select>
                            </div> --}}

                            <!-- Timezone -->
                            {{-- <div class="col-span-2 md:col-span-1">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Zona
                                    Waktu</label>
                                <select name="store_timezone"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 font-medium text-gray-700">
                                    <option value="Asia/Jakarta"
                                        {{ ($settings['store_timezone'] ?? 'Asia/Jakarta') == 'Asia/Jakarta' ? 'selected' : '' }}>
                                        (GMT+07:00) Jakarta, Bangkok</option>
                                    <option value="America/Los_Angeles"
                                        {{ ($settings['store_timezone'] ?? '') == 'America/Los_Angeles' ? 'selected' : '' }}>
                                        (GMT-08:00) Pacific Time</option>
                                </select>
                            </div> --}}

                            <!-- Map Placeholder -->
                            {{-- <div class="col-span-2 mt-2">
                                <div
                                    class="w-full h-40 bg-gray-200 rounded-xl overflow-hidden relative grayscale opacity-70">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/e/ec/USA_California_location_map.svg"
                                        class="w-full h-full object-cover object-center" alt="Map Location">
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/10">
                                        <span
                                            class="text-xs font-bold text-white bg-black/50 px-3 py-1 rounded-full">Tampilan
                                            Peta</span>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden"
                        x-data="{
                            methods: {{ json_encode($settings['payment_methods'] ?? []) }},
                            showModal: false,
                            editingIndex: null,
                            form: {
                                type: 'bank_transfer',
                                name: '',
                                enabled: true,
                                details: {}
                            },
                            addMethod() {
                                this.editingIndex = null;
                                this.form = {
                                    type: 'bank_transfer',
                                    name: '',
                                    enabled: true,
                                    details: {}
                                };
                                this.showModal = true;
                            },
                            editMethod(index) {
                                this.editingIndex = index;
                                this.form = JSON.parse(JSON.stringify(this.methods[index]));
                                this.showModal = true;
                            },
                            saveMethod() {
                                if (this.editingIndex !== null) {
                                    this.methods[this.editingIndex] = JSON.parse(JSON.stringify(this.form));
                                } else {
                                    this.methods.push(JSON.parse(JSON.stringify(this.form)));
                                }
                                this.showModal = false;
                            },
                            deleteMethod(index) {
                                if (confirm('Hapus metode pembayaran ini?')) {
                                    this.methods.splice(index, 1);
                                }
                            }
                        }">

                        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center text-orange-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                </div>
                                <h2 class="text-lg font-bold text-gray-900 uppercase">Metode Pembayaran</h2>
                            </div>
                            <button type="button" @click="addMethod()"
                                class="px-4 py-2 bg-orange-600 text-white text-sm font-bold rounded-lg hover:bg-orange-700 transition-colors">
                                + Tambah Metode
                            </button>
                        </div>

                        <div class="p-6 space-y-4">
                            <!-- Payment Methods List -->
                            <template x-if="methods.length === 0">
                                <div class="text-center py-8 text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    <p class="text-sm font-medium">Belum ada metode pembayaran</p>
                                    <p class="text-xs">Klik "Tambah Metode" untuk menambahkan</p>
                                </div>
                            </template>

                            <template x-for="(method, index) in methods" :key="index">
                                <div
                                    class="border border-gray-200 rounded-xl p-4 hover:border-orange-300 transition-colors">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span
                                                    class="px-2 py-1 bg-gray-100 text-gray-700 text-xs font-bold rounded uppercase"
                                                    x-text="method.type.replace('_', ' ')"></span>
                                                <span class="text-sm font-bold text-gray-900"
                                                    x-text="method.name"></span>
                                                <span x-show="method.enabled"
                                                    class="text-xs text-green-600 font-medium">● Aktif</span>
                                                <span x-show="!method.enabled"
                                                    class="text-xs text-gray-400 font-medium">○ Nonaktif</span>
                                            </div>

                                            <!-- Bank Transfer Details -->
                                            <template x-if="method.type === 'bank_transfer'">
                                                <div class="text-xs text-gray-600 space-y-1">
                                                    <p><span class="font-semibold">Bank:</span> <span
                                                            x-text="method.details.bank_name"></span></p>
                                                    <p><span class="font-semibold">Rekening:</span> <span
                                                            x-text="method.details.account_number"></span></p>
                                                    <p><span class="font-semibold">A/N:</span> <span
                                                            x-text="method.details.account_holder"></span></p>
                                                </div>
                                            </template>

                                            <!-- QRIS Details -->
                                            <template x-if="method.type === 'qris'">
                                                <div class="text-xs text-gray-600">
                                                    <p>Gambar QRIS tersimpan</p>
                                                </div>
                                            </template>

                                            <!-- E-Wallet Details -->
                                            <template x-if="method.type === 'e_wallet'">
                                                <div class="text-xs text-gray-600 space-y-1">
                                                    <p><span class="font-semibold">Provider:</span> <span
                                                            x-text="method.details.provider"></span></p>
                                                    <p><span class="font-semibold">Nomor:</span> <span
                                                            x-text="method.details.phone"></span></p>
                                                </div>
                                            </template>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <button type="button" @click="editMethod(index)"
                                                class="p-2 text-gray-400 hover:text-orange-600 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button type="button" @click="deleteMethod(index)"
                                                class="p-2 text-gray-400 hover:text-red-600 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <!-- Hidden input for form submission -->
                            <input type="hidden" name="payment_methods" :value="JSON.stringify(methods)">
                        </div>

                        <!-- Add/Edit Modal -->
                        <div x-show="showModal"
                            class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
                            @click.self="showModal = false" style="display: none;">
                            <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto"
                                @click.stop>
                                <div class="p-6 border-b border-gray-100">
                                    <h3 class="text-xl font-bold text-gray-900"
                                        x-text="editingIndex !== null ? 'Edit Metode Pembayaran' : 'Tambah Metode Pembayaran'">
                                    </h3>
                                </div>

                                <div class="p-6 space-y-4">
                                    <!-- Payment Type -->
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tipe
                                            Pembayaran</label>
                                        <select x-model="form.type"
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 font-medium text-gray-700">
                                            <option value="bank_transfer">Transfer Bank</option>
                                            <option value="qris">QRIS</option>
                                            <option value="e_wallet">E-Wallet</option>
                                        </select>
                                    </div>

                                    <!-- Name -->
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama
                                            Metode</label>
                                        <input type="text" x-model="form.name"
                                            placeholder="e.g., BCA Virtual Account"
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 font-medium text-gray-700">
                                    </div>

                                    <!-- Bank Transfer Fields -->
                                    <template x-if="form.type === 'bank_transfer'">
                                        <div class="space-y-4">
                                            <div>
                                                <label
                                                    class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama
                                                    Bank</label>
                                                <input type="text" x-model="form.details.bank_name"
                                                    placeholder="e.g., BCA"
                                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 font-medium text-gray-700">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-xs font-bold text-gray-500 uppercase mb-2">Nomor
                                                    Rekening</label>
                                                <input type="text" x-model="form.details.account_number"
                                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 font-medium text-gray-700">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-xs font-bold text-gray-500 uppercase mb-2">Atas
                                                    Nama</label>
                                                <input type="text" x-model="form.details.account_holder"
                                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 font-medium text-gray-700">
                                            </div>
                                        </div>
                                    </template>

                                    <!-- QRIS Fields -->
                                    <template x-if="form.type === 'qris'">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Upload
                                                QRIS Image</label>
                                            <input type="file" name="qris_image" accept="image/*"
                                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 font-medium text-gray-700 
                                                file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                                            <p class="text-xs text-gray-400 mt-2">Max: 2MB</p>
                                        </div>
                                    </template>

                                    <!-- E-Wallet Fields -->
                                    <template x-if="form.type === 'e_wallet'">
                                        <div class="space-y-4">
                                            <div>
                                                <label
                                                    class="block text-xs font-bold text-gray-500 uppercase mb-2">Provider</label>
                                                <select x-model="form.details.provider"
                                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 font-medium text-gray-700">
                                                    <option value="GoPay">GoPay</option>
                                                    <option value="OVO">OVO</option>
                                                    <option value="Dana">Dana</option>
                                                    <option value="ShopeePay">ShopeePay</option>
                                                    <option value="LinkAja">LinkAja</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-xs font-bold text-gray-500 uppercase mb-2">Nomor
                                                    Telepon</label>
                                                <input type="text" x-model="form.details.phone"
                                                    placeholder="08xxxxxxxxxx"
                                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 font-medium text-gray-700">
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Enabled Toggle -->
                                    <div class="flex items-center justify-between pt-4 border-t">
                                        <label class="text-sm font-bold text-gray-700">Aktifkan Metode Ini</label>
                                        <button type="button" @click="form.enabled = !form.enabled"
                                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors"
                                            :class="form.enabled ? 'bg-orange-500' : 'bg-gray-200'">
                                            <span
                                                class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
                                                :class="form.enabled ? 'translate-x-6' : 'translate-x-1'"></span>
                                        </button>
                                    </div>
                                </div>

                                <div class="p-6 border-t border-gray-100 flex gap-3">
                                    <button type="button" @click="showModal = false"
                                        class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors">
                                        Batal
                                    </button>
                                    <button type="button" @click="saveMethod()"
                                        class="flex-1 px-4 py-3 bg-orange-600 text-white font-bold rounded-xl hover:bg-orange-700 transition-colors">
                                        Simpan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="lg:col-span-1">
                    <!-- Store Hours -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-32">
                        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center text-orange-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900 uppercase">Jam Operasional</h2>
                                <p class="text-xs text-gray-500">Konfigurasi jadwal mingguan</p>
                            </div>
                        </div>

                        <div class="p-6">
                            <p class="text-sm text-gray-500 mb-6">Atur jam buka standar Anda. Perubahan akan segera
                                berlaku di POS.</p>

                            <div class="space-y-1">
                                <template x-for="dayKey in days" :key="dayKey">
                                    <div class="flex items-center justify-between py-3">
                                        <!-- Day Name -->
                                        <div class="w-24 font-bold text-gray-900 capitalize" x-text="labels[dayKey]">
                                        </div>

                                        <!-- Time Inputs or Closed Badge -->
                                        <div class="flex-1 flex items-center justify-end mr-4">
                                            <template x-if="!hours[dayKey].closed">
                                                <div class="flex items-center gap-2">
                                                    <input type="text" :name="`online_hours[${dayKey}][start]`"
                                                        x-init="initPicker($el, `${dayKey}.start`)"
                                                        class="px-2 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm font-semibold text-gray-700 focus:ring-2 focus:ring-orange-500 w-24 text-center cursor-pointer">
                                                    <span class="text-gray-300 font-bold">-</span>
                                                    <input type="text" :name="`online_hours[${dayKey}][end]`"
                                                        x-init="initPicker($el, `${dayKey}.end`)"
                                                        class="px-2 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm font-semibold text-gray-700 focus:ring-2 focus:ring-orange-500 w-24 text-center cursor-pointer">
                                                </div>
                                            </template>

                                            <template x-if="hours[dayKey].closed">
                                                <span
                                                    class="px-6 py-2 bg-gray-100 text-gray-400 rounded-lg text-sm font-bold uppercase tracking-wide">Tutup</span>
                                            </template>

                                            <!-- Hidden Inputs for form submission -->
                                            <div class="hidden">
                                                <input type="hidden" :name="`online_hours[${dayKey}][start]`"
                                                    x-model="hours[dayKey].start">
                                                <input type="hidden" :name="`online_hours[${dayKey}][end]`"
                                                    x-model="hours[dayKey].end">
                                                <input type="hidden" :name="`online_hours[${dayKey}][closed]`"
                                                    :value="hours[dayKey].closed ? 1 : 0">
                                            </div>
                                        </div>

                                        <!-- Toggle Switch -->
                                        <div>
                                            <button type="button"
                                                @click="hours[dayKey].closed = !hours[dayKey].closed"
                                                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
                                                :class="!hours[dayKey].closed ? 'bg-orange-500' : 'bg-gray-200'">
                                                <span
                                                    class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
                                                    :class="!hours[dayKey].closed ? 'translate-x-6' : 'translate-x-1'"></span>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <div class="mt-8 border-t border-gray-100 pt-6">
                                <div class="flex items-start gap-3">
                                    <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900">Catatan</h4>
                                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                                            Aktifkan/nonaktifkan hari menggunakan tombol. Saat jam valid diatur, toko
                                            online akan menerima pesanan selama waktu tersebut.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <x-slot name="scripts">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    </x-slot>
</x-admin-layout>
