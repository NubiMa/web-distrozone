<x-admin-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{
        hours: {{ $settings['operating_hours'] ??
            json_encode([
                'Monday' => ['start' => '09:00', 'end' => '21:00', 'closed' => false],
                'Tuesday' => ['start' => '09:00', 'end' => '21:00', 'closed' => false],
                'Wednesday' => ['start' => '09:00', 'end' => '21:00', 'closed' => false],
                'Thursday' => ['start' => '09:00', 'end' => '21:00', 'closed' => false],
                'Friday' => ['start' => '09:00', 'end' => '22:00', 'closed' => false],
                'Saturday' => ['start' => '10:00', 'end' => '22:00', 'closed' => false],
                'Sunday' => ['start' => '10:00', 'end' => '21:00', 'closed' => true],
            ]) }}
    }">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf

            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <nav class="flex text-sm text-gray-500 mb-1">
                        <span class="hover:text-gray-700 cursor-pointer">Settings</span>
                        <span class="mx-2">/</span>
                        <span class="text-orange-600 font-medium">Store & Operations</span>
                    </nav>
                    <h1 class="text-3xl font-bold text-gray-900 uppercase">Store Settings</h1>
                    <p class="text-gray-500 mt-1">Manage your DistroZone flagship details, regional preferences, and
                        weekly operational schedules.</p>
                </div>
                <div class="flex gap-3 mt-4 md:mt-0 sticky top-24 z-10">
                    <button type="button" onclick="window.history.back()"
                        class="px-6 py-3 bg-white border border-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition-colors shadow-sm">
                        Discard
                    </button>
                    <button type="submit"
                        class="px-6 py-3 bg-orange-600 text-white font-bold rounded-xl hover:bg-orange-700 transition-colors shadow-lg shadow-orange-600/20">
                        Save Changes
                    </button>
                </div>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div
                    class="mb-6 p-4 bg-green-100 border border-green-200 text-green-800 rounded-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- General Information -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center text-orange-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-gray-900 uppercase">General Information</h2>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Store Name -->
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Store
                                    Name</label>
                                <input type="text" name="store_name"
                                    value="{{ $settings['store_name'] ?? 'DistroZone Flagship - LA' }}"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 font-bold text-gray-900">
                            </div>

                            <!-- Store ID -->
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Store
                                    ID</label>
                                <div class="relative">
                                    <input type="text" value="DZ-8842-X" readonly
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-500 cursor-not-allowed">
                                    <svg class="w-5 h-5 text-gray-400 absolute right-3 top-3.5" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Store
                                    Description</label>
                                <textarea name="store_description" rows="3"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 font-medium text-gray-700 resize-none">{{ $settings['store_description'] ?? 'Premier streetwear destination located in the heart of the Arts District.' }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Location & Contact -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center text-orange-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-gray-900 uppercase">Location & Contact</h2>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Address -->
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Full
                                    Address</label>
                                <div class="relative">
                                    <input type="text" name="store_address"
                                        value="{{ $settings['store_address'] ?? '1200 S Hope St, Los Angeles, CA 90015' }}"
                                        class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 font-bold text-gray-900">
                                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-span-2 md:col-span-1">
                                <label
                                    class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Support
                                    Email</label>
                                <input type="email" name="store_email"
                                    value="{{ $settings['store_email'] ?? 'help@distrozone.com' }}"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 font-medium text-gray-700">
                            </div>

                            <!-- Phone -->
                            <div class="col-span-2 md:col-span-1">
                                <label
                                    class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Phone
                                    Number</label>
                                <input type="text" name="store_phone"
                                    value="{{ $settings['store_phone'] ?? '+1 (213) 555-0199' }}"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 font-medium text-gray-700">
                            </div>

                            <!-- Currency -->
                            <div class="col-span-2 md:col-span-1">
                                <label
                                    class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Currency</label>
                                <select name="store_currency"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 font-medium text-gray-700">
                                    <option value="IDR"
                                        {{ ($settings['store_currency'] ?? 'IDR') == 'IDR' ? 'selected' : '' }}>IDR -
                                        Indonesian Rupiah</option>
                                    <option value="USD"
                                        {{ ($settings['store_currency'] ?? '') == 'USD' ? 'selected' : '' }}>USD - US
                                        Dollar</option>
                                </select>
                            </div>

                            <!-- Timezone -->
                            <div class="col-span-2 md:col-span-1">
                                <label
                                    class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Timezone</label>
                                <select name="store_timezone"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 font-medium text-gray-700">
                                    <option value="Asia/Jakarta"
                                        {{ ($settings['store_timezone'] ?? 'Asia/Jakarta') == 'Asia/Jakarta' ? 'selected' : '' }}>
                                        (GMT+07:00) Jakarta, Bangkok</option>
                                    <option value="America/Los_Angeles"
                                        {{ ($settings['store_timezone'] ?? '') == 'America/Los_Angeles' ? 'selected' : '' }}>
                                        (GMT-08:00) Pacific Time</option>
                                </select>
                            </div>

                            <!-- Map Placeholder -->
                            <div class="col-span-2 mt-2">
                                <div
                                    class="w-full h-40 bg-gray-200 rounded-xl overflow-hidden relative grayscale opacity-70">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/e/ec/USA_California_location_map.svg"
                                        class="w-full h-full object-cover object-center" alt="Map Location">
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/10">
                                        <span
                                            class="text-xs font-bold text-white bg-black/50 px-3 py-1 rounded-full">Map
                                            View</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="lg:col-span-1">
                    <!-- Store Hours -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-32">
                        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center text-orange-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-gray-900 uppercase">Store Hours</h2>
                        </div>
                        <div class="p-6">
                            <p class="text-sm text-gray-500 mb-6">Set your standard opening hours. Changes will reflect
                                on the POS immediately.</p>

                            <div class="space-y-4">
                                <template x-for="(schedule, day) in hours" :key="day">
                                    <div class="flex items-center justify-between group">
                                        <div class="font-bold text-sm text-gray-900 w-24 capitalize" x-text="day">
                                        </div>

                                        <!-- Open -->
                                        <div class="flex items-center gap-2" x-show="!schedule.closed">
                                            <input type="time" :name="`operating_hours[${day}][start]`"
                                                x-model="schedule.start"
                                                class="px-2 py-1.5 bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold text-gray-700 focus:ring-2 focus:ring-orange-500">
                                            <span class="text-gray-400">-</span>
                                            <input type="time" :name="`operating_hours[${day}][end]`"
                                                x-model="schedule.end"
                                                class="px-2 py-1.5 bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold text-gray-700 focus:ring-2 focus:ring-orange-500">
                                        </div>

                                        <!-- Closed -->
                                        <div class="flex-1 text-center" x-show="schedule.closed">
                                            <span
                                                class="px-3 py-1 bg-gray-100 text-gray-500 rounded-md text-xs font-bold uppercase tracking-wider">Closed</span>
                                        </div>

                                        <!-- Toggle -->
                                        <div class="ml-2">
                                            <button type="button" @click="schedule.closed = !schedule.closed"
                                                class="w-10 h-6 rounded-full p-1 transition-colors duration-200 ease-in-out"
                                                :class="!schedule.closed ? 'bg-orange-500' : 'bg-gray-200'">
                                                <div class="w-4 h-4 bg-white rounded-full shadow-sm transform transition-transform duration-200 ease-in-out"
                                                    :class="!schedule.closed ? 'translate-x-4' : 'translate-x-0'"></div>
                                            </button>
                                            <input type="hidden" :name="`operating_hours[${day}][closed]`"
                                                :value="schedule.closed ? 1 : 0">
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <div class="mt-8 p-4 bg-blue-50 rounded-xl flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-xs text-blue-800 leading-relaxed">
                                    Holiday hours must be configured in the "Special Events" calendar tab.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-admin-layout>
