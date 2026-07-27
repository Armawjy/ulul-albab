<script>
    import { onMount } from 'svelte';
    import { ArrowRight, Microscope, Globe, Book, Languages, Coins, Monitor, Lightbulb, Users, Speech, Star } from 'lucide-svelte';
    import Card from '../components/Card.svelte';
    import Skeleton from '../animations/components/Skeleton.svelte';
    import LightboxModal from '../animations/components/LightboxModal.svelte';
    import api from '../services/axios';
    
    let programs = $state([]);
    let loading = $state(true);

    const iconMap = {
        'Microscope': Microscope,
        'Globe': Globe,
        'Book': Book,
        'Languages': Languages,
        'Coins': Coins,
        'Monitor': Monitor,
        'Lightbulb': Lightbulb,
        'Users': Users,
        'Speech': Speech,
    };
    
    const getIcon = (iconName) => iconMap[iconName] || Star;

    onMount(async () => {
        try {
            const response = await api.get('/v1/program');
            programs = response.data.data || [];
        } catch (error) {
            console.error('Error fetching programs:', error);
        } finally {
            loading = false;
        }
    });

    let selectedProgram = $state(null);
    let isOpen = $state(false);

    const openLightbox = (program) => {
        selectedProgram = program;
        isOpen = true;
    };

    const closeLightbox = () => {
        isOpen = false;
        selectedProgram = null;
    };
</script>

<svelte:head>
    <title>Program SMA/MA - Pondok Pesantren Ulul Albab Makassar</title>
</svelte:head>

<div class="pt-24 pb-16 bg-primary-dark relative overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img 
            src="https://images.unsplash.com/photo-1577896851231-70ef18881754?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
            alt="Program Background" 
            class="w-full h-full object-cover opacity-20"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-primary-dark to-transparent"></div>
    </div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-3xl animate-fade-up">
            <h1 class="text-4xl md:text-5xl font-heading font-bold text-white mb-6">Program SMA/MA</h1>
            <p class="text-lg text-gray-200">
                Beragam program unggulan yang dirancang untuk membekali santri dengan ilmu agama, akademik, dan life skills.
            </p>
        </div>
    </div>
</div>

<section class="py-20 bg-bg-base relative -mt-10 rounded-t-[40px] z-20">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            {#if loading}
                {#each Array(8) as _}
                    <Skeleton height="h-64" rounded="rounded-[20px]" />
                {/each}
            {:else if programs.length === 0}
                <div class="col-span-full py-20 text-center text-text-body">
                    Belum ada data program.
                </div>
            {:else}
                {#each programs as program, i (program.id)}
                    {@const ProgramIcon = getIcon(program.icon)}
                    <div data-aos="fade-up" data-aos-delay={i * 100} data-aos-duration="600">
                        <Card class="flex flex-col h-full border border-border-color/50 hover:border-primary transition-all duration-300 ease-in-out hover:shadow-[0_10px_30px_rgba(22,101,52,0.15)] hover:-translate-y-2 group">
                            {#if program.icon && (program.icon.startsWith('http') || program.icon.includes('/') || program.icon.includes('blob:') || program.icon.includes('.'))}
                                <div 
                                    class="relative w-full h-52 mb-6 rounded-2xl overflow-hidden shadow-sm cursor-pointer"
                                    onclick={() => openLightbox(program)}
                                    role="button"
                                    tabindex="0"
                                    onkeydown={(e) => (e.key === 'Enter' || e.key === ' ') && openLightbox(program)}
                                >
                                    <img src={program.icon} alt={program.name} class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                                    <div class="absolute inset-0 bg-primary/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                        <span class="bg-black/60 text-white text-xs px-3 py-1.5 rounded-full font-medium backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity">
                                            Klik Lihat Foto
                                        </span>
                                    </div>
                                </div>
                            {:else}
                                <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-primary mb-6 group-hover:scale-110 group-hover:bg-primary group-hover:text-white transition-all duration-500">
                                    <ProgramIcon size={28} />
                                </div>
                            {/if}
                            
                            <h3 class="font-heading font-bold text-xl text-text-title mb-3 group-hover:text-primary transition-colors">{program.name}</h3>
                            <p class="text-text-body text-sm leading-relaxed mb-6 flex-grow">{program.description}</p>
                            
                            <a href="#/kontak" class="inline-flex items-center gap-2 text-primary text-sm font-semibold hover:text-primary-hover transition-colors mt-auto">
                                Info Pendaftaran <ArrowRight size={16} class="group-hover:translate-x-1 transition-transform" />
                            </a>
                        </Card>
                    </div>
                {/each}
            {/if}
        </div>
    </div>
</section>

<LightboxModal 
    {isOpen} 
    image={selectedProgram?.icon} 
    title={selectedProgram?.name} 
    category="Program Unggulan" 
    onClose={closeLightbox} 
/>
