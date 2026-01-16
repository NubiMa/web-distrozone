<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="text-3xl font-bold font-display text-primary mb-8">Checkout</h1>

        <form action="{{ route('checkout.store') }}" method="POST" enctype="multipart/form-data"
            class="flex flex-col lg:flex-row gap-12">
            @csrf

            <!-- Shipping Form -->
            <div class="flex-1 space-y-8">
                <div class="bg-white border border-border p-8">
                    <h2 class="text-xl font-bold text-primary mb-6">Shipping Details</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Full Name</label>
                            <input type="text" value="{{ Auth::user()->name }}"
                                class="w-full border-gray-300 rounded focus:border-accent focus:ring-accent" readonly>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Phone Number</label>
                            <input type="text" name="phone" value="{{ Auth::user()->phone }}"
                                class="w-full border-gray-300 rounded focus:border-accent focus:ring-accent" required>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Destination (City/Regency)</label>
                            <select name="shipping_rate_id"
                                class="w-full border-gray-300 rounded focus:border-accent focus:ring-accent" required>
                                <option value="">Select Destination</option>
                                @foreach ($destinations as $dest)
                                    <option value="{{ $dest->id }}">{{ $dest->destination }} (Rp
                                        {{ number_format($dest->rate_per_kg, 0, ',', '.') }}/kg)</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Shipping cost will be calculated based on total weight
                                ({{ ceil($totalQuantity / 3) }} kg).</p>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Full Address</label>
                            <textarea name="address" rows="3" class="w-full border-gray-300 rounded focus:border-accent focus:ring-accent"
                                required>{{ Auth::user()->address }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-border p-8">
                    <h2 class="text-xl font-bold text-primary mb-6">Payment</h2>

                    <div class="space-y-4">
                        <label
                            class="flex items-center gap-3 p-4 border border-gray-200 rounded cursor-pointer hover:border-accent">
                            <input type="radio" name="payment_method" value="transfer_bca"
                                class="text-accent focus:ring-accent" checked>
                            <div>
                                <span class="font-bold block">Bank Transfer (BCA)</span>
                                <span class="text-sm text-gray-500">No. Rek: 1234567890 a/n DistroZone</span>
                            </div>
                        </label>
                    </div>

                    <div class="mt-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Upload Payment Proof</label>
                        <input type="file" name="payment_proof"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                            required>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="w-full lg:w-96">
                <div class="bg-gray-50 p-6 border border-border sticky top-24">
                    <h2 class="text-xl font-bold font-display text-primary mb-6">Your Order</h2>

                    <ul class="space-y-4 mb-6 max-h-60 overflow-y-auto">
                        @foreach ($cart as $item)
                            <li class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ $item['name'] }} x{{ $item['quantity'] }}</span>
                                <span class="font-bold">Rp
                                    {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="border-t border-gray-200 pt-4 space-y-2 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-bold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Total Weight</span>
                            <span class="font-bold">{{ ceil($totalQuantity / 3) }} kg</span>
                        </div>
                        <div class="flex justify-between text-sm text-accent">
                            <span class="italic">*Shipping cost added on confirmation</span>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-primary text-white text-center py-4 font-bold uppercase tracking-wide hover:bg-accent transition-colors">
                        Place Order
                    </button>

                    @if ($errors->any())
                        <div class="mt-4 p-3 bg-red-100 text-red-700 text-sm rounded">
                            {{ $errors->first() }}
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
