<script>
    import { onMount } from 'svelte';
    import NewsCard from '../components/NewsCard.svelte';
    import Skeleton from '../animations/components/Skeleton.svelte';
    import { Search } from 'lucide-svelte';
    import api from '../services/axios';
    
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

    onMount(() => {
        fetchPublicBerita();
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

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
            {#if loading}
                {#each Array(6) as _}
                    <Skeleton height="h-96" rounded="rounded-[20px]" />
                {/each}
            {:else if beritas.length === 0}
                <div class="col-span-full py-20 text-center text-text-body">Belum ada berita yang diterbitkan.</div>
            {:else}
                {#each beritas as item, i}
                    <div data-aos="fade-up" data-aos-delay={i * 100} data-aos-duration="600">
                        <NewsCard 
                            image={item.thumbnail}
                            title={item.title}
                            date={item.created_at?.split(' ')[0]}
                        />
                    </div>
                {/each}
            {/if}
        </div>
        
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
