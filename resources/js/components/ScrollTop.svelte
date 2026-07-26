<script>
    import { ChevronUp } from 'lucide-svelte';
    import { onMount } from 'svelte';
    import { fade, scale } from 'svelte/transition';

    let showButton = $state(false);

    onMount(() => {
        const handleScroll = () => {
            showButton = window.scrollY > 300;
        };
        
        window.addEventListener('scroll', handleScroll, { passive: true });
        return () => window.removeEventListener('scroll', handleScroll);
    });

    const scrollToTop = () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    };
</script>

{#if showButton}
    <button
        onclick={scrollToTop}
        transition:fade={{ duration: 300 }}
        class="fixed bottom-24 right-6 z-50 flex items-center justify-center w-12 h-12 bg-primary text-white rounded-full shadow-lg hover:bg-primary-hover hover:shadow-xl hover:-translate-y-1 hover:scale-110 transition-all duration-300 ease-in-out"
        aria-label="Scroll to top"
    >
        <ChevronUp size={24} />
    </button>
{/if}
