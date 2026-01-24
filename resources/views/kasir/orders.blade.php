<x-kasir-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header & Title -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Orders & Verification</h1>
                <p class="text-gray-600 mt-1">Manage daily transactions and verify incoming online orders requiring
                    attention.</p>
            </div>

            <a href="{{ route('kasir.orders.index', ['status' => 'pending']) }}" class="hidden md:block">
                <div class="bg-orange-50 border border-orange-100 rounded-2xl p-4 flex items-center gap-4 min-w-[200px]">
                    <div
                        class="h-12 w-12 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold text-xl">
                        {{ $pendingCount }}
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $pendingCount }}</p>
                        <p class="text-xs font-medium text-orange-600 uppercase tracking-wider">PENDING VERIFY</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Filters & Search -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
            <div class="flex flex-col md:flex-row gap-4 justify-between">
                <!-- Search -->
                <div class="flex-1 max-w-lg">
                    <form action="{{ route('kasir.orders.index') }}" method="GET" class="relative">
                        <input type="hidden" name="status" value="{{ $status }}">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" name="search" value="{{ $search }}"
                            placeholder="Search Order ID, Customer..."
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border-transparent focus:bg-white focus:border-orange-500 rounded-xl text-sm transition-all duration-200">
                    </form>
                </div>

                <!-- View Type / Filter (Visual only for now matching design) -->
                <div class="flex items-center gap-2 bg-gray-50 p-1 rounded-xl">
                    <a href="{{ route('kasir.orders.index', array_merge(request()->query(), ['status' => 'history'])) }}"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $status === 'history' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500 hover:text-gray-900' }}">
                        All
                    </a>
                    <span class="w-px h-4 bg-gray-300 mx-1"></span>
                    <a href="{{ route('kasir.orders.index', array_merge(request()->query(), ['status' => 'pending'])) }}"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $status === 'pending' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500 hover:text-gray-900' }}">
                        Pending Verify <span
                            class="ml-1 text-xs bg-orange-100 text-orange-700 px-1.5 py-0.5 rounded-full">{{ $pendingCount }}</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs (Status) -->
        <div class="flex flex-wrap gap-2 mb-6">
            @php
                $tabs = [
                    'pending' => 'Pending Verify',
                    'verified' => 'Verified',
                    'rejected' => 'Rejected',
                    'history' => 'Order History',
                ];
            @endphp

            @foreach ($tabs as $key => $label)
                <a href="{{ route('kasir.orders.index', ['status' => $key]) }}"
                    class="px-6 py-3 rounded-full text-sm font-bold transition-all duration-200 border 
                   {{ $status === $key
                       ? 'bg-gray-900 text-white border-gray-900 shadow-lg shadow-gray-900/20'
                       : 'bg-white text-gray-500 border-gray-200 hover:border-gray-300 hover:text-gray-900' }}">
                    {{ $label }}
                    @if ($key === 'pending' && $pendingCount > 0 && $status !== 'pending')
                        <span
                            class="ml-2 bg-orange-500 text-white text-[10px] px-1.5 py-0.5 rounded-full">{{ $pendingCount }}</span>
                    @endif
                </a>
            @endforeach
        </div>

        <!-- Orders Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Order ID</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Date/Time</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Customer</th>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Type</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Total</th>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($orders as $order)
                            <tr class="hover:bg-gray-50/80 transition-colors group">
                                <td class="px-6 py-4">
                                    <span class="font-bold text-gray-900">{{ $order->transaction_code }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-medium text-gray-900">{{ $order->created_at->format('M d, Y') }}</span>
                                        <span
                                            class="text-xs text-gray-500">{{ $order->created_at->format('h:i A') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs">
                                            {{ substr($order->user->name ?? 'G', 0, 2) }}
                                        </div>
                                        <span
                                            class="text-sm font-medium text-gray-900">{{ $order->user->name ?? 'Guest' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($order->transaction_type == 'online')
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-600 border border-blue-100">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                            </svg>
                                            Online
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            In-Store
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-bold text-gray-900">Rp
                                        {{ number_format($order->total, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($order->payment_status == 'verified' || $order->order_status == 'completed')
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                            ✓ Verified
                                        </span>
                                    @elseif($order->payment_status == 'pending')
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-700">
                                            ⏳ Pending
                                        </span>
                                    @elseif($order->payment_status == 'rejected')
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                            ✕ Rejected
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if ($order->payment_status == 'pending' && $order->transaction_type == 'online')
                                        <a href="{{ route('orders.verify', $order->id) }}"
                                            class="inline-flex items-center px-3 py-1.5 bg-orange-600 text-white text-xs font-bold rounded-lg hover:bg-orange-700 transition-colors shadow-sm shadow-orange-500/30">
                                            Verify
                                            <svg class="ml-1.5 w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    @else
                                        <button class="text-gray-400 hover:text-gray-600 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-500">
                                        <div
                                            class="h-16 w-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                        </div>
                                        <p class="text-base font-medium text-gray-900">No orders found</p>
                                        <p class="text-sm mt-1">Try adjusting your search or filters</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div
                class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm text-gray-500">
                    Showing <span class="font-medium">{{ $orders->firstItem() ?? 0 }}</span> to <span
                        class="font-medium">{{ $orders->lastItem() ?? 0 }}</span> of <span
                        class="font-medium">{{ $orders->total() }}</span> orders
                </p>
                @if ($orders->hasPages())
                    <div class="flex gap-2">
                        {{-- Previous --}}
                        @if ($orders->onFirstPage())
                            <span
                                class="flex items-center px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-300 bg-white cursor-not-allowed">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                                Previous
                            </span>
                        @else
                            <a href="{{ $orders->previousPageUrl() }}"
                                class="flex items-center px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 bg-white hover:bg-gray-50 hover:text-gray-900 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                                Previous
                            </a>
                        @endif

                        {{-- Next --}}
                        @if ($orders->hasMorePages())
                            <a href="{{ $orders->nextPageUrl() }}"
                                class="flex items-center px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 bg-white hover:bg-gray-50 hover:text-gray-900 transition-colors">
                                Next
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        @else
                            <span
                                class="flex items-center px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-300 bg-white cursor-not-allowed">
                                Next
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Verification Dialog (Alpine) -->
    <div x-data="{ open: false, orderId: null, orderCode: '', notes: '' }"
        @open-verification.window="open = true; orderId = $event.detail.id; orderCode = $event.detail.code"
        x-show="open" x-cloak class="relative z-50">

        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="open = false" x-transition.opacity></div>

        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 animate-scale-up" @click.stop x-transition>
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Verify Order</h3>
                        <p class="text-sm text-gray-500 mt-1">Order #<span x-text="orderCode"></span></p>
                    </div>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Internal Notes (Optional)</label>
                    <textarea x-model="notes" rows="3"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-shadow resize-none"
                        placeholder="Add any notes about this verification..."></textarea>
                </div>

                <div class="flex gap-3">
                    <button @click="submitVerification(orderId, 'reject', notes)"
                        class="flex-1 px-4 py-3 rounded-xl border-2 border-red-100 text-red-600 font-bold hover:bg-red-50 hover:border-red-200 transition-colors">
                        Reject
                    </button>
                    <button @click="submitVerification(orderId, 'approve', notes)"
                        class="flex-1 px-4 py-3 rounded-xl bg-green-600 text-white font-bold hover:bg-green-700 shadow-lg shadow-green-500/30 transition-all hover:scale-[1.02]">
                        Approve Payment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openVerificationModal(id, code) {
            window.dispatchEvent(new CustomEvent('open-verification', {
                detail: {
                    id,
                    code
                }
            }));
        }

        async function submitVerification(id, action, notes) {
            if (!confirm('Are you sure you want to ' + action + ' this order?')) return;

            try {
                const response = await fetch(`/kasir/api/orders/${id}/verify`, {
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
                    window.location.reload(); // Simple reload to refresh state
                } else {
                    alert(data.message || 'Error processing verification');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        }
    </script>
</x-kasir-layout>
