<script>
    import { fade, scale } from 'svelte/transition';
    import logo from '../../assets/logo/ub.jpeg';
    
    let { 
        visible = true, 
        text = 'Memuat Pesantren Ulul Albab...' 
    } = $props();
</script>

{#if visible}
    <div 
        transition:fade={{ duration: 500 }}
        class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-white/95 backdrop-blur-md"
    >
        <div transition:scale={{ duration: 400, start: 0.8 }} class="flex flex-col items-center">
            <!-- Modern Animated Spinner with Logo inside -->
            <div class="relative flex items-center justify-center w-24 h-24 mb-6">
                <!-- Outer spinning ring -->
                <div class="absolute inset-0 border-4 border-primary/20 border-t-primary rounded-full animate-spin"></div>
                <!-- Inner pulse ring -->
                <div class="absolute inset-2 border-2 border-primary-hover/30 border-b-primary-hover rounded-full animate-[spin_1.5s_linear_infinite_reverse]"></div>
                <!-- Center logo -->
                <img 
                    src={logo} 
                    alt="Logo" 
                    class="w-12 h-12 object-contain rounded-full bg-white p-1 shadow-sm animate-pulse" 
                />
            </div>

            <!-- Loading text -->
            <h3 class="font-heading font-semibold text-lg text-text-title tracking-wide animate-pulse">
                {text}
            </h3>
            <p class="text-xs text-text-body mt-1">SMA/MA Unggulan Berbasis Pesantren</p>
        </div>
    </div>
{/if}
