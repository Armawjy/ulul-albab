<script>
    import { onMount, onDestroy } from 'svelte';
    import NewsCard from '../components/NewsCard.svelte';
    import Skeleton from '../animations/components/Skeleton.svelte';
    import LightboxModal from '../animations/components/LightboxModal.svelte';
    import { Search } from 'lucide-svelte';
    import api from '../services/axios';
    import { fade, fly } from 'svelte/transition';
    import { flip } from 'svelte/animate';
    import { cubicOut, quartOut } from 'svelte/easing';
    
    const categories = ['Semua Berita', 'Akademik', 'Keasramaan', 'Prestasi', 'Pengumuman'];
    let activeCategory = $state('Semua Berita');
    
    let beritas = $state([]);
    let loading = $state(true);
    let pagination = $state(null);
    let currentPage = $state(1);

    const fetchPublicBerita = async (page = 1) => {
        loading = true;
        try {
            const url = activeCategory === 'Semua Berita' 
                ? `/v1/berita?page=${page}` 
                : `/v1/berita?page=${page}&category=${activeCategory}`;
            const response = await api.get(url);
            beritas = response.data.data;
            pagination = response.data.meta;
            currentPage = page;
        } catch (error) {
            console.error(error);
        } finally {
            loading = false;
        }
    };

    let selectedNews = $state(null);
    let isOpen = $state(false);

    const openLightbox = (item) => {
        selectedNews = item;
        isOpen = true;
    };

    const closeLightbox = () => {
        isOpen = false;
        selectedNews = null;
    };

    let scrollContainer = $state(null);
    let autoPlayInterval;

    const startAutoPlay = () => {
        if (autoPlayInterval) clearInterval(autoPlayInterval);
        autoPlayInterval = setInterval(() => {
            if (scrollContainer) {
                const maxScroll = scrollContainer.scrollWidth - scrollContainer.clientWidth;
                if (maxScroll > 0) {
                    if (scrollContainer.scrollLeft >= maxScroll - 10) {
                        scrollContainer.scrollTo({ left: 0, behavior: 'smooth' });
                    } else {
                        scrollContainer.scrollBy({ left: 340, behavior: 'smooth' });
                    }
                }
            }
        }, 2200);
    };

    const stopAutoPlay = () => {
        if (autoPlayInterval) clearInterval(autoPlayInterval);
    };

    onMount(() => {
        fetchPublicBerita();
        startAutoPlay();
    });

    onDestroy(() => {
        stopAutoPlay();
    });
</script>

<svelte:head>
    <title>Berita & Informasi - Pondok Pesantren Ulul Albab Makassar</title>
</svelte:head>

<div class="pt-24 pb-16 bg-primary-dark relative overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img 
            src="https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
            alt="Berita Background" 
            class="w-full h-full object-cover opacity-20"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-primary-dark to-transparent"></div>
    </div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-3xl animate-fade-up">
            <h1 class="text-4xl md:text-5xl font-heading font-bold text-white mb-6">Berita & Informasi</h1>
            <p class="text-lg text-gray-200">
                Kabar terbaru seputar kegiatan akademik, prestasi santri, dan pengumuman resmi pesantren.
            </p>
        </div>
    </div>
</div>

<section class="py-20 bg-bg-base relative -mt-10 rounded-t-[40px] z-20">
    <div class="container mx-auto px-6">
        
        <div class="flex flex-col lg:flex-row justify-between gap-6 mb-12">
            <!-- Filters -->
            <div class="flex flex-wrap gap-2">
                {#each categories as category}
                    <button 
                        class="px-5 py-2 rounded-full text-sm font-medium transition-all duration-300 transform hover:-translate-y-0.5 {activeCategory === category ? 'bg-primary text-white shadow-md shadow-primary/20' : 'bg-bg-section text-text-title hover:bg-primary/10 hover:text-primary'}"
                        onclick={() => { activeCategory = category; fetchPublicBerita(1); }}
                    >
                        {category}
                    </button>
                {/each}
            </div>
            
            <!-- Search -->
            <div class="relative w-full lg:w-72">
                <input 
                    type="text" 
                    placeholder="Cari berita..." 
                    class="w-full pl-10 pr-4 py-2.5 rounded-full border border-border-color focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-sm transition-all duration-300"
                />
                <Search size={18} class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" />
            </div>
        </div>

        {#key activeCategory}
            <div class="relative w-full">
                <!-- Scroll Container -->
                <div 
                    bind:this={scrollContainer}
                    class="flex flex-nowrap gap-8 overflow-x-auto scroll-smooth py-6 px-4 no-scrollbar"
                    style="scrollbar-width: none; -ms-overflow-style: none;"
                    in:fade={{ duration: 200 }}
                >
                    {#if loading}
                        {#each Array(6) as _}
                            <div class="w-80 sm:w-96 flex-shrink-0">
                                <Skeleton height="h-96" rounded="rounded-[20px]" />
                            </div>
                        {/each}
                    {:else if beritas.length === 0}
                        <div class="w-full py-20 text-center text-text-body">Belum ada berita yang diterbitkan.</div>
                    {:else}
                        {#each beritas as item, i}
                            <div 
                                class="w-80 sm:w-96 flex-shrink-0 transition-transform duration-300 hover:-translate-y-2"
                                in:fly={{ x: 120, duration: 600, delay: (i % 8) * 80, easing: cubicOut }}
                                out:fade={{ duration: 150 }}
                            >
                                <NewsCard 
                                    image={item.thumbnail}
                                    title={item.title}
                                    date={item.created_at?.split(' ')[0]}
                                    onImageClick={() => openLightbox(item)}
                                />
                            </div>
                        {/each}
                    {/if}
                </div>
            </div>
        {/key}
        
        <!-- Pagination -->
        {#if pagination && pagination.last_page > 1}
            <div class="flex justify-center mt-16">
                <nav class="flex items-center gap-2">
                    <button 
                        class="w-10 h-10 rounded-full border border-border-color flex items-center justify-center text-text-body hover:border-primary hover:text-primary transition-colors disabled:opacity-50"
                        disabled={currentPage === 1}
                        onclick={() => fetchPublicBerita(currentPage - 1)}
                    >&laquo;</button>
                    
                    <button class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-medium shadow-md shadow-primary/20">
                        {currentPage}
                    </button>
                    
                    <button 
                        class="w-10 h-10 rounded-full border border-border-color flex items-center justify-center text-text-body hover:border-primary hover:text-primary transition-colors disabled:opacity-50"
                        disabled={currentPage === pagination.last_page}
                        onclick={() => fetchPublicBerita(currentPage + 1)}
                    >&raquo;</button>
                </nav>
            </div>
        {/if}

    </div>
</section>

<LightboxModal 
    {isOpen} 
    image={selectedNews?.thumbnail || selectedNews?.image} 
    title={selectedNews?.title} 
    category="Berita Pesantren" 
    onClose={closeLightbox} 
/>
