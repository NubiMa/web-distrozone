<x-guest-layout>
    <div class="flex min-h-[calc(100vh-80px)]">
        <!-- Brand Side (Hidden on Mobile) -->
        <div
            class="hidden lg:flex w-1/2 bg-primary relative overflow-hidden items-center justify-center text-center px-12">
            <div class="absolute inset-0 bg-gradient-brand opacity-20 transform skew-y-12 scale-150"></div>
            <div class="relative z-10 text-white">
                <h2 class="text-5xl font-bold font-display mb-6">WELCOME BACK.</h2>
                <p class="text-xl text-gray-400 font-light max-w-md mx-auto">
                    Continue your journey with DistroZone. manage your orders, check new drops, and more.
                </p>
            </div>
        </div>

        <!-- Form Side -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white">
            <div class="w-full max-w-md space-y-8">
                <div>
                    <h2 class="text-3xl font-bold text-primary font-display">Sign In</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        New here?
                        <a href="{{ route('register') }}"
                            class="font-medium text-accent hover:text-accent-light transition-colors">Create an
                            account</a>
                    </p>
                </div>

                <form class="mt-8 space-y-6" action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                            <input id="email" name="email" type="email" autocomplete="email" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-accent focus:border-accent sm:text-sm"
                                value="{{ old('email') }}">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <input id="password" name="password" type="password" autocomplete="current-password"
                                required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-accent focus:border-accent sm:text-sm">
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember-me" name="remember-me" type="checkbox"
                                class="h-4 w-4 text-accent focus:ring-accent border-gray-300 rounded">
                            <label for="remember-me" class="ml-2 block text-sm text-gray-900">Remember me</label>
                        </div>

                        <div class="text-sm">
                            <a href="#" class="font-medium text-accent hover:text-accent-light">Forgot
                                password?</a>
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold text-white bg-primary hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors uppercase tracking-wide">
                            Sign In
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
