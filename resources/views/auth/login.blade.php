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
                            <label for="login" class="block text-sm font-medium text-gray-700">Email Address or
                                Username</label>
                            <input id="login" name="login" type="text" autocomplete="username" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-accent focus:border-accent sm:text-sm"
                                value="{{ old('login') }}">
                            @error('login')
                                <div
                                    class="mt-2 text-sm text-red-600 bg-red-50 border border-red-200 rounded-md p-3 flex items-start gap-2 animate-pulse-soft">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <span>{{ $message }}</span>
                                </div>
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

                    @if (session('error'))
                        <div class="rounded-md bg-red-50 p-4 mt-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </form>

                {{-- Auto-refresh CSRF token every 60 minutes to prevent expiration --}}
                <script>
                    // Refresh CSRF token every 60 minutes (before the 120-minute session expires)
                    setInterval(function() {
                        fetch('/csrf-token')
                            .then(response => response.json())
                            .then(data => {
                                document.querySelector('input[name="_token"]').value = data.token;
                                document.querySelector('meta[name="csrf-token"]')?.setAttribute('content', data.token);
                            })
                            .catch(error => console.error('Failed to refresh CSRF token:', error));
                    }, 60 * 60 * 1000); // 60 minutes
                </script>
            </div>
        </div>
    </div>
</x-guest-layout>
