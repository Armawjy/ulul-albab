    <script>
        import { X } from 'lucide-svelte';
        import { fade, scale } from 'svelte/transition';
        import Button from './Button.svelte';

        let { show = $bindable(false), title = '', children, onclose } = $props();

        const close = () => {
            show = false;
            if (onclose) onclose();
        };

        // Prevent scrolling on body when modal is open
        $effect(() => {
            if (show) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = 'auto';
            }
        });
    </script>

    {#if show}
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" transition:fade={{ duration: 200 }}>
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick={close}></div>
            
            <div 
                class="relative w-full max-w-2xl bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col max-h-[90vh]"
                transition:scale={{ duration: 200, start: 0.95 }}
            >
                <div class="flex items-center justify-between p-6 border-b border-border-color">
                    <h2 class="text-xl font-bold text-text-title font-heading">{title}</h2>
                    <button onclick={close} class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-colors">
                        <X size={20} />
                    </button>
                </div>
                
                <div class="p-6 overflow-y-auto">
                    {@render children?.()}
                </div>
            </div>
        </div>
    {/if}
