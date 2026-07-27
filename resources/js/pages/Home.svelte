<script>
    import { onMount } from 'svelte';
    import Hero from '../components/Hero.svelte';
    import StatisticCard from '../components/StatisticCard.svelte';
    import Card from '../components/Card.svelte';
    import Button from '../components/Button.svelte';
    import GalleryCard from '../components/GalleryCard.svelte';
    import NewsCard from '../components/NewsCard.svelte';
    import Skeleton from '../animations/components/Skeleton.svelte';
    import api from '../services/axios';
    
    import { Users, BookOpen, GraduationCap, Trophy, Building2, CheckCircle2, PlayCircle, ArrowRight, Microscope, Globe, Book, Languages, Coins, Monitor, Lightbulb, Speech, Star } from 'lucide-svelte';

    let statsData = $state({
        guru: 78,
        prestasi: 150,
        santri: 1250,
        alumni: 2300,
        fasilitas: 20
    });
    
    let statistics = $derived([
        { label: 'Santri Aktif', value: statsData.santri, suffix: '+', icon: Users },
        { label: 'Guru & Staf', value: statsData.guru, suffix: '+', icon: BookOpen },
        { label: 'Alumni', value: statsData.alumni, suffix: '+', icon: GraduationCap },
        { label: 'Prestasi', value: statsData.prestasi, suffix: '+', icon: Trophy },
        { label: 'Fasilitas Modern', value: statsData.fasilitas, suffix: '+', icon: Building2 },
    ]);

    const reasons = [
        { title: 'Berbasis Al-Qur\'an', desc: 'Pembelajaran berlandaskan Al-Qur\'an dan Sunnah untuk membentuk akhlak mulia.', icon: BookOpen },
        { title: 'Guru Profesional', desc: 'Didampingi guru berkompeten, berakhlak, dan berpengalaman di bidangnya.', icon: Users },
        { title: 'Prestasi Gemilang', desc: 'Berbagai prestasi akademik dan non-akademik di tingkat daerah hingga nasional.', icon: Trophy },
        { title: 'Fasilitas Modern', desc: 'Didukung fasilitas lengkap dan teknologi modern untuk pembelajaran optimal.', icon: Building2 },
    ];

    let programs = $state([]);
    let prestasis = $state([]);
    let beritas = $state([]);
    let sliders = $state([]);
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
            const response = await api.get('/v1/home');
            const data = response.data;
            programs = data.programs || [];
            prestasis = data.prestasis || [];
            beritas = data.beritas || [];
            sliders = data.sliders || [];
            if (data.stats) {
                statsData = { ...statsData, ...data.stats };
            }
        } catch (error) {
            console.error('Error fetching home data:', error);
        } finally {
            loading = false;
        }
    });
</script>

<svelte:head>
    <title>Beranda - Pondok Pesantren Ulul Albab Makassar</title>
</svelte:head>

<Hero 
    backgroundImage={sliders.length > 0 && sliders[0].image ? sliders[0].image : 'https://images.unsplash.com/photo-1541829070764-84a7d30dd3f3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80'}
    backgroundVideo="/videos/videoplayback.mp4"
    {sliders}
/>

<!-- Statistics Section -->
<section class="py-12 -mt-16 relative z-20">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
            {#each statistics as stat, i}
                <StatisticCard 
                    value={stat.value} 
                    label={stat.label} 
                    suffix={stat.suffix} 
                    icon={stat.icon} 
                />
            {/each}
        </div>
    </div>
</section>

<!-- About & Video Section -->
<section class="py-20 bg-bg-base overflow-hidden">
    <div class="container mx-auto px-6">
        <div class="flex flex-col lg:flex-row gap-16 items-center">
            <div class="lg:w-1/2" data-aos="fade-right" data-aos-duration="700">
                <div class="inline-block px-4 py-1.5 rounded-full bg-primary/10 text-primary text-sm font-semibold mb-4 animate-floating">
                    TENTANG KAMI
                </div>
                <h2 class="text-3xl lg:text-4xl font-heading font-bold text-text-title mb-6">
                    Pondok Pesantren Ulul Albab Makassar
                </h2>
                <p class="text-text-body mb-6 text-lg leading-relaxed">
                    Berdiri sejak tahun 2012, Pondok Pesantren Ulul Albab Makassar berkomitmen mencetak generasi Qurani yang berilmu, berakhlak mulia, berprestasi, dan siap menghadapi tantangan global.
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                    <div class="flex items-center gap-3">
                        <CheckCircle2 class="text-primary shrink-0" size={20} />
                        <span class="font-medium">Pendidikan Islam Terpadu</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <CheckCircle2 class="text-primary shrink-0" size={20} />
                        <span class="font-medium">Pembinaan Karakter & Kepemimpinan</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <CheckCircle2 class="text-primary shrink-0" size={20} />
                        <span class="font-medium">Kurikulum Nasional & Pesantren</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <CheckCircle2 class="text-primary shrink-0" size={20} />
                        <span class="font-medium">Fasilitas Lengkap & Modern</span>
                    </div>
                </div>
                
                <Button href="/#/profil" class="shadow-lg">
                    Selengkapnya Tentang Kami
                    <ArrowRight size={18} class="ml-2" />
                </Button>
            </div>
            
            <div class="lg:w-1/2 w-full" data-aos="fade-left" data-aos-duration="700">
                <div class="relative rounded-[20px] overflow-hidden shadow-card group">
                    <img 
                        src="https://images.unsplash.com/photo-1577896851231-70ef18881754?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                        alt="Video Profil" 
                        class="w-full aspect-video object-cover transition-transform duration-700 ease-in-out group-hover:scale-105"
                    />
                    <div class="absolute inset-0 bg-black/40 group-hover:bg-black/30 transition-colors flex items-center justify-center">
                        <button class="w-20 h-20 bg-white/30 backdrop-blur-md rounded-full flex items-center justify-center hover:bg-primary hover:text-white transition-all duration-300 transform hover:scale-110 text-white shadow-lg">
                            <PlayCircle size={40} />
                        </button>
                    </div>
                    <!-- Floating Badge -->
                    <div class="absolute top-6 right-6 bg-white/90 backdrop-blur-md px-4 py-2 rounded-full shadow-lg flex items-center gap-2 animate-floating">
                        <Star size={16} class="text-[#F8B803] fill-[#F8B803]" />
                        <span class="text-xs font-bold text-primary-dark">Akreditasi Unggul</span>
                    </div>
                    <div class="absolute bottom-6 left-6 right-6">
                        <h3 class="text-white font-heading font-semibold text-xl mb-1">Video Profil Pesantren</h3>
                        <p class="text-white/80 text-sm">Putar Video</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mengapa Memilih Ulul Albab -->
<section class="py-20 bg-bg-section relative overflow-hidden">
    <!-- Decorative patterns -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-primary-hover/5 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <h2 class="text-3xl lg:text-4xl font-heading font-bold text-text-title mb-4 flex items-center justify-center gap-4">
                <span class="w-12 h-1 bg-primary/20 rounded-full"></span>
                Mengapa Memilih Ulul Albab?
                <span class="w-12 h-1 bg-primary/20 rounded-full"></span>
            </h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            {#each reasons as reason, i}
                <div data-aos="zoom-in" data-aos-delay={i * 150} data-aos-duration="600">
                    <Card class="text-center flex flex-col items-center group h-full">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center text-primary mb-6 group-hover:scale-110 group-hover:bg-primary group-hover:text-white transition-all duration-500 ease-in-out shadow-sm group-hover:shadow-md">
                            <reason.icon size={32} />
                        </div>
                        <h3 class="font-heading font-semibold text-xl text-text-title mb-3 group-hover:text-primary transition-colors">{reason.title}</h3>
                        <p class="text-text-body text-sm leading-relaxed">{reason.desc}</p>
                    </Card>
                </div>
            {/each}
        </div>
    </div>
</section>

<!-- Program Unggulan -->
<section class="py-20 bg-bg-base">
    <div class="container mx-auto px-6">
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-4">
            <div class="max-w-2xl">
                <h2 class="text-3xl lg:text-4xl font-heading font-bold text-text-title mb-4 flex items-center gap-4">
                    Program Unggulan
                    <span class="w-20 h-1 bg-primary/20 rounded-full hidden md:block"></span>
                </h2>
            </div>
            <a href="/#/program" class="text-primary font-medium hover:text-primary-hover transition-colors flex items-center gap-2">
                Lihat Semua Program
                <ArrowRight size={18} />
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
            {#each programs as program, i}
                {@const ProgramIcon = getIcon(program.icon)}
                <div data-aos="fade-up" data-aos-delay={i * 100} data-aos-duration="600">
                    <Card class="p-6 border border-border-color/50 hover:border-primary transition-all duration-300 ease-in-out hover:shadow-[0_10px_30px_rgba(22,101,52,0.15)] hover:-translate-y-2 group h-full flex flex-col">
                        <div class="text-primary mb-4 transition-transform duration-500 group-hover:scale-125 group-hover:-translate-y-1 inline-block">
                            <ProgramIcon size={40} />
                        </div>
                        <h3 class="font-heading font-semibold text-lg text-text-title mb-2 group-hover:text-primary transition-colors">{program.name}</h3>
                        <p class="text-text-body text-sm mb-4 flex-grow">{program.description}</p>
                        <a href="/#/program" class="inline-flex items-center gap-2 text-sm text-text-body group-hover:text-primary font-medium transition-colors mt-auto">
                            Selengkapnya <ArrowRight size={14} class="transform group-hover:translate-x-1 transition-transform" />
                        </a>
                    </Card>
                </div>
            {/each}
        </div>
    </div>
</section>

<!-- Prestasi Section (Snippet) -->
<section class="py-20 bg-bg-section">
    <div class="container mx-auto px-6">
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-4">
            <div>
                <h2 class="text-3xl lg:text-4xl font-heading font-bold text-text-title mb-4">Prestasi Membanggakan</h2>
            </div>
            <a href="/#/prestasi" class="text-primary font-medium hover:text-primary-hover transition-colors flex items-center gap-2">
                Lihat Semua Prestasi <ArrowRight size={18} />
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {#each prestasis as item, i}
                <div data-aos="flip-left" data-aos-delay={i * 100} data-aos-duration="700">
                    <Card class="p-0 overflow-hidden group h-full flex flex-col">
                        <div class="h-40 overflow-hidden bg-gray-100 relative">
                            {#if item.image}
                                <img src={item.image} alt={item.title} class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-in-out" />
                            {/if}
                        </div>
                        <div class="p-5 flex flex-col flex-grow">
                            <div>
                                <span class="inline-block px-3 py-1 bg-green-100 text-primary-dark text-xs font-semibold rounded-full mb-3 badge-glow animate-pulse">
                                    {item.level}
                                </span>
                            </div>
                            <h3 class="font-heading font-semibold text-text-title mb-2 line-clamp-2 group-hover:text-primary transition-colors">{item.rank} {item.title}</h3>
                            <p class="text-text-body text-xs mt-auto">{item.year} - {item.winner_name}</p>
                        </div>
                    </Card>
                </div>
            {/each}
        </div>
    </div>
</section>

<!-- Berita Terbaru -->
<section class="py-20 bg-bg-base">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-heading font-bold text-text-title mb-4">Berita & Informasi Terkini</h2>
            <p class="text-text-body max-w-2xl mx-auto">Dapatkan kabar terbaru seputar kegiatan, informasi pendaftaran, dan pengumuman penting lainnya.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {#if loading}
                {#each Array(3) as _}
                    <Skeleton height="h-96" rounded="rounded-[20px]" />
                {/each}
            {:else if beritas.length === 0}
                <div class="col-span-full py-10 text-center text-text-body">Belum ada berita terbaru.</div>
            {:else}
                {#each beritas as item}
                    <NewsCard 
                        image={item.thumbnail}
                        title={item.title}
                        date={item.created_at?.split(' ')[0]}
                    />
                {/each}
            {/if}
        </div>
        
        <div class="mt-12 text-center">
            <Button href="/#/berita" variant="outline">Lihat Semua Berita</Button>
        </div>
    </div>
</section>

<!-- Call To Action -->
<section class="py-20 bg-primary-dark relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/arabesque.png')] opacity-10 mix-blend-overlay"></div>
    <div class="container mx-auto px-6 relative z-10 text-center">
        <h2 class="text-3xl md:text-4xl lg:text-5xl font-heading font-bold text-white mb-6">
            Bergabunglah Bersama Kami
        </h2>
        <p class="text-gray-200 text-lg md:text-xl max-w-3xl mx-auto mb-10">
            Mari wujudkan masa depan gemilang bersama pendidikan Islam berkualitas di Pondok Pesantren Ulul Albab Makassar.
        </p>
        <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
            <Button href="/#/ppdb" size="lg" variant="white" class="w-full sm:w-auto text-primary-dark font-semibold">
                Daftar PPDB Sekarang
                <ArrowRight size={20} class="ml-2" />
            </Button>
            <Button href="/#/kontak" size="lg" variant="outline" class="w-full sm:w-auto text-white border-white hover:bg-white hover:text-primary-dark">
                Hubungi Kami
            </Button>
        </div>
        <p class="text-white/70 text-sm mt-6">Tahun Ajaran 2026/2027</p>
    </div>
</section>
