<x-admin-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Employee Management</h1>
                <p class="text-gray-500 mt-1">Manage access, roles, and personal details for your staff members.</p>
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
                            placeholder="Search by name, NIK, or username..."
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
                Add New Employee
            </a>
        </div>

        <!-- Staff Table -->
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-orange-50/50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 uppercase tracking-wider">
                                Photo</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 uppercase tracking-wider">
                                Name</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 uppercase tracking-wider">
                                Address</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 uppercase tracking-wider">
                                Phone</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 uppercase tracking-wider">
                                NIK</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 uppercase tracking-wider">
                                Username</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-orange-800 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($staff as $employee)
                            <tr class="hover:bg-gray-50 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($employee->employee && $employee->employee->photo)
                                        <img src="{{ asset('storage/' . $employee->employee->photo) }}"
                                            alt="{{ $employee->name }}"
                                            class="w-10 h-10 rounded-full object-cover ring-2 ring-gray-100">
                                    @else
                                        <div
                                            class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-sm ring-2 ring-gray-100">
                                            {{ substr($employee->name, 0, 1) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $employee->name }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $employee->role == 'kasir' ? 'Cashier' : 'Staff' }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600 max-w-[200px] truncate"
                                        title="{{ $employee->employee->address ?? '-' }}">
                                        {{ $employee->employee->address ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600">{{ $employee->phone ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-md text-xs font-medium border border-gray-200">
                                        {{ $employee->employee->nik ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-orange-600">
                                        @php
                                            $username = explode('@', $employee->email)[0];
                                        @endphp
                                        {{ '@' . $username }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div
                                        class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('admin.staff.edit', $employee->id) }}"
                                            class="p-2 bg-white border border-gray-200 rounded-lg text-gray-600 hover:text-orange-600 hover:border-orange-200 transition-colors shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.staff.destroy', $employee->id) }}" method="POST"
                                            class="inline"
                                            onsubmit="return confirm('Yakin ingin menghapus staff ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 bg-white border border-gray-200 rounded-lg text-gray-600 hover:text-red-600 hover:border-red-200 transition-colors shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    No employees found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($staff->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        Showing <span
                            class="font-bold text-gray-900">{{ $staff->firstItem() }}-{{ $staff->lastItem() }}</span>
                        of {{ $staff->total() }} results
                    </div>
                    <div>
                        {{ $staff->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
