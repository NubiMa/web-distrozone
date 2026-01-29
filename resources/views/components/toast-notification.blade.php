<div x-data="{
    notifications: [],
    add(type, message) {
        const id = Date.now();
        this.notifications.push({
            id: id,
            type: type,
            message: message,
            show: false
        });

        // Trigger animation in next tick
        this.$nextTick(() => {
            const index = this.notifications.findIndex(n => n.id === id);
            if (index > -1) this.notifications[index].show = true;
        });

        // Auto dismiss
        setTimeout(() => {
            this.remove(id);
        }, 4000);
    },
    remove(id) {
        const index = this.notifications.findIndex(n => n.id === id);
        if (index > -1) {
            this.notifications[index].show = false;
            // Wait for animation to finish before removing from DOM
            setTimeout(() => {
                this.notifications = this.notifications.filter(n => n.id !== id);
            }, 300);
        }
    },
    init() {
        @if(session('success'))
        this.add('success', '{{ session('success') }}');
        @endif
        @if(session('error'))
        this.add('error', '{{ session('error') }}');
        @endif
        @if(session('warning'))
        this.add('warning', '{{ session('warning') }}');
        @endif
        @if(session('info'))
        this.add('info', '{{ session('info') }}');
        @endif
    }
}" @notify.window="add($event.detail.type, $event.detail.message)"
    class="fixed top-24 right-5 z-[100] flex flex-col gap-3 pointer-events-none">
    <template x-for="note in notifications" :key="note.id">
        <div x-show="note.show" x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="translate-x-full opacity-0"
            class="pointer-events-auto min-w-[320px] max-w-sm bg-white border-l-4 shadow-xl rounded-r-lg overflow-hidden flex items-start p-4 backdrop-blur-md bg-white/95"
            :class="{
                'border-green-500': note.type === 'success',
                'border-red-500': note.type === 'error',
                'border-yellow-500': note.type === 'warning',
                'border-blue-500': note.type === 'info',
            }">
            <!-- Icon -->
            <div class="flex-shrink-0 mr-3">
                <template x-if="note.type === 'success'">
                    <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
                <template x-if="note.type === 'error'">
                    <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
                <template x-if="note.type === 'warning'">
                    <svg class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </template>
                <template x-if="note.type === 'info'">
                    <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
            </div>

            <!-- Content -->
            <div class="flex-1 w-0">
                <p class="text-sm font-bold text-gray-900 capitalize" x-text="note.type"></p>
                <p class="mt-1 text-sm text-gray-500" x-text="note.message"></p>
            </div>

            <!-- Close Button -->
            <button @click="remove(note.id)"
                class="ml-4 flex-shrink-0 text-gray-400 hover:text-gray-500 focus:outline-none">
                <span class="sr-only">Close</span>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </template>
</div>
