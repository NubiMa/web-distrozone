<div x-data="{
    show: false,
    title: 'Are you sure?',
    message: 'This action cannot be undone.',
    type: 'danger', // danger, warning, info
    confirmLabel: 'Delete',
    cancelLabel: 'Cancel',
    callback: null,

    confirm() {
        if (this.callback) {
            // function callback if passed via JS (custom event)
            window.dispatchEvent(new CustomEvent(this.callback));
        } else {
            // Default behavior if triggered by form interception (to be implemented if needed, 
            // but for now, we'll assume the caller listens for 'action-confirmed' or passes a callback name)
            this.$dispatch('action-confirmed');
        }
        this.show = false;
    }
}"
    @confirm-action.window="
    show = true;
    title = $event.detail.title || 'Are you sure?';
    message = $event.detail.message || 'This action cannot be undone.';
    type = $event.detail.type || 'danger';
    confirmLabel = $event.detail.confirmLabel || 'Confirm';
    cancelLabel = $event.detail.cancelLabel || 'Cancel';
    callback = $event.detail.callback || null;
"
    x-show="show" style="display: none;" class="fixed inset-0 z-[110] overflow-y-auto" aria-labelledby="modal-title"
    role="dialog" aria-modal="true">

    <!-- Backdrop -->
    <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm" @click="show = false">
    </div>

    <div class="flex min-h-screen items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <!-- Modal Panel -->
        <div x-show="show" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <!-- Icon -->
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full sm:mx-0 sm:h-10 sm:w-10"
                        :class="{
                            'bg-red-100': type === 'danger',
                            'bg-yellow-100': type === 'warning',
                            'bg-blue-100': type === 'info'
                        }">
                        <template x-if="type === 'danger'">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </template>
                        <template x-if="type === 'warning'">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </template>
                        <template x-if="type === 'info'">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                            </svg>
                        </template>
                    </div>

                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                        <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title" x-text="title">
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" x-text="message"></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                <!-- Confirm Button -->
                <button type="button" @click="confirm()"
                    class="inline-flex w-full justify-center rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm sm:ml-3 sm:w-auto transition-colors"
                    :class="{
                        'bg-red-600 hover:bg-red-500': type === 'danger',
                        'bg-yellow-600 hover:bg-yellow-500': type === 'warning',
                        'bg-blue-600 hover:bg-blue-500': type === 'info'
                    }"
                    x-text="confirmLabel">
                </button>

                <!-- Cancel Button -->
                <button type="button" @click="show = false"
                    class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
                    x-text="cancelLabel">
                </button>
            </div>
        </div>
    </div>
</div>
