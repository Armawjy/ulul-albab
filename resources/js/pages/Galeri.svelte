<script>
    import { onMount } from 'svelte';
    import GalleryCard from '../components/GalleryCard.svelte';
    import Skeleton from '../animations/components/Skeleton.svelte';
    import LightboxModal from '../animations/components/LightboxModal.svelte';
    import api from '../services/axios';
    
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

    onMount(() => {
        fetchGalleries();
    });

    const handleCategoryClick = (category) => {
        activeCategory = category;
        fetchGalleries();
    };
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

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            {#if loading}
                {#each Array(8) as _}
                    <Skeleton height="h-72" rounded="rounded-[20px]" />
                {/each}
            {:else if filteredGalleries.length === 0}
                <div class="col-span-full py-20 text-center text-text-body">
                    Tidak ada galeri untuk kategori ini.
                </div>
            {:else}
                {#each filteredGalleries as item (item.id)}
                    <div data-aos="zoom-in" data-aos-duration="600">
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
</section>

<LightboxModal {isOpen} image={selectedImage} onClose={closeLightbox} />
