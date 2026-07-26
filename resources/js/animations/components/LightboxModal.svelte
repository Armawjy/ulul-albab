<script>
    import { fade, scale } from 'svelte/transition';
    import { X } from 'lucide-svelte';
    import { onMount } from 'svelte';

    let { 
        image = null, 
        title = '', 
        category = '', 
        onClose = () => {} 
    } = $props();

    const handleKeydown = (e) => {
        if (e.key === 'Escape') {
            onClose();
        }
    };
</script>

<svelte:window onkeydown={handleKeydown} />

{#if image}
    <!-- Overlay Hitam Transparan -->
    <div 
        transition:fade={{ duration: 300 }}
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4 md:p-10"
        onclick={onClose}
        role="dialog"
        aria-modal="true"
        tabindex="-1"
    >
        <!-- Modal Content with Zoom transition -->
        <div 
            transition:scale={{ duration: 400, start: 0.9, opacity: 0 }}
            class="relative max-w-5xl max-h-[85vh] w-full bg-bg-base rounded-[20px] overflow-hidden shadow-2xl flex flex-col"
            onclick={(e) => e.stopPropagation()}
        >
            <!-- Close Button -->
            <button 
                onclick={onClose}
                class="absolute top-4 right-4 z-10 w-10 h-10 rounded-full bg-black/50 hover:bg-black/80 text-white flex items-center justify-center transition-colors shadow-md"
                aria-label="Tutup Preview"
            >
                <X size={20} />
            </button>

            <!-- Image Container -->
            <div class="relative flex-grow overflow-hidden bg-black flex items-center justify-center max-h-[70vh]">
                <img 
                    src={image} 
                    alt={title} 
                    class="max-w-full max-h-[70vh] object-contain transition-transform duration-500"
                />
            </div>

            <!-- Footer Info -->
            {#if title || category}
                <div class="p-6 bg-white flex items-center justify-between border-t border-border-color">
                    <div>
                        {#if category}
                            <span class="inline-block px-3 py-1 bg-primary/10 text-primary text-xs font-semibold rounded-full mb-1">
                                {category}
                            </span>
                        {/if}
                        <h3 class="font-heading font-bold text-lg text-text-title">{title || 'Galeri Kegiatan'}</h3>
                    </div>
                    <button 
                        onclick={onClose}
                        class="px-5 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-text-title text-sm font-medium transition-colors"
                    >
                        Tutup
                    </button>
                </div>
            {/if}
        </div>
    </div>
{/if}
