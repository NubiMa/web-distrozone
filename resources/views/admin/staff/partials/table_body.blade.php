@forelse($staff as $employee)
    <tr class="hover:bg-gray-50 transition-colors group">
        <td class="px-6 py-4 whitespace-nowrap">
            @if ($employee->employee && $employee->employee->photo)
                <img src="{{ asset('storage/' . $employee->employee->photo) }}" alt="{{ $employee->name }}"
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
            <div class="text-sm text-gray-600 max-w-[200px] truncate" title="{{ $employee->employee->address ?? '-' }}">
                {{ $employee->employee->address ?? '-' }}
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-600">{{ $employee->phone ?? '-' }}</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-md text-xs font-medium border border-gray-200">
                {{ $employee->employee->nik ?? '-' }}
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="text-sm font-medium text-orange-600">
                {{ '@' . ($employee->username ?? '-') }}
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <div class="flex items-center justify-end gap-2">
                <a href="{{ route('admin.staff.edit', $employee->id) }}"
                    class="p-2 bg-white border border-gray-200 rounded-lg text-gray-600 hover:text-orange-600 hover:border-orange-200 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                </a>
                <form action="{{ route('admin.staff.destroy', $employee->id) }}" method="POST" class="inline"
                    onsubmit="return confirm('Yakin ingin menghapus staff ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="p-2 bg-white border border-gray-200 rounded-lg text-gray-600 hover:text-red-600 hover:border-red-200 transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
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
