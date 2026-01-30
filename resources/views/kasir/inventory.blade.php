<x-kasir-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header & Search -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Stok Produk</h1>
                <p class="text-gray-500 mt-1">Cek ketersediaan produk di toko</p>
            </div>

            <!-- Search -->
            <form action="{{ route('kasir.inventory') }}" method="GET" class="w-full md:w-auto relative">
                <input type="text" name="search" value="{{ $search }}"
                    placeholder="Cari nama produk, brand, kode SKU..."
                    class="w-full md:w-80 pl-12 pr-4 py-3 bg-white border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 rounded-xl text-sm transition-all shadow-sm">
                <svg class="absolute left-4 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </form>
        </div>

        <!-- Product Grid Container -->
        <div id="inventoryContainer">
            @include('kasir.partials.inventory_list')
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.querySelector('input[name="search"]');
                const container = document.getElementById('inventoryContainer');
                let timeoutId;

                searchInput.addEventListener('input', function() {
                    clearTimeout(timeoutId);
                    const query = this.value;

                    timeoutId = setTimeout(() => {
                        fetch(`{{ route('kasir.inventory') }}?search=${query}`, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => response.text())
                            .then(html => {
                                container.innerHTML = html;
                            })
                            .catch(error => console.error('Error:', error));
                    }, 300); // 300ms debounce
                });
            });
        </script>
    </div>
</x-kasir-layout>
