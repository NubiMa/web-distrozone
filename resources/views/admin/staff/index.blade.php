<x-admin-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Manajemen Karyawan</h1>
                <p class="text-gray-500 mt-1">Kelola akses, peran, dan detail pribadi anggota staf Anda.</p>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-800 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Filter & Search Bar -->
        <div
            class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row gap-4 justify-between items-center">
            <div class="relative w-full md:w-96">
                <form method="GET" action="{{ route('admin.staff.index') }}">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari berdasarkan nama, NIK, atau username..."
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </form>
            </div>

            <a href="{{ route('admin.staff.create') }}"
                class="w-full md:w-auto px-6 py-3 bg-orange-600 text-white font-bold rounded-xl hover:bg-orange-700 transition-colors flex items-center justify-center gap-2 shadow-lg shadow-orange-600/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Karyawan Baru
            </a>
        </div>

        <!-- Staff Table -->
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-orange-50/50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 uppercase tracking-wider">
                            <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 uppercase tracking-wider">
                                Foto</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 uppercase tracking-wider">
                                Nama</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 uppercase tracking-wider">
                                Alamat</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 uppercase tracking-wider">
                                Telepon</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 uppercase tracking-wider">
                                NIK</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 uppercase tracking-wider">
                                Username</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-orange-800 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="staffTableBody" class="divide-y divide-gray-100">
                        @include('admin.staff.partials.table_body')
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($staff->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        Menampilkan <span
                            class="font-bold text-gray-900">{{ $staff->firstItem() }}-{{ $staff->lastItem() }}</span>
                        dari {{ $staff->total() }} hasil
                    </div>
                    <div>
                        {{ $staff->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[name="search"]');
            const tableBody = document.getElementById('staffTableBody');
            let timeoutId;

            searchInput.addEventListener('input', function() {
                clearTimeout(timeoutId);
                const query = this.value;

                timeoutId = setTimeout(() => {
                    fetch(`{{ route('admin.staff.index') }}?search=${query}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.text())
                        .then(html => {
                            tableBody.innerHTML = html;
                        })
                        .catch(error => console.error('Error:', error));
                }, 300); // 300ms debounce
            });
        });
    </script>
</x-admin-layout>
