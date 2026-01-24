<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="posSystem()">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">POS System</h1>
                <p class="text-gray-600 mt-1">Cashier: {{ Auth::user()->name }}</p>
            </div>
            <a href="/kasir/dashboard" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-50">
                ← Back to Dashboard
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Product Browser -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold mb-4">Products</h2>

                    <!-- Search Bar -->
                    <div class="mb-4">
                        <input type="text" x-model="searchQuery" @input.debounce.300ms="searchProducts"
                            placeholder="Search products (brand, color, size)..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    </div>

                    <!-- Loading State -->
                    <div x-show="loading" class="text-center py-8">
                        <p class="text-gray-500">Loading products...</p>
                    </div>

                    <!-- Products Grid -->
                    <div x-show="!loading" class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-[600px] overflow-y-auto">
                        <template x-for="product in products" :key="product.id">
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-orange-500 transition cursor-pointer"
                                @click="addToCart(product)">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900" x-text="product.brand"></h3>
                                        <p class="text-sm text-gray-600">
                                            <span
                                                x-text="product.type === 'long_sleeve' ? 'Long Sleeve' : 'Short Sleeve'"></span>
                                            - <span x-text="product.color"></span>
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            Size: <span class="font-medium" x-text="product.size"></span>
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-orange-600 font-bold"
                                            x-text="formatPrice(product.selling_price)"></p>
                                        <p class="text-xs text-gray-500">Stock: <span x-text="product.stock"></span></p>
                                    </div>
                                </div>
                                <button
                                    class="w-full mt-2 bg-gray-900 text-white py-1 text-sm rounded hover:bg-orange-600 transition">
                                    Add to Cart
                                </button>
                            </div>
                        </template>
                    </div>

                    <!-- Empty State -->
                    <div x-show="!loading && products.length === 0" class="text-center py-8">
                        <p class="text-gray-500">No products found</p>
                    </div>
                </div>
            </div>

            <!-- Right: Cart & Payment -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-24">
                    <h2 class="text-xl font-bold mb-4">Shopping Cart</h2>

                    <!-- Cart Items -->
                    <div class="space-y-3 max-h-[300px] overflow-y-auto mb-4">
                        <template x-for="item in cart" :key="item.product_id">
                            <div class="border border-gray-200 rounded p-3">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-sm" x-text="item.brand"></h4>
                                        <p class="text-xs text-gray-600">
                                            <span x-text="item.type === 'long_sleeve' ? 'Long' : 'Short'"></span> -
                                            <span x-text="item.color"></span> -
                                            <span x-text="item.size"></span>
                                        </p>
                                        <p class="text-sm font-semibold text-orange-600"
                                            x-text="formatPrice(item.price)"></p>
                                    </div>
                                    <button @click="removeFromCart(item.product_id)"
                                        class="text-red-500 hover:text-red-700 text-sm">
                                        ✕
                                    </button>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <button @click="decreaseQuantity(item.product_id)"
                                            class="w-6 h-6 border border-gray-300 rounded flex items-center justify-center hover:bg-gray-100">
                                            −
                                        </button>
                                        <span class="w-8 text-center font-medium" x-text="item.quantity"></span>
                                        <button @click="increaseQuantity(item.product_id)"
                                            class="w-6 h-6 border border-gray-300 rounded flex items-center justify-center hover:bg-gray-100">
                                            +
                                        </button>
                                    </div>
                                    <span class="font-bold" x-text="formatPrice(item.price * item.quantity)"></span>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Empty Cart -->
                    <div x-show="cart.length === 0" class="text-center py-8 text-gray-500">
                        <p>Cart is empty</p>
                    </div>

                    <!-- Total -->
                    <div class="border-t pt-4 mb-4">
                        <div class="flex justify-between items-center text-lg font-bold">
                            <span>Total:</span>
                            <span class="text-orange-600" x-text="formatPrice(total)"></span>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-4" x-show="cart.length > 0">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                        <select x-model="paymentMethod"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                            <option value="tunai">Cash (Tunai)</option>
                            <option value="qris">QRIS</option>
                            <option value="transfer">Bank Transfer</option>
                        </select>
                    </div>

                    <!-- Notes -->
                    <div class="mb-4" x-show="cart.length > 0">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                        <textarea x-model="notes" rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500"
                            placeholder="Add notes..."></textarea>
                    </div>

                    <!-- Process Button -->
                    <button @click="processTransaction" :disabled="cart.length === 0 || processing"
                        class="w-full bg-orange-600 text-white py-3 rounded-lg font-bold hover:bg-orange-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition">
                        <span x-show="!processing">Process Transaction</span>
                        <span x-show="processing">Processing...</span>
                    </button>

                    <!-- Clear Cart -->
                    <button @click="clearCart" x-show="cart.length > 0" :disabled="processing"
                        class="w-full mt-2 border border-red-500 text-red-500 py-2 rounded-lg hover:bg-red-50 disabled:opacity-50 transition">
                        Clear Cart
                    </button>
                </div>
            </div>
        </div>

        <!-- Receipt Modal -->
        <div x-show="showReceipt" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            @click.self="showReceipt = false">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4" @click.stop>
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold">Transaction Successful</h3>
                        <button @click="showReceipt = false" class="text-gray-500 hover:text-gray-700">✕</button>
                    </div>

                    <!-- Receipt Content -->
                    <div id="receiptContent" class="border-2 border-dashed border-gray-300 p-4 mb-4">
                        <div class="text-center mb-4">
                            <h2 class="text-2xl font-bold">DISTROZONE</h2>
                            <p class="text-xs">Jln. Raya Pegangsaan Timur No.29H</p>
                            <p class="text-xs">Kelapa Gading, Jakarta</p>
                        </div>

                        <div class="border-t border-b border-gray-300 py-2 mb-2 text-xs">
                            <div class="flex justify-between">
                                <span>Transaction ID:</span>
                                <span class="font-mono" x-text="receipt?.id"></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Date:</span>
                                <span x-text="formatDate(receipt?.created_at)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Cashier:</span>
                                <span>{{ Auth::user()->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Payment:</span>
                                <span class="uppercase" x-text="receipt?.payment_method"></span>
                            </div>
                        </div>

                        <div class="mb-2">
                            <template x-for="item in receipt?.details" :key="item.id">
                                <div class="text-xs mb-1">
                                    <div class="font-medium"
                                        x-text="item.product.brand + ' - ' + item.product.color + ' (' + item.product.size + ')'">
                                    </div>
                                    <div class="flex justify-between text-gray-600">
                                        <span>
                                            <span x-text="item.quantity"></span> x
                                            <span x-text="formatPrice(item.price)"></span>
                                        </span>
                                        <span x-text="formatPrice(item.quantity * item.price)"></span>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="border-t border-gray-300 pt-2 text-sm">
                            <div class="flex justify-between font-bold">
                                <span>TOTAL:</span>
                                <span x-text="formatPrice(receipt?.total)"></span>
                            </div>
                        </div>

                        <div class="text-center mt-4 text-xs">
                            <p>Thank you for shopping!</p>
                            <p>Visit us again</p>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <button @click="printReceipt"
                            class="flex-1 bg-gray-900 text-white py-2 rounded hover:bg-gray-800">
                            Print Receipt
                        </button>
                        <button @click="newTransaction"
                            class="flex-1 bg-orange-600 text-white py-2 rounded hover:bg-orange-700">
                            New Transaction
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function posSystem() {
            return {
                // State
                products: [],
                cart: [],
                searchQuery: '',
                loading: false,
                processing: false,
                paymentMethod: 'tunai',
                notes: '',
                showReceipt: false,
                receipt: null,

                // Computed
                get total() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                },

                // Lifecycle
                init() {
                    this.loadProducts();
                },

                // Methods
                async loadProducts() {
                    this.loading = true;
                    try {
                        const response = await fetch('/api/kasir/products?in_stock=1', {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'include'
                        });
                        const data = await response.json();
                        if (data.success) {
                            this.products = data.data.data || [];
                        }
                    } catch (error) {
                        console.error('Error loading products:', error);
                        alert('Failed to load products');
                    } finally {
                        this.loading = false;
                    }
                },

                async searchProducts() {
                    this.loading = true;
                    try {
                        const url = `/api/kasir/products?search=${encodeURIComponent(this.searchQuery)}&in_stock=1`;
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
                            this.products = data.data.data || [];
                        }
                    } catch (error) {
                        console.error('Error searching products:', error);
                    } finally {
                        this.loading = false;
                    }
                },

                addToCart(product) {
                    const existingItem = this.cart.find(item => item.product_id === product.id);

                    if (existingItem) {
                        if (existingItem.quantity < product.stock) {
                            existingItem.quantity++;
                        } else {
                            alert('Cannot add more. Stock limit reached.');
                        }
                    } else {
                        this.cart.push({
                            product_id: product.id,
                            brand: product.brand,
                            type: product.type,
                            color: product.color,
                            size: product.size,
                            price: product.selling_price,
                            quantity: 1,
                            max_stock: product.stock
                        });
                    }
                },

                removeFromCart(productId) {
                    this.cart = this.cart.filter(item => item.product_id !== productId);
                },

                increaseQuantity(productId) {
                    const item = this.cart.find(i => i.product_id === productId);
                    if (item && item.quantity < item.max_stock) {
                        item.quantity++;
                    } else {
                        alert('Cannot add more. Stock limit reached.');
                    }
                },

                decreaseQuantity(productId) {
                    const item = this.cart.find(i => i.product_id === productId);
                    if (item) {
                        if (item.quantity > 1) {
                            item.quantity--;
                        } else {
                            this.removeFromCart(productId);
                        }
                    }
                },

                clearCart() {
                    if (confirm('Are you sure you want to clear the cart?')) {
                        this.cart = [];
                        this.notes = '';
                    }
                },

                async processTransaction() {
                    if (this.cart.length === 0) return;

                    if (!confirm('Process this transaction?')) return;

                    this.processing = true;

                    try {
                        const items = this.cart.map(item => ({
                            product_id: item.product_id,
                            quantity: item.quantity
                        }));

                        const response = await fetch('/api/kasir/transactions', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'include',
                            body: JSON.stringify({
                                payment_method: this.paymentMethod,
                                items: items,
                                notes: this.notes || null
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.receipt = data.data;
                            this.showReceipt = true;
                            this.cart = [];
                            this.notes = '';
                            this.paymentMethod = 'tunai';
                        } else {
                            alert(data.message || 'Failed to process transaction');
                        }
                    } catch (error) {
                        console.error('Error processing transaction:', error);
                        alert('Failed to process transaction');
                    } finally {
                        this.processing = false;
                    }
                },

                printReceipt() {
                    const printWindow = window.open('', '', 'width=300,height=600');
                    const content = document.getElementById('receiptContent').innerHTML;
                    printWindow.document.write(`
                        <html>
                            <head>
                                <title>Receipt - ${this.receipt.id}</title>
                                <style>
                                    body { font-family: monospace; padding: 10px; font-size: 12px; }
                                    .text-center { text-align: center; }
                                    .font-bold { font-weight: bold; }
                                    .mb-4 { margin-bottom: 16px; }
                                    .mb-2 { margin-bottom: 8px; }
                                    .mb-1 { margin-bottom: 4px; }
                                    .text-xs { font-size: 10px; }
                                    .text-sm { font-size: 11px; }
                                    .text-2xl { font-size: 20px; }
                                    .border-t { border-top: 1px solid #000; }
                                    .border-b { border-bottom: 1px solid #000; }
                                    .py-2 { padding-top: 8px; padding-bottom: 8px; }
                                    .pt-2 { padding-top: 8px; }
                                    .mt-4 { margin-top: 16px; }
                                    .flex { display: flex; }
                                    .justify-between { justify-content: space-between; }
                                    .font-medium { font-weight: 500; }
                                    .text-gray-600 { color: #666; }
                                </style>
                            </head>
                            <body>${content}</body>
                        </html>
                    `);
                    printWindow.document.close();
                    printWindow.focus();
                    setTimeout(() => {
                        printWindow.print();
                        printWindow.close();
                    }, 250);
                },

                newTransaction() {
                    this.showReceipt = false;
                    this.receipt = null;
                    this.loadProducts();
                },



                formatPrice(price) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(price);
                },

                formatDate(dateString) {
                    if (!dateString) return '';
                    const date = new Date(dateString);
                    return date.toLocaleString('id-ID');
                }
            }
        }
    </script>
</x-app-layout>