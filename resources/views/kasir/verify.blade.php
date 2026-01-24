<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'DistroZone') }} - Payment Verification</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Back Button -->
        <a href="{{ route('kasir.orders.index') }}"
            class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-900 mb-6 group">
            <svg class="w-4 h-4 mr-1 group-hover:-translate-x-1 transition-transform" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Orders
        </a>

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Payment Verification</h1>
            <div class="flex items-center gap-2 mt-2">
                <p class="text-gray-600">Verify the bank transfer proof for Order <span
                        class="font-bold text-gray-900">#{{ $transaction->transaction_code }}</span>.</p>
                @if ($transaction->payment_status === 'pending')
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-orange-100 text-orange-700">
                        ⏳ AWAITING APPROVAL
                    </span>
                @elseif($transaction->payment_status === 'rejected')
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">
                        ✕ REJECTED
                    </span>
                @else
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">
                        ✓ {{ strtoupper($transaction->payment_status) }}
                    </span>
                @endif
                <span class="text-xs text-gray-400 ml-auto">Submitted:
                    {{ $transaction->created_at->format('M d, h:i A') }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left: Payment Proof -->
            <div x-data="{ zoom: false }" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 h-fit">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Payment Proof
                    </h2>
                    <div class="flex gap-2">
                        <button @click="zoom = !zoom" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                            </svg>
                        </button>
                        @if ($transaction->payment_proof)
                            <a href="{{ asset('storage/' . $transaction->payment_proof) }}" download
                                class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>

                <div
                    class="bg-gray-50 rounded-xl overflow-hidden flex items-center justify-center min-h-[400px] border-2 border-dashed border-gray-200 relative">
                    @if ($transaction->payment_proof)
                        <img :class="zoom ? 'fixed inset-0 z-50 w-full h-full object-contain bg-black/90 p-10 cursor-zoom-out' :
                            'w-full h-auto object-contain max-h-[600px] cursor-zoom-in'"
                            src="{{ asset('storage/' . $transaction->payment_proof) }}" alt="Proof"
                            @click="zoom = !zoom">
                        <p x-show="!zoom" class="absolute bottom-4 text-xs text-gray-400 bg-white/80 px-2 py-1 rounded">
                            Click to zoom</p>
                    @else
                        <div class="text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p>No payment proof uploaded</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right: Order Summary & Actions -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 h-fit">
                <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-6 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    Order Summary
                </h2>

                <!-- Customer Card -->
                <div class="bg-gray-50 rounded-xl p-4 mb-8 flex items-center gap-4">
                    <div
                        class="h-12 w-12 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold text-lg">
                        {{ substr($transaction->user->name ?? 'G', 0, 2) }}
                    </div>
                    <div>
                        <p class="font-bold text-gray-900">{{ $transaction->user->name ?? 'Guest' }}</p>
                        <p class="text-sm text-gray-500">{{ $transaction->user->email ?? '-' }}</p>
                        <p class="text-xs text-gray-400">{{ $transaction->user->phone ?? '-' }}</p>
                    </div>
                    <div class="ml-auto text-right">
                        <p class="text-[10px] font-bold text-orange-600 uppercase tracking-wider">CUSTOMER</p>
                        <p class="text-xs text-gray-500">Since {{ $transaction->user->created_at->format('M Y') }}</p>
                    </div>
                </div>

                <!-- Item List -->
                <div class="space-y-4 mb-8">
                    <div
                        class="flex text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 pb-2">
                        <span class="flex-1">ITEM</span>
                        <span class="w-16 text-center">QTY</span>
                        <span class="w-24 text-right">PRICE</span>
                    </div>

                    @foreach ($transaction->details as $detail)
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="text-sm font-bold text-gray-900">
                                    {{ $detail->productVariant->product->brand }}</p>
                                <p class="text-xs text-gray-500">Color: {{ $detail->productVariant->color }} | Size:
                                    {{ $detail->productVariant->size }}</p>
                            </div>
                            <div class="w-16 text-center text-sm text-gray-900">{{ $detail->quantity }}</div>
                            <div class="w-24 text-right text-sm font-bold text-gray-900">Rp
                                {{ number_format($detail->price, 0, ',', '.') }}</div>
                        </div>
                    @endforeach
                </div>

                <!-- Totals -->
                <div class="border-t border-dashed border-gray-200 pt-4 mb-8 space-y-2">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Shipping Cost</span>
                        <span>Rp {{ number_format($transaction->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div
                        class="flex justify-between items-center text-xl font-bold text-gray-900 pt-2 border-t border-gray-100 mt-2">
                        <span>TOTAL AMOUNT</span>
                        <span class="text-orange-600">Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                    </div>
                </div>

                @if ($transaction->payment_status === 'pending')
                    <!-- Action Form (Alpine) -->
                    <div x-data="{ notes: '' }">
                        <div class="mb-6">
                            <label
                                class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Verification
                                Note (Optional)</label>
                            <textarea x-model="notes" rows="3"
                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all resize-none shadow-sm"
                                placeholder="Add a note about this transaction..."></textarea>
                        </div>

                        <div class="flex gap-4">
                            <button @click="submitVerification('reject', notes)"
                                class="flex-1 bg-black text-white py-3.5 rounded-xl font-bold hover:bg-gray-800 transition-all flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Reject Payment
                            </button>
                            <button @click="submitVerification('approve', notes)"
                                class="flex-1 bg-orange-600 text-white py-3.5 rounded-xl font-bold hover:bg-orange-700 shadow-lg shadow-orange-500/30 transition-all flex items-center justify-center gap-2 hover:scale-[1.02]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Approve Payment
                            </button>
                        </div>
                    </div>
                @else
                    <!-- Status Display if already processed -->
                    <div class="bg-gray-50 rounded-xl p-6 text-center border border-gray-200">
                        <p class="text-sm text-gray-600 mb-1">Transaction processed by</p>
                        <p class="font-bold text-gray-900">{{ $transaction->verifier->name ?? 'Unknown' }}</p>
                        <p class="text-xs text-gray-500 mt-2">on
                            {{ optional($transaction->verified_at)->format('M d, Y h:i A') ?? '-' }}</p>
                        @if ($transaction->notes)
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Note</p>
                                <p class="text-sm text-gray-800 italic">"{{ $transaction->notes }}"</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        async function submitVerification(action, notes) {
            if (!confirm('Are you sure you want to ' + action + ' this payment?')) return;

            try {
                const response = await fetch('/kasir/api/orders/{{ $transaction->id }}/verify', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        action,
                        notes
                    })
                });

                const data = await response.json();

                if (data.success) {
                    window.location.reload(); // Reload to show status
                } else {
                    alert(data.message || 'Error processing verification');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        }
    </script>
</body>

</html>
