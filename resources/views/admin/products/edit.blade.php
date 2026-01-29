<x-admin-layout>
    <x-slot name="head">
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        <!-- ntc.js for color naming -->
        <script src="{{ asset('js/ntc.js?v=3') }}"></script>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="productForm({{ $product->variants->toJson() }})">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('admin.products.index') }}"
                class="text-orange-600 hover:text-orange-700 font-bold mb-4 inline-block">
                ← Kembali ke Daftar Produk
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Edit Produk</h1>
            <p class="text-gray-500 mt-1">Update informasi produk dan varian</p>
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
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Product Info Card -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Informasi Produk</h2>
                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Nama Produk *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>

                <!-- Brand & Type -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="brand" class="block text-sm font-bold text-gray-700 mb-2">Brand *</label>
                        <input type="text" name="brand" id="brand" value="{{ old('brand', $product->brand) }}"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    </div>
                    <div>
                        <label for="type" class="block text-sm font-bold text-gray-700 mb-2">Tipe *</label>
                        <select name="type" id="type" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            <option value="">Pilih Tipe</option>
                            <option value="lengan panjang"
                                {{ old('type', $product->type) == 'lengan panjang' ? 'selected' : '' }}>Lengan Panjang
                            </option>
                            <option value="lengan pendek"
                                {{ old('type', $product->type) == 'lengan pendek' ? 'selected' : '' }}>Lengan Pendek
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Base Price -->
                <div class="mb-4">
                    <label for="base_price" class="block text-sm font-bold text-gray-700 mb-2">Harga Dasar *</label>
                    <input type="number" name="base_price" id="base_price"
                        value="{{ old('base_price', $product->base_price) }}" required min="0"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    <p class="text-xs text-gray-500 mt-1">Harga yang akan ditampilkan jika tidak ada varian</p>
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">{{ old('description', $product->description) }}</textarea>
                </div><!-- Status -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Status Produk</label>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center">
                            <input type="radio" name="is_active" value="1"
                                {{ old('is_active', $product->is_active) == 1 ? 'checked' : '' }} class="mr-2">
                            <span class="text-sm text-gray-700">Aktif</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="is_active" value="0"
                                {{ old('is_active', $product->is_active) == 0 ? 'checked' : '' }} class="mr-2">
                            <span class="text-sm text-gray-700">Nonaktif</span>
                        </label>
                    </div>
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

                            <!-- Hidden ID field for existing variants -->
                            <input type="hidden" :name="'variants[' + index + '][id]'" x-model="variant.id">

                            <div class="grid grid-cols-1 md:grid-cols-6 gap-3">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Warna *</label>
                                    <div class="flex gap-2">
                                        <!-- Color Picker -->
                                        <input type="color" x-model="variant.color_hex" x-init="$watch('variant.color_hex', (val) => updateColorName(index))"
                                            @input="updateColorName(index)" @change="updateColorName(index)"
                                            class="w-12 h-10 rounded border border-gray-300 cursor-pointer flex-shrink-0">
                                        <!-- Auto-generated Color Name (readonly) -->
                                        <input type="text" x-model="variant.color" readonly
                                            class="flex-1 min-w-0 px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-50 focus:ring-2 focus:ring-orange-500"
                                            placeholder="Pilih warna">
                                    </div>
                                    <!-- Hidden inputs for form submission -->
                                    <input type="hidden" :name="'variants[' + index + '][color]'"
                                        x-model="variant.color">
                                    <input type="hidden" :name="'variants[' + index + '][color_hex]'"
                                        x-model="variant.color_hex">
                                </div>
                                <div class="md:col-span-1">
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
                                <div class="md:col-span-1">
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Harga *</label>
                                    <input type="number" :name="'variants[' + index + '][price]'"
                                        x-model="variant.price" required min="0" placeholder="0"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-500">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Stok *</label>
                                    <input type="number" :name="'variants[' + index + '][stock]'"
                                        x-model="variant.stock" required min="0" placeholder="0"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-500">
                                </div>
                            </div>

                            <!-- Variant Photo Upload -->
                            <div class="mt-3">
                                <label class="block text-xs font-bold text-gray-700 mb-1">Foto Varian
                                    (Opsional)</label>

                                <!-- Current Photo Preview -->
                                <template x-if="variant.photo && !variant.photo_preview">
                                    <div class="mb-2">
                                        <img :src="'/images/products/variants/' + variant.photo" alt="Variant photo"
                                            class="h-20 w-20 object-cover rounded-lg border border-gray-200">
                                    </div>
                                </template>

                                <!-- New Photo Preview -->
                                <template x-if="variant.photo_preview">
                                    <div class="mb-2">
                                        <img :src="variant.photo_preview" alt="Preview"
                                            class="h-20 w-20 object-cover rounded-lg border border-gray-200">
                                    </div>
                                </template>

                                <input type="file" :name="'variants[' + index + '][photo]'"
                                    accept="image/jpeg,image/png,image/jpg"
                                    @change="previewVariantImage(index, $event)"
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                                <p class="text-xs text-gray-500 mt-1">JPG, PNG. Maks 2MB</p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3">
                <button type="submit"
                    class="px-8 py-3 bg-orange-600 text-white font-bold rounded-lg hover:bg-orange-700 transition-colors">
                    Update Produk
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
            function productForm(existingVariants) {
                return {
                    variants: existingVariants.length > 0 ? existingVariants.map(v => ({
                        ...v,
                        color_hex: v.color_hex || '#000000', // Preserve existing hex or default
                        photo_preview: null
                    })) : [{
                        color: '',
                        color_hex: '#000000',
                        size: '',
                        price: '',
                        stock: '',
                        photo: null,
                        photo_preview: null
                    }],
                    init() {
                        if (typeof ntc !== 'undefined') {
                            ntc.init();
                        } else {
                            console.warn('ntc.js not loaded');
                        }

                        // Watch for changes in variants to update color names automatically
                        this.$watch('variants', (variants) => {
                            variants.forEach((variant, index) => {
                                if (variant.color_hex && !variant.color) {
                                    this.updateColorName(index);
                                }
                            });
                        });
                    },
                    addVariant() {
                        this.variants.push({
                            color: '',
                            color_hex: '#000000',
                            size: '',
                            price: '',
                            stock: '',
                            photo: null,
                            photo_preview: null
                        });
                    },
                    removeVariant(index) {
                        this.variants.splice(index, 1);
                    },
                    updateColorName(index) {
                        const hex = this.variants[index].color_hex;
                        if (hex) {
                            if (typeof ntc !== 'undefined') {
                                const match = ntc.name(hex);
                                this.variants[index].color = match[1]; // Set color name
                            } else {
                                // Fallback if library failed to load
                                this.variants[index].color = hex + ' (Manual)';
                                console.warn('ntc.js not loaded, using hex code');
                            }
                        }
                    },
                    previewVariantImage(index, event) {
                        const file = event.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                this.variants[index].photo_preview = e.target.result;
                            };
                            reader.readAsDataURL(file);
                        }
                    }
                }
            }
        </script>
    </x-slot>
</x-admin-layout>
