<x-admin-layout>
    <x-slot name="head">
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="productForm()">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('admin.products.index') }}"
                class="text-orange-600 hover:text-orange-700 font-bold mb-4 inline-block">
                ← Kembali ke Daftar Produk
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Tambah Produk Baru</h1>
            <p class="text-gray-500 mt-1">Tambahkan produk dengan varian warna dan ukuran</p>
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
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf

            <!-- Product Info Card -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Informasi Produk</h2>

                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Nama Produk *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>

                <!-- Brand & Type -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="brand" class="block text-sm font-bold text-gray-700 mb-2">Brand *</label>
                        <input type="text" name="brand" id="brand" value="{{ old('brand') }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    </div>
                    <div>
                        <label for="type" class="block text-sm font-bold text-gray-700 mb-2">Tipe *</label>
                        <select name="type" id="type" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            <option value="">Pilih Tipe</option>
                            <option value="lengan panjang" {{ old('type') == 'lengan panjang' ? 'selected' : '' }}>
                                Lengan Panjang</option>
                            <option value="lengan pendek" {{ old('type') == 'lengan pendek' ? 'selected' : '' }}>Lengan
                                Pendek</option>
                        </select>
                    </div>
                </div>

                <!-- Base Price -->
                <div class="mb-4">
                    <label for="base_price" class="block text-sm font-bold text-gray-700 mb-2">Harga Dasar *</label>
                    <input type="number" name="base_price" id="base_price" value="{{ old('base_price') }}" required
                        min="0"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    <p class="text-xs text-gray-500 mt-1">Harga yang akan ditampilkan jika tidak ada varian</p>
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">{{ old('description') }}</textarea>
                </div>

                <!-- Photo -->
                <div>
                    <label for="photo" class="block text-sm font-bold text-gray-700 mb-2">Foto Produk *</label>
                    <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/jpg" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maksimal 2MB</p>
                </div>
            </div>

            <!-- Variants Card -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-900">Varian Produk</h2>
                    <button type="button" @click="addVariant()"
                        class="px-4 py-2 bg-orange-600 text-white text-sm font-bold rounded-lg hover:bg-orange-700 transition-colors">
                        + Tambah Varian
                    </button>
                </div>

                <p class="text-sm text-gray-500 mb-4">Minimal harus ada 1 varian</p>

                <div class="space-y-3">
                    <template x-for="(variant, index) in variants" :key="index">
                        <div class="p-4 border border-gray-200 rounded-lg">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-sm font-bold text-gray-700">Varian #<span
                                        x-text="index + 1"></span></span>
                                <button type="button" @click="removeVariant(index)" x-show="variants.length > 1"
                                    class="text-red-600 hover:text-red-700 text-sm font-bold">
                                    Hapus
                                </button>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Warna *</label>
                                    <input type="text" :name="'variants[' + index + '][color]'"
                                        x-model="variant.color" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Ukuran *</label>
                                    <select :name="'variants[' + index + '][size]'" x-model="variant.size" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-500">
                                        <option value="">Pilih</option>
                                        <option value="XS">XS</option>
                                        <option value="S">S</option>
                                        <option value="M">M</option>
                                        <option value="L">L</option>
                                        <option value="XL">XL</option>
                                        <option value="2XL">2XL</option>
                                        <option value="3XL">3XL</option>
                                        <option value="4XL">4XL</option>
                                        <option value="5XL">5XL</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Harga *</label>
                                    <input type="number" :name="'variants[' + index + '][price]'"
                                        x-model="variant.price" required min="0"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Stok *</label>
                                    <input type="number" :name="'variants[' + index + '][stock]'"
                                        x-model="variant.stock" required min="0"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-500">
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3">
                <button type="submit"
                    class="px-8 py-3 bg-orange-600 text-white font-bold rounded-lg hover:bg-orange-700 transition-colors">
                    Simpan Produk
                </button>
                <a href="{{ route('admin.products.index') }}"
                    class="px-8 py-3 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <x-slot name="scripts">
        <script>
            function productForm() {
                return {
                    variants: [{
                        color: '',
                        size: '',
                        price: '',
                        stock: ''
                    }],
                    addVariant() {
                        this.variants.push({
                            color: '',
                            size: '',
                            price: '',
                            stock: ''
                        });
                    },
                    removeVariant(index) {
                        this.variants.splice(index, 1);
                    }
                }
            }
        </script>
    </x-slot>
</x-admin-layout>
