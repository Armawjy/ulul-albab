<script>
    import { onMount, onDestroy } from 'svelte';
    import GalleryCard from '../components/GalleryCard.svelte';
    import Skeleton from '../animations/components/Skeleton.svelte';
    import LightboxModal from '../animations/components/LightboxModal.svelte';
    import api from '../services/axios';
    import { fade, fly } from 'svelte/transition';
    import { flip } from 'svelte/animate';
    import { cubicOut, quartOut } from 'svelte/easing';
    
    const categories = ['Semua', 'Fasilitas', 'Akademik', 'Keagamaan', 'Ekstrakurikuler'];
    let activeCategory = $state('Semua');
    
    let filteredGalleries = $state([]);
    let loading = $state(true);

    let selectedImage = $state(null);
    let isOpen = $state(false);

    const openLightbox = (item) => {
        selectedImage = item;
        isOpen = true;
    };

    const closeLightbox = () => {
        isOpen = false;
        selectedImage = null;
    };

    const fetchGalleries = async () => {
        loading = true;
        try {
            const url = activeCategory === 'Semua' ? '/v1/galeri' : `/v1/galeri?category=${activeCategory}`;
            const response = await api.get(url);
            filteredGalleries = response.data.data;
        } catch (error) {
            console.error('Failed to fetch galleries:', error);
        } finally {
            loading = false;
        }
    };

    const handleCategoryClick = (category) => {
        activeCategory = category;
        fetchGalleries();
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
                        scrollContainer.scrollBy({ left: 320, behavior: 'smooth' });
                    }
                }
            }
        }, 2200);
    };

    const stopAutoPlay = () => {
        if (autoPlayInterval) clearInterval(autoPlayInterval);
    };

    onMount(() => {
        fetchGalleries();
        startAutoPlay();
    });

    onDestroy(() => {
        stopAutoPlay();
    });
</script>

<svelte:head>
    <title>Galeri - Pondok Pesantren Ulul Albab Makassar</title>
</svelte:head>

<div class="pt-24 pb-16 bg-primary-dark relative overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img 
            src="https://images.unsplash.com/photo-1541829070764-84a7d30dd3f3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
            alt="Galeri Background" 
            class="w-full h-full object-cover opacity-20"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-primary-dark to-transparent"></div>
    </div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-3xl animate-fade-up">
            <h1 class="text-4xl md:text-5xl font-heading font-bold text-white mb-6">Galeri Kegiatan</h1>
            <p class="text-lg text-gray-200">
                Dokumentasi fasilitas dan berbagai kegiatan santri selama berada di Pondok Pesantren Ulul Albab.
            </p>
        </div>
    </div>
</div>

<section class="py-20 bg-bg-base relative -mt-10 rounded-t-[40px] z-20">
    <div class="container mx-auto px-6">
        
        <!-- Filters -->
        <div class="flex flex-wrap gap-3 mb-12 justify-center">
            {#each categories as category}
                <button 
                    class="px-6 py-2 rounded-full text-sm font-medium transition-all duration-300 transform hover:-translate-y-0.5 border {activeCategory === category ? 'bg-primary border-primary text-white shadow-md shadow-primary/20' : 'bg-white text-text-title border-border-color hover:border-primary hover:text-primary'}"
                    onclick={() => handleCategoryClick(category)}
                >
                    {category}
                </button>
            {/each}
        </div>

        {#key activeCategory}
            <div class="relative w-full">
                <!-- Scroll Container -->
                <div 
                    bind:this={scrollContainer}
                    class="flex flex-nowrap gap-6 overflow-x-auto scroll-smooth py-6 px-4 no-scrollbar"
                    style="scrollbar-width: none; -ms-overflow-style: none;"
                    in:fade={{ duration: 200 }}
                >
                    {#if loading}
                        {#each Array(6) as _}
                            <div class="w-72 sm:w-80 flex-shrink-0">
                                <Skeleton height="h-72" rounded="rounded-[20px]" />
                            </div>
                        {/each}
                    {:else if filteredGalleries.length === 0}
                        <div class="w-full py-20 text-center text-text-body">
                            Tidak ada galeri untuk kategori ini.
                        </div>
                    {:else}
                        {#each filteredGalleries as item, i}
                            <div 
                                class="w-72 sm:w-80 flex-shrink-0 transition-transform duration-300 hover:-translate-y-2"
                                in:fly={{ x: 120, duration: 600, delay: (i % 8) * 80, easing: cubicOut }}
                                out:fade={{ duration: 150 }}
                            >
                                <GalleryCard 
                                    image={item.image} 
                                    title={item.title} 
                                    category={item.category}
                                    onclick={() => openLightbox(item)}
                                />
                            </div>
                        {/each}
                    {/if}
                </div>
            </div>
        {/key}
        
    </div>
</section>

<LightboxModal {isOpen} image={selectedImage?.image} title={selectedImage?.title} category={selectedImage?.category} onClose={closeLightbox} />
