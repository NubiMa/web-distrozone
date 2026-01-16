<x-app-layout>
    <x-customer-sidebar active="settings">
        <div class="space-y-6">

            <!-- Header -->
            <div>
                <h1 class="text-3xl font-bold font-display text-primary">Pengaturan Akun</h1>
                <p class="text-gray-500 mt-1">Kelola keamanan akun dan preferensi Anda.</p>
            </div>

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                    class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="font-bold mb-1">Terjadi kesalahan:</p>
                            <ul class="list-disc list-inside text-sm space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Account Information (Read-only) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-primary mb-1">Informasi Akun</h2>
                        <p class="text-sm text-gray-500">Informasi dasar akun Anda</p>
                    </div>
                    <a href="{{ route('profile.show') }}"
                        class="text-accent hover:text-accent-light font-bold text-sm flex items-center gap-1">
                        Edit Profil
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </a>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 block">Nama
                            Lengkap</label>
                        <p class="text-gray-900 font-medium">{{ $user->name }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 block">Email</label>
                        <p class="text-gray-900 font-medium">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 block">No.
                            Telepon</label>
                        <p class="text-gray-900 font-medium">{{ $user->phone ?? 'Belum diatur' }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 block">Member
                            Sejak</label>
                        <p class="text-gray-900 font-medium">{{ $user->created_at->format('d F Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Change Password -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-primary mb-1">Ubah Password</h2>
                    <p class="text-sm text-gray-500">Pastikan password Anda kuat dan aman</p>
                </div>

                <form action="{{ route('settings.updatePassword') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="current_password" class="block text-sm font-bold text-gray-700 mb-2">
                            Password Saat Ini <span class="text-red-500">*</span>
                        </label>
                        <input type="password" id="current_password" name="current_password" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent transition-all @error('current_password') border-red-500 @enderror">
                        @error('current_password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-bold text-gray-700 mb-2">
                                Password Baru <span class="text-red-500">*</span>
                            </label>
                            <input type="password" id="password" name="password" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent transition-all @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-2">
                                Konfirmasi Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent transition-all">
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="bg-accent text-white font-bold px-6 py-2.5 rounded-lg hover:bg-accent-light transition-colors shadow-lg shadow-accent/20">
                            Simpan Password Baru
                        </button>
                    </div>
                </form>
            </div>

            <!-- Notification Preferences -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-primary mb-1">Preferensi Notifikasi</h2>
                    <p class="text-sm text-gray-500">Kelola bagaimana kami menghubungi Anda</p>
                </div>

                <form action="{{ route('settings.updatePreferences') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="space-y-4">
                        <!-- Email Notifications -->
                        <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="flex items-center h-6">
                                <input type="checkbox" id="email_notifications" name="email_notifications"
                                    value="1"
                                    {{ old('email_notifications', $user->email_notifications ?? true) ? 'checked' : '' }}
                                    class="w-4 h-4 text-accent border-gray-300 rounded focus:ring-accent">
                            </div>
                            <div class="flex-1">
                                <label for="email_notifications" class="font-bold text-gray-900 cursor-pointer">
                                    Notifikasi Email
                                </label>
                                <p class="text-sm text-gray-500 mt-1">
                                    Terima notifikasi penting via email
                                </p>
                            </div>
                        </div>

                        <!-- Order Updates -->
                        <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="flex items-center h-6">
                                <input type="checkbox" id="order_updates" name="order_updates" value="1"
                                    {{ old('order_updates', $user->order_updates ?? true) ? 'checked' : '' }}
                                    class="w-4 h-4 text-accent border-gray-300 rounded focus:ring-accent">
                            </div>
                            <div class="flex-1">
                                <label for="order_updates" class="font-bold text-gray-900 cursor-pointer">
                                    Update Pesanan
                                </label>
                                <p class="text-sm text-gray-500 mt-1">
                                    Terima pemberitahuan tentang status pesanan Anda
                                </p>
                            </div>
                        </div>

                        <!-- Newsletter -->
                        <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="flex items-center h-6">
                                <input type="checkbox" id="newsletter" name="newsletter" value="1"
                                    {{ old('newsletter', $user->newsletter ?? false) ? 'checked' : '' }}
                                    class="w-4 h-4 text-accent border-gray-300 rounded focus:ring-accent">
                            </div>
                            <div class="flex-1">
                                <label for="newsletter" class="font-bold text-gray-900 cursor-pointer">
                                    Newsletter & Promosi
                                </label>
                                <p class="text-sm text-gray-500 mt-1">
                                    Dapatkan info produk baru dan penawaran spesial
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="bg-accent text-white font-bold px-6 py-2.5 rounded-lg hover:bg-accent-light transition-colors shadow-lg shadow-accent/20">
                            Simpan Preferensi
                        </button>
                    </div>
                </form>
            </div>

            <!-- Privacy & Security Tips -->
            <div class="bg-orange-50 border border-orange-100 rounded-xl p-6">
                <div class="flex gap-4">
                    <div class="flex-shrink-0">
                        <div
                            class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-accent shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-primary mb-2">Tips Keamanan</h3>
                        <ul class="text-sm text-gray-700 space-y-1">
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-accent mt-0.5 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Gunakan password yang unik dan kuat
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-accent mt-0.5 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Jangan berbagi password dengan siapapun
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-accent mt-0.5 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Ubah password secara berkala
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </x-customer-sidebar>
</x-app-layout>
