<x-kasir-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="onlineOrders()">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Online Orders Verification</h1>
                <p class="text-gray-600 mt-1">Verify customer payments and process orders</p>
            </div>
            <a href="/kasir/dashboard" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-50">
                ← Back to Dashboard
            </a>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-sm text-gray-600">Pending Orders</p>
                        <p class="text-2xl font-bold text-orange-600" x-text="pendingCount"></p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                        <span class="text-2xl">⏳</span>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-sm text-gray-600">Verified Today</p>
                        <p class="text-2xl font-bold text-green-600" x-text="verifiedToday"></p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <span class="text-2xl">✓</span>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-sm text-gray-600">Total Amount</p>
                        <p class="text-xl font-bold text-gray-900" x-text="formatPrice(totalAmount)"></p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-2xl">💰</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <button @click="activeTab = 'pending'"
                        :class="activeTab === 'pending' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-4 px-6 border-b-2 font-medium text-sm">
                        Pending Orders (<span x-text="pendingCount"></span>)
                    </button>
                    <button @click="activeTab = 'history'"
                        :class="activeTab === 'history' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-4 px-6 border-b-2 font-medium text-sm">
                        Order History
                    </button>
                </nav>
            </div>
        </div>

        <!-- Pending Orders Tab -->
        <div x-show="activeTab === 'pending'" class="space-y-4">
            <!-- Loading State -->
            <div x-show="loading" class="text-center py-12">
                <p class="text-gray-500">Loading orders...</p>
            </div>

            <!-- Orders List -->
            <template x-for="order in pendingOrders" :key="order.id">
                <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <div class="flex items-center gap-3">
                                <h3 class="text-lg font-bold text-gray-900">Order #<span x-text="order.id"></span></h3>
                                <span
                                    class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">
                                    PENDING VERIFICATION
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">
                                <strong>Customer:</strong> <span x-text="order.user?.name"></span>
                                (<span x-text="order.user?.email"></span>)
                            </p>
                            <p class="text-sm text-gray-600">
                                <strong>Date:</strong> <span x-text="formatDate(order.created_at)"></span>
                            </p>
                            <p class="text-sm text-gray-600">
                                <strong>Payment:</strong> <span class="uppercase" x-text="order.payment_method"></span>
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-orange-600" x-text="formatPrice(order.total)"></p>
                            <p class="text-xs text-gray-500">
                                Subtotal: <span x-text="formatPrice(order.subtotal)"></span><br>
                                Shipping: <span x-text="formatPrice(order.shipping_cost)"></span>
                            </p>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="border-t border-gray-200 pt-4 mb-4">
                        <h4 class="font-semibold text-sm text-gray-700 mb-2">Order Items:</h4>
                        <div class="space-y-2">
                            <template x-for="item in order.details" :key="item.id">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">
                                        <span x-text="item.quantity"></span>x
                                        <span x-text="item.product.brand"></span> -
                                        <span x-text="item.product.color"></span>
                                        (<span x-text="item.product.size"></span>)
                                    </span>
                                    <span class="font-medium" x-text="formatPrice(item.price * item.quantity)"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Shipping Info -->
                    <div class="bg-gray-50 rounded p-3 mb-4" x-show="order.shipping_address">
                        <h4 class="font-semibold text-sm text-gray-700 mb-1">Shipping Address:</h4>
                        <p class="text-sm text-gray-600" x-text="order.shipping_address"></p>
                        <p class="text-sm text-gray-600" x-show="order.shipping_destination">
                            <strong>Destination:</strong> <span x-text="order.shipping_destination"></span>
                        </p>
                    </div>

                    <!-- Notes -->
                    <div class="bg-blue-50 rounded p-3 mb-4" x-show="order.notes">
                        <h4 class="font-semibold text-sm text-gray-700 mb-1">Customer Notes:</h4>
                        <p class="text-sm text-gray-600" x-text="order.notes"></p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3">
                        <button @click="verifyOrder(order.id, 'approve')"
                            class="flex-1 bg-green-600 text-white py-3 rounded-lg font-bold hover:bg-green-700 transition">
                            ✓ Approve Payment
                        </button>
                        <button @click="verifyOrder(order.id, 'reject')"
                            class="flex-1 bg-red-600 text-white py-3 rounded-lg font-bold hover:bg-red-700 transition">
                            ✕ Reject Payment
                        </button>
                    </div>
                </div>
            </template>

            <!-- Empty State -->
            <div x-show="!loading && pendingOrders.length === 0" class="bg-white rounded-lg shadow p-12 text-center">
                <div class="text-6xl mb-4">✓</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">All Caught Up!</h3>
                <p class="text-gray-600">No pending orders to verify at the moment.</p>
            </div>
        </div>

        <!-- History Tab -->
        <div x-show="activeTab === 'history'" class="space-y-4">
            <!-- Date Filter -->
            <div class="bg-white rounded-lg shadow p-4 mb-4">
                <div class="flex gap-4 items-end">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="date" x-model="startDate"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <input type="date" x-model="endDate"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                    </div>
                    <button @click="loadHistory"
                        class="px-6 py-2 bg-orange-600 text-white rounded-lg font-bold hover:bg-orange-700">
                        Filter
                    </button>
                </div>
            </div>

            <!-- History List -->
            <template x-for="order in historyOrders" :key="order.id">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-lg font-bold text-gray-900">Order #<span x-text="order.id"></span></h3>
                                <span :class="{
                                    'bg-green-100 text-green-800': order.payment_status === 'verified',
                                    'bg-red-100 text-red-800': order.payment_status === 'rejected'
                                }" class="px-3 py-1 text-xs font-semibold rounded-full uppercase"
                                    x-text="order.payment_status"></span>
                            </div>
                            <p class="text-sm text-gray-600">
                                <strong>Customer:</strong> <span x-text="order.user?.name"></span>
                            </p>
                            <p class="text-sm text-gray-600">
                                <strong>Verified:</strong> <span x-text="formatDate(order.verified_at)"></span>
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-xl font-bold text-gray-900" x-text="formatPrice(order.total)"></p>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Empty History -->
            <div x-show="!loading && historyOrders.length === 0" class="bg-white rounded-lg shadow p-12 text-center">
                <div class="text-6xl mb-4">📋</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No History</h3>
                <p class="text-gray-600">No orders found for the selected date range.</p>
            </div>
        </div>

        <!-- Verification Modal -->
        <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            @click.self="showModal = false">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6" @click.stop>
                <h3 class="text-xl font-bold mb-4" x-text="modalTitle"></h3>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                    <textarea x-model="modalNotes" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500"
                        placeholder="Add notes about this verification..."></textarea>
                </div>

                <div class="flex gap-3">
                    <button @click="showModal = false"
                        class="flex-1 border border-gray-300 text-gray-700 py-2 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button @click="confirmVerification"
                        :class="modalAction === 'approve' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700'"
                        class="flex-1 text-white py-2 rounded-lg font-bold">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function onlineOrders() {
            return {
                // State
                activeTab: 'pending',
                pendingOrders: [],
                historyOrders: [],
                loading: false,
                showModal: false,
                modalTitle: '',
                modalAction: '',
                modalOrderId: null,
                modalNotes: '',
                startDate: '',
                endDate: '',

                // Stats
                pendingCount: 0,
                verifiedToday: 0,
                totalAmount: 0,

                // Lifecycle
                init() {
                    this.setDefaultDates();
                    this.loadPendingOrders();
                    this.loadStats();
                },

                setDefaultDates() {
                    const today = new Date();
                    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
                    this.startDate = firstDay.toISOString().split('T')[0];
                    this.endDate = today.toISOString().split('T')[0];
                },

                async loadPendingOrders() {
                    this.loading = true;
                    try {
                        const response = await fetch('/api/kasir/orders/pending', {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'include'
                        });
                        const data = await response.json();
                        if (data.success) {
                            this.pendingOrders = data.data.data || [];
                            this.pendingCount = this.pendingOrders.length;
                            this.calculateTotalAmount();
                        }
                    } catch (error) {
                        console.error('Error loading pending orders:', error);
                        alert('Failed to load pending orders');
                    } finally {
                        this.loading = false;
                    }
                },

                async loadHistory() {
                    this.loading = true;
                    try {
                        const url = `/api/kasir/transactions?type=online&start_date=${this.startDate}&end_date=${this.endDate}`;
                        const response = await fetch(url, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'include'
                        });
                        const data = await response.json();
                        if (data.success) {
                            this.historyOrders = data.data.data || [];
                        }
                    } catch (error) {
                        console.error('Error loading history:', error);
                    } finally {
                        this.loading = false;
                    }
                },

                async loadStats() {
                    // Load verified today count
                    const today = new Date().toISOString().split('T')[0];
                    try {
                        const response = await fetch(`/api/kasir/transactions?type=online&start_date=${today}&end_date=${today}`, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'include'
                        });
                        const data = await response.json();
                        if (data.success) {
                            const verified = data.data.data.filter(o => o.payment_status === 'verified');
                            this.verifiedToday = verified.length;
                        }
                    } catch (error) {
                        console.error('Error loading stats:', error);
                    }
                },

                calculateTotalAmount() {
                    this.totalAmount = this.pendingOrders.reduce((sum, order) => sum + parseFloat(order.total || 0), 0);
                },

                verifyOrder(orderId, action) {
                    this.modalOrderId = orderId;
                    this.modalAction = action;
                    this.modalTitle = action === 'approve' ? 'Approve Payment' : 'Reject Payment';
                    this.modalNotes = '';
                    this.showModal = true;
                },

                async confirmVerification() {
                    try {
                        const response = await fetch(`/api/kasir/orders/${this.modalOrderId}/verify`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'include',
                            body: JSON.stringify({
                                action: this.modalAction,
                                notes: this.modalNotes || null
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            alert(data.message || 'Order verified successfully');
                            this.showModal = false;
                            this.loadPendingOrders();
                            this.loadStats();
                        } else {
                            alert(data.message || 'Failed to verify order');
                        }
                    } catch (error) {
                        console.error('Error verifying order:', error);
                        alert('Failed to verify order');
                    }
                },

                formatPrice(price) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(price || 0);
                },

                formatDate(dateString) {
                    if (!dateString) return '-';
                    const date = new Date(dateString);
                    return date.toLocaleString('id-ID', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                }
            }
        }
    </script>
</x-kasir-layout>