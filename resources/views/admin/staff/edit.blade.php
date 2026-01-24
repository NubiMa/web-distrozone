<x-admin-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('admin.staff.index') }}"
                class="text-orange-600 hover:text-orange-700 font-bold mb-4 inline-block">
                ← Kembali ke Daftar Staff
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Edit Staff</h1>
            <p class="text-gray-500 mt-1">Update informasi karyawan</p>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 border border-red-200 text-red-800 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('admin.staff.update', $staff->id) }}" method="POST" enctype="multipart/form-data"
            class="bg-white border border-gray-200 rounded-xl shadow-sm p-8">
            @csrf
            @method('PUT')

            <!-- Current Photo -->
            @if ($staff->employee && $staff->employee->photo)
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Foto Saat Ini</label>
                    <img src="{{ asset('storage/' . $staff->employee->photo) }}" alt="{{ $staff->name }}"
                        class="w-24 h-24 rounded-full object-cover">
                </div>
            @endif

            <!-- Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $staff->name) }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
            </div>

            <!-- NIK -->
            <div class="mb-6">
                <label for="nik" class="block text-sm font-bold text-gray-700 mb-2">NIK (16 digit) *</label>
                <input type="text" name="nik" id="nik"
                    value="{{ old('nik', $staff->employee->nik ?? '') }}" required maxlength="16" pattern="[0-9]{16}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                <p class="text-xs text-gray-500 mt-1">Harus 16 digit angka</p>
            </div>

            <!-- Email -->
            <div class="mb-6">
                <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Email *</label>
                <input type="email" name="email" id="email" value="{{ old('email', $staff->email) }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-bold text-gray-700 mb-2">Password Baru</label>
                <input type="password" name="password" id="password" minlength="6"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password. Minimal 6 karakter
                </p>
            </div>

            <!-- Phone -->
            <div class="mb-6">
                <label for="phone" class="block text-sm font-bold text-gray-700 mb-2">Nomor Telepon *</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $staff->phone) }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
            </div>

            <!-- Address -->
            <div class="mb-6">
                <label for="address" class="block text-sm font-bold text-gray-700 mb-2">Alamat Lengkap *</label>
                <textarea name="address" id="address" required rows="3"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">{{ old('address', $staff->address) }}</textarea>
            </div>

            <!-- Photo -->
            <div class="mb-6">
                <label for="photo" class="block text-sm font-bold text-gray-700 mb-2">Update Foto Profil</label>
                <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/jpg"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maksimal 2MB</p>
            </div>

            <!-- Status -->
            <div class="mb-8">
                <label class="block text-sm font-bold text-gray-700 mb-2">Status Akun</label>
                <div class="flex items-center gap-4">
                    <label class="flex items-center">
                        <input type="radio" name="is_active" value="1"
                            {{ old('is_active', $staff->is_active) == 1 ? 'checked' : '' }} class="mr-2">
                        <span class="text-sm text-gray-700">Aktif</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="is_active" value="0"
                            {{ old('is_active', $staff->is_active) == 0 ? 'checked' : '' }} class="mr-2">
                        <span class="text-sm text-gray-700">Nonaktif</span>
                    </label>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3">
                <button type="submit"
                    class="px-8 py-3 bg-orange-600 text-white font-bold rounded-lg hover:bg-orange-700 transition-colors">
                    Update
                </button>
                <a href="{{ route('admin.staff.index') }}"
                    class="px-8 py-3 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-admin-layout>
