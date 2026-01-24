<div x-data="{
    isOpen: false,
    messages: [
        { text: 'Halo! 👋 Saya asisten virtual toko ini. Ada yang bisa saya bantu?', isUser: false, type: 'text' }
    ],
    userInput: '',
    isLoading: false,

    toggleChat() {
        this.isOpen = !this.isOpen;
        if (this.isOpen) {
            setTimeout(() => this.$refs.chatInput.focus(), 100);
        }
    },

    async sendMessage() {
        if (!this.userInput.trim()) return;

        const text = this.userInput;
        this.messages.push({ text: text, isUser: true, type: 'text' });
        this.userInput = '';
        this.isLoading = true;

        // Scroll to bottom
        this.$nextTick(() => {
            this.$refs.chatContainer.scrollTop = this.$refs.chatContainer.scrollHeight;
        });

        try {
            const response = await fetch('{{ route('chatbot.ask') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify({ message: text })
            });

            const data = await response.json();

            this.messages.push({
                text: data.text,
                isUser: false,
                type: data.type,
                data: data.data || []
            });

        } catch (error) {
            this.messages.push({ text: 'Maaf, terjadi kesalahan koneksi.', isUser: false, type: 'text' });
        } finally {
            this.isLoading = false;
            this.$nextTick(() => {
                this.$refs.chatContainer.scrollTop = this.$refs.chatContainer.scrollHeight;
            });
        }
    }
}" class="fixed bottom-6 right-6 z-50 flex flex-col items-end print:hidden">

    <!-- Chat Window -->
    <div x-show="isOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        class="mb-4 w-80 md:w-96 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden flex flex-col h-[500px]">

        <!-- Header -->
        <div class="bg-gradient-to-r from-orange-600 to-orange-500 p-4 flex justify-between items-center text-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-sm">DistroZone AI</h3>
                    <div class="flex items-center gap-1.5 opacity-90">
                        <span class="relative flex h-2 w-2">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-300"></span>
                        </span>
                        <p class="text-xs">Online</p>
                    </div>
                </div>
            </div>
            <button @click="toggleChat"
                class="text-white/80 hover:text-white transition-colors p-1 hover:bg-white/10 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Messages Area -->
        <div x-ref="chatContainer" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50 scroll-smooth">
            <template x-for="(msg, index) in messages" :key="index">
                <div :class="msg.isUser ? 'flex justify-end' : 'flex justify-start'">

                    <!-- Bot Avatar -->
                    <div x-show="!msg.isUser"
                        class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center shrink-0 mr-2 mt-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>

                    <!-- Message Bubble -->
                    <div class="max-w-[85%] space-y-2">
                        <div :class="msg.isUser ? 'bg-orange-600 text-white rounded-2xl rounded-tr-sm' :
                            'bg-white text-gray-800 border border-gray-100 shadow-sm rounded-2xl rounded-tl-sm'"
                            class="px-4 py-3 text-sm leading-relaxed whitespace-pre-wrap" x-text="msg.text"></div>

                        <!-- Product Cards (if type is 'products') -->
                        <template x-if="msg.type === 'products' && msg.data">
                            <div class="flex gap-3 overflow-x-auto pb-2 snap-x">
                                <template x-for="product in msg.data" :key="product.id">
                                    <a :href="product.url"
                                        class="snap-center shrink-0 w-32 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow block no-underline group">
                                        <div class="aspect-square bg-gray-100 relative">
                                            <img :src="product.image" class="w-full h-full object-cover">
                                        </div>
                                        <div class="p-2">
                                            <p class="text-xs font-bold text-gray-900 truncate" x-text="product.name">
                                            </p>
                                            <p class="text-xs text-orange-600 font-bold mt-1" x-text="product.price">
                                            </p>
                                        </div>
                                    </a>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <!-- Loading Indicator -->
            <div x-show="isLoading" class="flex justify-start">
                <div
                    class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center shrink-0 mr-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div
                    class="bg-white border border-gray-100 shadow-sm rounded-2xl rounded-tl-sm px-4 py-3 flex gap-1 items-center">
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce delay-100"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce delay-200"></div>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-4 bg-white border-t border-gray-100">
            <form @submit.prevent="sendMessage" class="flex items-center gap-2">
                <input x-ref="chatInput" type="text" x-model="userInput" placeholder="Tanya sesuatu..."
                    class="flex-1 px-4 py-2 bg-gray-50 border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm">
                <button type="submit" :disabled="!userInput.trim() || isLoading"
                    class="p-2 bg-orange-600 text-white rounded-full hover:bg-orange-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors shadow-sm">
                    <svg class="w-5 h-5 translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Floating Button -->
    <button @click="toggleChat"
        class="w-14 h-14 bg-orange-600 text-white rounded-full shadow-lg hover:bg-orange-700 hover:scale-110 transition-all duration-300 flex items-center justify-center group relative z-50">

        <div class="absolute inset-0 bg-white/20 rounded-full animate-ping opacity-75 group-hover:opacity-100"
            x-show="!isOpen"></div>

        <svg x-show="!isOpen" class="w-7 h-7 relative z-10 transition-transform duration-300 group-hover:rotate-12"
            fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>

        <svg x-show="isOpen" class="w-7 h-7 relative z-10 transition-transform duration-300 rotate-90" fill="none"
            stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>

        <!-- Notification Badge -->
        <span x-show="!isOpen" class="absolute -top-1 -right-1 flex h-4 w-4">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500"></span>
        </span>
    </button>
</div>
