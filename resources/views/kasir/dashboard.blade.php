<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold font-display text-primary mb-8">Kasir Dashboard</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white border border-border p-8 hover:border-accent transition-colors group cursor-pointer">
                <h3 class="text-2xl font-bold text-primary group-hover:text-accent mb-4">POS System</h3>
                <p class="text-gray-500 mb-6">Start a new transaction for offline customer.</p>
                <span class="inline-block bg-primary text-white px-4 py-2 text-sm font-bold uppercase">Launch POS</span>
            </div>

            <div class="bg-white border border-border p-8 hover:border-accent transition-colors group cursor-pointer">
                <h3 class="text-2xl font-bold text-primary group-hover:text-accent mb-4">Online Orders</h3>
                <p class="text-gray-500 mb-6">Verify payments and process pending online orders.</p>
                <span class="inline-block border border-primary text-primary px-4 py-2 text-sm font-bold uppercase">View
                    Orders</span>
            </div>
        </div>

        <div class="mt-8">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-red-600 font-bold hover:underline">Logout</button>
            </form>
        </div>
    </div>
</x-app-layout>
