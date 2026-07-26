<script>
    import { onMount } from 'svelte';
    import { Trophy, Medal, Star } from 'lucide-svelte';
    import Card from '../components/Card.svelte';
    import Skeleton from '../animations/components/Skeleton.svelte';
    import api from '../services/axios';
    
    let prestasi = $state([]);
    let loading = $state(true);

    onMount(async () => {
        try {
            const response = await api.get('/v1/prestasi');
            prestasi = response.data.data;
        } catch (error) {
            console.error('Error fetching prestasi:', error);
        } finally {
            loading = false;
        }
    });
</script>

<svelte:head>
    <title>Prestasi - Pondok Pesantren Ulul Albab Makassar</title>
</svelte:head>

<div class="pt-24 pb-16 bg-primary-dark relative overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img 
            src="https://images.unsplash.com/photo-1526628953301-3e589a6a8b74?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
            alt="Prestasi Background" 
            class="w-full h-full object-cover opacity-20"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-primary-dark to-transparent"></div>
    </div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-3xl animate-fade-up">
            <h1 class="text-4xl md:text-5xl font-heading font-bold text-white mb-6">Prestasi Santri</h1>
            <p class="text-lg text-gray-200">
                Bukti nyata dedikasi dan kerja keras santri Ulul Albab dalam meraih keunggulan di bidang akademik maupun non-akademik.
            </p>
        </div>
    </div>
</div>

<section class="py-20 bg-bg-base relative -mt-10 rounded-t-[40px] z-20">
    <div class="container mx-auto px-6">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {#if loading}
                {#each Array(6) as _}
                    <Skeleton height="h-96" rounded="rounded-[20px]" />
                {/each}
            {:else if prestasi.length === 0}
                <div class="col-span-full py-20 text-center text-text-body">
                    Belum ada data prestasi.
                </div>
            {:else}
                {#each prestasi as item, i (item.id)}
                    <div data-aos="flip-left" data-aos-delay={i * 100} data-aos-duration="700">
                        <Card class="p-0 overflow-hidden group border border-border-color/50 h-full flex flex-col">
                            <div class="relative h-48 overflow-hidden bg-gray-100">
                                {#if item.image}
                                    <img 
                                        src={item.image} 
                                        alt={item.title} 
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-in-out"
                                    />
                                {/if}
                                <div class="absolute top-4 right-4 w-12 h-12 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center text-[#F8B803] shadow-lg transform group-hover:scale-110 transition-transform duration-300">
                                    <Trophy size={24} />
                                </div>
                            </div>
                            <div class="p-6 flex flex-col flex-grow">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="px-3 py-1 bg-primary/10 text-primary rounded-full text-xs font-semibold">{item.year}</span>
                                    <span class="px-3 py-1 bg-green-100 text-primary-dark rounded-full text-xs font-semibold badge-glow animate-pulse">{item.level}</span>
                                </div>
                                <h3 class="font-heading font-bold text-xl text-text-title mb-2 text-primary group-hover:text-primary-hover transition-colors">{item.rank}</h3>
                                <h4 class="font-heading font-medium text-text-title mb-2 line-clamp-2 leading-snug">{item.title}</h4>
                                <div class="flex items-center gap-2 text-sm text-text-body mt-auto pt-4 border-t border-border-color">
                                    <Star size={16} class="text-[#F8B803] fill-[#F8B803]" />
                                    <span class="font-medium text-text-title">{item.winner_name}</span>
                                </div>
                            </div>
                        </Card>
                    </div>
                {/each}
            {/if}
        </div>
        
    </div>
</section>
