@forelse($transactions as $transaction)
    <tr class="hover:bg-gray-50 transition-colors">
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm font-bold text-gray-900">
                {{ $transaction->created_at->format('d M Y') }}</div>
            <div class="text-xs text-gray-500">{{ $transaction->created_at->format('H:i') }}
                WIB</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="text-sm font-bold text-gray-900">#{{ $transaction->transaction_code }}</span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                    @if ($transaction->cashier && $transaction->cashier->employee && $transaction->cashier->employee->photo)
                        <img src="{{ asset('storage/' . $transaction->cashier->employee->photo) }}"
                            class="w-full h-full object-cover">
                    @else
                        <span
                            class="text-xs font-bold text-gray-500">{{ substr($transaction->cashier->name ?? '?', 0, 1) }}</span>
                    @endif
                </div>
                <span class="text-sm font-medium text-gray-700">{{ $transaction->cashier->name ?? '-' }}</span>
            </div>
        </td>
        <td class="px-6 py-4">
            <div class="text-sm text-gray-700 line-clamp-1">
                @foreach ($transaction->details as $detail)
                    {{ $detail->productVariant->product->name }} ({{ $detail->quantity }}),
                @endforeach
            </div>
            <div class="text-xs text-gray-400 mt-0.5">{{ $transaction->details->count() }}
                Item</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center gap-2 text-sm text-gray-600">
                @if ($transaction->payment_method == 'qris')
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                @else
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                @endif
                {{ ucfirst($transaction->payment_method) }}
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="text-sm font-bold text-orange-600">Rp
                {{ number_format($transaction->total, 0, ',', '.') }}</span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            @php
                // Calculate total cost and revenue for this transaction
                $totalCost = $transaction->details->sum(function ($detail) {
                    return $detail->cost_price * $detail->quantity;
                });
                $totalRevenue = $transaction->total;
                $profit = $totalRevenue - $totalCost;
                $margin = $totalRevenue > 0 ? ($profit / $totalRevenue) * 100 : 0;

                // Determine color based on margin
                if ($margin > 0) {
                    $marginClass = 'text-green-600 bg-green-50';
                    $sign = '+';
                } elseif ($margin < 0) {
                    $marginClass = 'text-red-600 bg-red-50';
                    $sign = '';
                } else {
                    $marginClass = 'text-gray-600 bg-gray-50';
                    $sign = '';
                }
            @endphp
            <span class="px-2.5 py-1 rounded-lg text-xs font-bold {{ $marginClass }}">
                {{ $sign }}{{ number_format($margin, 1) }}%
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            @php
                $statusClasses = [
                    'verified' => 'bg-green-100 text-green-700',
                    'pending' => 'bg-yellow-100 text-yellow-700',
                    'rejected' => 'bg-red-100 text-red-700',
                ];
                $statusLabel = [
                    'verified' => 'Selesai',
                    'pending' => 'Pending',
                    'rejected' => 'Dibatalkan',
                ];
            @endphp
            <span
                class="px-3 py-1 rounded-full text-xs font-bold {{ $statusClasses[$transaction->payment_status] ?? 'bg-gray-100' }}">
                {{ $statusLabel[$transaction->payment_status] ?? ucfirst($transaction->payment_status) }}
            </span>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
            Tidak ada transaksi untuk periode ini
        </td>
    </tr>
@endforelse
