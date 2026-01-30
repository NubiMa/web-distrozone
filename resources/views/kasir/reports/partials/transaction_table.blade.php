<table class="w-full whitespace-nowrap">
    <thead>
        <tr class="bg-gray-50/50">
            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                Waktu</th>
            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                ID Pesanan</th>
            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                Item</th>
            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                Metode</th>
            <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">
                Total</th>
            <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">
                Margin</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-50">
        @forelse ($transactions as $transaction)
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-6 py-4 text-sm font-bold text-gray-900">
                    {{ $transaction->created_at->format('H:i') }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">
                    #{{ $transaction->transaction_code }}</td>
                <td class="px-6 py-4">
                    @if ($transaction->details->count() > 0)
                        <p class="text-sm font-bold text-gray-900">
                            {{ $transaction->details->first()->productVariant->product->brand ?? 'Unknown' }}
                        </p>
                        <p class="text-xs text-gray-400">
                            {{ $transaction->details->first()->productVariant->size ?? '-' }}
                            @if ($transaction->details->count() > 1)
                                <span class="text-orange-500 font-bold ml-1">+{{ $transaction->details->count() - 1 }}
                                    lainnya</span>
                            @else
                                , Qty {{ $transaction->details->first()->quantity }}
                            @endif
                        </p>
                    @else
                        <span class="text-sm text-gray-500">-</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    @php
                        $methodClass = match ($transaction->payment_method) {
                            'qris' => 'bg-blue-100 text-blue-700',
                            'tunai' => 'bg-green-100 text-green-700',
                            'transfer' => 'bg-purple-100 text-purple-700',
                            default => 'bg-gray-100 text-gray-700',
                        };
                        $methodLabel = match ($transaction->payment_method) {
                            'tunai' => 'Tunai',
                            'qris' => 'QRIS',
                            'transfer' => 'Transfer',
                            default => ucfirst($transaction->payment_method),
                        };
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $methodClass }}">
                        {{ $methodLabel }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right text-sm font-bold text-orange-600">
                    Rp {{ number_format($transaction->total, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 text-center">
                    @php
                        // Calculate margin
                        $totalCost = $transaction->details->sum(function ($detail) {
                            return $detail->cost_price * $detail->quantity;
                        });
                        $totalRevenue = $transaction->total;
                        $profit = $totalRevenue - $totalCost;
                        $margin = $totalRevenue > 0 ? ($profit / $totalRevenue) * 100 : 0;
                    @endphp
                    <div class="flex flex-col items-center gap-1">
                        <div class="flex items-center gap-2">
                            @if ($margin > 0)
                                <span class="text-green-600 font-bold text-sm">✓</span>
                            @elseif($margin < 0)
                                <span class="text-red-600 font-bold text-sm">✗</span>
                            @else
                                <span class="text-gray-400 font-bold text-sm">≈</span>
                            @endif
                            <span
                                class="text-xs font-bold {{ $margin > 0 ? 'text-green-600' : ($margin < 0 ? 'text-red-600' : 'text-gray-500') }}">
                                Rp {{ number_format(abs($profit), 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-500">Tidak ada transaksi</td>
            </tr>
        @endforelse
    </tbody>
</table>

@if ($transactions->hasPages())
    <div class="p-4 border-t border-gray-50 flex justify-end gap-2">
        @if (!$transactions->onFirstPage())
            <button
                class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
        @endif
        @if ($transactions->hasMorePages())
            <button
                class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        @endif
    </div>
@endif
