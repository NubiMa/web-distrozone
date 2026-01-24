<x-guest-layout>
    <div class="flex min-h-[calc(100vh-80px)]">
        <!-- Brand Side (Hidden on Mobile) -->
        <div
            class="hidden lg:flex w-1/2 bg-primary relative overflow-hidden items-center justify-center text-center px-12">
            <div class="absolute inset-0 bg-gradient-brand opacity-20 transform -skew-y-12 scale-150"></div>
            <div class="relative z-10 text-white">
                <h2 class="text-5xl font-bold font-display mb-6">JOIN THE MOVEMENT.</h2>
                <p class="text-xl text-gray-400 font-light max-w-md mx-auto">
                    Create an account to unlock exclusive drops, faster checkout, and member-only rewards.
                </p>
            </div>
        </div>

        <!-- Form Side -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white my-8">
            <div class="w-full max-w-md space-y-8">
                <div>
                    <h2 class="text-3xl font-bold text-primary font-display">Create Account</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Already have an account?
                        <a href="{{ route('login') }}"
                            class="font-medium text-accent hover:text-accent-light transition-colors">Sign in</a>
                    </p>
                </div>

                <form class="mt-8 space-y-6" action="{{ route('register.post') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input id="name" name="name" type="text" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-accent focus:border-accent sm:text-sm"
                                value="{{ old('name') }}">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                            <input id="email" name="email" type="email" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-accent focus:border-accent sm:text-sm"
                                value="{{ old('email') }}">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <input id="phone" name="phone" type="text" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-accent focus:border-accent sm:text-sm"
                                value="{{ old('phone') }}">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>


                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <input id="password" name="password" type="password" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-accent focus:border-accent sm:text-sm">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm
                                Password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-accent focus:border-accent sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold text-white bg-primary hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors uppercase tracking-wide">
                            Create Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>