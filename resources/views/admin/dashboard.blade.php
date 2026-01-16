<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold font-display text-primary mb-8">Admin Dashboard</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white border border-border p-6 hover:border-accent transition-colors group cursor-pointer">
                <h3 class="text-lg font-bold text-primary group-hover:text-accent mb-2">Product Management</h3>
                <p class="text-gray-500 text-sm">Manage inventory, add new drops, update prices.</p>
            </div>

            <div class="bg-white border border-border p-6 hover:border-accent transition-colors group cursor-pointer">
                <h3 class="text-lg font-bold text-primary group-hover:text-accent mb-2">Employee Management</h3>
                <p class="text-gray-500 text-sm">Manage cashier accounts and staff access.</p>
            </div>

            <div class="bg-white border border-border p-6 hover:border-accent transition-colors group cursor-pointer">
                <h3 class="text-lg font-bold text-primary group-hover:text-accent mb-2">Reports</h3>
                <p class="text-gray-500 text-sm">View financial reports, sales analytics, and top products.</p>
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
