<script>
    import Button from './Button.svelte';
    import { PlayCircle, ArrowRight } from 'lucide-svelte';
    import { parallax } from '../animations/actions/parallax';
    import ModernSlider from '../animations/components/ModernSlider.svelte';

    let { 
        title = 'Membentuk Generasi Qurani, Berprestasi, Berkarakter, dan Siap Menjadi Pemimpin Masa Depan',
        subtitle = 'SMA/MA UNGGULAN BERBASIS PESANTREN',
        description = 'Pondok Pesantren Ulul Albab Makassar berkomitmen mencetak generasi Qurani yang berilmu, berakhlak mulia, berprestasi, dan siap menghadapi tantangan global dengan tetap berpegang pada nilai-nilai Islam.',
        primaryButtonText = 'Lihat Profil',
        primaryButtonLink = '/profil',
        secondaryButtonText = 'PPDB Online',
        secondaryButtonLink = '/ppdb',
        backgroundImage = 'https://images.unsplash.com/photo-1541829070764-84a7d30dd3f3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
        backgroundVideo = null,
        sliders = []
    } = $props();

    let videoError = $state(false);
</script>

{#if sliders && sliders.length > 1}
    <section class="relative min-h-[90vh] pt-20 pb-12 bg-primary-dark">
        <ModernSlider slides={sliders} />
    </section>
{:else}
    <section class="relative min-h-[90vh] flex items-center pt-24 pb-16 overflow-hidden">
        <!-- Background Image or Video with Overlay and GSAP Parallax -->
        <div class="absolute inset-0 z-0" use:parallax={{ speed: 0.35 }}>
            {#if backgroundVideo && !videoError}
                <video 
                    src={backgroundVideo} 
                    poster={backgroundImage}
                    autoplay 
                    loop 
                    muted 
                    playsinline 
                    onerror={() => videoError = true}
                    class="w-full h-full object-cover object-center scale-105"
                    data-aos="zoom-in"
                    data-aos-duration="800"
                ></video>
            {:else}
                <img 
                    src={backgroundImage} 
                    alt="Pondok Pesantren Ulul Albab" 
                    class="w-full h-full object-cover object-center scale-105"
                    data-aos="zoom-in"
                    data-aos-duration="800"
                />
            {/if}
            <div class="absolute inset-0 bg-gradient-to-r from-primary-dark/95 via-primary-dark/80 to-primary-dark/40 mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-primary-dark/40"></div>
        </div>

        <!-- Content -->
        <div class="container mx-auto px-6 relative z-10">
            <div class="max-w-3xl">
                <div 
                    class="inline-block px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white text-sm font-semibold mb-6"
                    data-aos="fade-up"
                    data-aos-duration="500"
                >
                    {subtitle}
                </div>
                
                <h1 
                    class="text-4xl md:text-5xl lg:text-6xl font-heading font-bold text-white leading-tight mb-6"
                    data-aos="fade-up"
                    data-aos-delay="150"
                    data-aos-duration="600"
                >
                    Membentuk Generasi Qurani, Berprestasi, Berkarakter, <span class="text-primary-hover">dan Siap Menjadi Pemimpin Masa Depan</span>
                </h1>
                
                <p 
                    class="text-lg md:text-xl text-gray-200 mb-10 max-w-2xl"
                    data-aos="fade-up"
                    data-aos-delay="300"
                    data-aos-duration="600"
                >
                    {description}
                </p>
                
                <div 
                    class="flex flex-wrap items-center gap-4"
                    data-aos="fade-up"
                    data-aos-delay="450"
                    data-aos-duration="700"
                >
                    <Button href={primaryButtonLink} size="lg" class="gap-2">
                        {primaryButtonText}
                        <ArrowRight size={20} />
                    </Button>
                    <Button href={secondaryButtonLink} variant="outline" size="lg" class="gap-2 text-white border-white hover:bg-white hover:text-primary-dark">
                        <PlayCircle size={20} />
                        {secondaryButtonText}
                    </Button>
                </div>
            </div>
        </div>

        <!-- Decorative Islamic Pattern Overlay -->
        <div class="absolute bottom-0 left-0 w-full h-24 bg-gradient-to-t from-bg-base to-transparent z-10"></div>
    </section>
{/if}
