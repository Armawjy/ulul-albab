<script>
    import Navbar from '../components/Navbar.svelte';
    import Footer from '../components/Footer.svelte';
    import WhatsAppButton from '../components/WhatsAppButton.svelte';
    import ScrollTop from '../components/ScrollTop.svelte';

    let { children } = $props();
</script>

<div class="min-h-screen flex flex-col font-sans bg-bg-base text-text-body overflow-x-hidden">
    <Navbar />
    
    <main class="flex-grow">
        {@render children?.()}
    </main>
    
    <Footer />
    
    <WhatsAppButton />
    <ScrollTop />
</div>

<style>
    :global(html) {
        scroll-behavior: smooth;
    }
    :global(body) {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
</style>
