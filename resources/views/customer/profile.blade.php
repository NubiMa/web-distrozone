<x-app-layout>
    <x-customer-sidebar active="profile">
        <div class="space-y-8">

            <!-- Header Section -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold font-display text-primary">Profil Saya</h1>
                    <p class="text-gray-500 text-sm mt-1">Kelola informasi pribadi dan preferensi akunmu.</p>
                </div>
            </div>

            <!-- Profile Information Form -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 md:p-8">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <h2 class="text-lg font-bold text-primary">Informasi Profil</h2>
                    </div>
                    <span
                        class="px-2 py-1 bg-green-100 text-green-700 text-xs font-bold uppercase rounded-sm">Terverifikasi</span>
                </div>

                <div class="flex flex-col md:flex-row gap-8">
                    <!-- Avatar Upload (Visual) -->
                    <div class="flex-shrink-0 text-center">
                        <div
                            class="w-24 h-24 rounded-full bg-gray-100 mx-auto mb-3 overflow-hidden group cursor-pointer relative">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0F172A&color=fff"
                                alt="Avatar" class="w-full h-full object-cover">
                            <div
                                class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <button class="text-sm text-gray-500 hover:text-accent font-medium">Ubah Foto</button>
                    </div>

                    <!-- Form Fields -->
                    <form method="POST" action="{{ route('profile.update') }}"
                        class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Nama
                                Lengkap</label>
                            <input type="text" name="name" value="{{ $user->name }}"
                                class="w-full bg-gray-50 border border-gray-200 rounded-sm px-4 py-3 text-sm focus:outline-none focus:border-accent font-medium text-primary">
                        </div>
                        <div>
                            <label
                                class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Email</label>
                            <input type="email" value="{{ $user->email }}" readonly
                                class="w-full bg-gray-100 border border-gray-200 rounded-sm px-4 py-3 text-sm text-gray-500 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Nomor
                                Telepon</label>
                            <input type="text" name="phone" value="{{ $user->phone ?? '' }}"
                                class="w-full bg-gray-50 border border-gray-200 rounded-sm px-4 py-3 text-sm focus:outline-none focus:border-accent font-medium text-primary">
                        </div>

                        <!-- Placeholder for alignment -->
                        <div class="hidden md:block"></div>

                        <div class="md:col-span-2 text-right mt-2">
                            <button type="submit"
                                class="bg-accent hover:bg-accent-light text-white font-bold py-3 px-8 rounded-sm uppercase tracking-wide text-sm shadow-lg shadow-accent/20 transition-all flex items-center gap-2 ml-auto">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                    </path>
                                </svg>
                                Edit Profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Shipping Address (Full Width since Security account removed) -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                        <h2 class="text-lg font-bold text-primary">Alamat Pengiriman</h2>
                    </div>
                    <button class="text-gray-400 hover:text-accent"><svg class="w-4 h-4" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                            </path>
                        </svg></button>
                </div>

                <div class="bg-gray-50 p-4 rounded-sm border border-gray-100 flex gap-4">
                    <div
                        class="w-10 h-10 bg-white rounded-full flex items-center justify-center flex-shrink-0 text-accent shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        @if ($primaryAddress)
                            <div class="flex items-center gap-2 mb-1">
                                <p class="font-bold text-primary">{{ $primaryAddress->label }}</p>
                                <span
                                    class="bg-accent/10 text-accent text-[10px] font-bold px-2 py-0.5 rounded uppercase">Utama</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-1">{{ $primaryAddress->recipient_name }} •
                                {{ $primaryAddress->phone }}</p>
                            <p class="text-sm text-gray-500 leading-relaxed">
                                {{ $primaryAddress->address }}, {{ $primaryAddress->city }},
                                {{ $primaryAddress->postal_code }}
                            </p>
                        @else
                            <p class="font-bold text-primary mb-1">Belum ada alamat utama</p>
                            <p class="text-sm text-gray-500 leading-relaxed">
                                Tambahkan alamat pengiriman untuk mempermudah proses checkout.
                            </p>
                        @endif
                    </div>
                </div>

                <a href="{{ route('address.index') }}"
                    class="w-full mt-4 border border-gray-200 text-gray-600 font-bold py-2.5 rounded-sm hover:bg-gray-50 transition-colors text-sm block text-center">
                    Kelola Alamat
                </a>
            </div>

            <!-- Recent Order History -->
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-primary">Riwayat Pesanan Terbaru</h2>
                    <a href="#" class="text-accent text-sm font-bold flex items-center gap-1 hover:underline">
                        Lihat Semua <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500 uppercase font-bold text-xs tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Order ID</th>
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4">Item</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($orders as $order)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-primary">
                                        #{{ $order->transaction_code }}</td>
                                    <td class="px-6 py-4 text-gray-500">
                                        {{ $order->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 text-gray-600 max-w-xs truncate">
                                        {{ $order->details->first()->product->name ?? 'Product' }}
                                        @if ($order->details->count() > 1)
                                            <span class="text-gray-400 text-xs">+{{ $order->details->count() - 1 }}
                                                others</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusColor = 'bg-gray-100 text-gray-600';
                                            if ($order->order_status == 'pending') {
                                                $statusColor = 'bg-yellow-100 text-yellow-700';
                                            }
                                            if ($order->order_status == 'processing') {
                                                $statusColor = 'bg-blue-100 text-blue-700';
                                            }
                                            if ($order->order_status == 'shipped') {
                                                $statusColor = 'bg-orange-100 text-orange-700';
                                            }
                                            if ($order->order_status == 'completed') {
                                                $statusColor = 'bg-green-100 text-green-700';
                                            }
                                            if ($order->order_status == 'cancelled') {
                                                $statusColor = 'bg-red-100 text-red-700';
                                            }
                                        @endphp
                                        <span
                                            class="px-2 py-1 rounded-sm text-xs font-bold uppercase tracking-wide {{ $statusColor }}">
                                            {{ $order->order_status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-primary">Rp
                                        {{ number_format($order->total, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                        Belum ada pesanan terbaru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </x-customer-sidebar>
</x-app-layout>
