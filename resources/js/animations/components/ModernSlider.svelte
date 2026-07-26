<script>
    import { Splide, SplideSlide } from '@splidejs/svelte-splide';
    
    let { 
        slides = [], 
        options = {},
        class: className = '',
        ...rest 
    } = $props();

    // Konfigurasi default modern: Autoplay, Loop, Pagination Modern, Navigation Arrow
    const defaultOptions = {
        type: 'loop',
        autoplay: true,
        interval: 4000,
        pauseOnHover: true,
        pauseOnFocus: true,
        arrows: true,
        pagination: true,
        speed: 800, // 800ms durasi geser halus sesuai persyaratan
        easing: 'cubic-bezier(0.25, 1, 0.5, 1)', // ease-in-out modern
        ...options
    };
</script>

<div class="modern-slider-container relative overflow-hidden rounded-[20px] shadow-card {className}" {...rest}>
    {#if slides && slides.length > 0}
        <Splide options={defaultOptions} aria-label="Slider Utama">
            {#each slides as slide, i}
                <SplideSlide>
                    <div class="relative w-full h-[60vh] md:h-[75vh] overflow-hidden">
                        <img 
                            src={slide.image || slide} 
                            alt={slide.title || `Slide ${i + 1}`} 
                            class="w-full h-full object-cover"
                        />
                        <div class="absolute inset-0 bg-gradient-to-r from-primary-dark/90 via-primary-dark/60 to-transparent flex items-center">
                            <div class="container mx-auto px-8 md:px-16 max-w-3xl">
                                {#if slide.subtitle}
                                    <span class="inline-block px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white text-sm font-semibold mb-4 animate-fade-up">
                                        {slide.subtitle}
                                    </span>
                                {/if}
                                {#if slide.title}
                                    <h2 class="text-3xl md:text-5xl font-heading font-bold text-white mb-4 leading-tight animate-fade-up">
                                        {slide.title}
                                    </h2>
                                {/if}
                                {#if slide.description}
                                    <p class="text-gray-200 text-base md:text-lg mb-8 max-w-xl animate-fade-up">
                                        {slide.description}
                                    </p>
                                {/if}
                            </div>
                        </div>
                    </div>
                </SplideSlide>
            {/each}
        </Splide>
    {:else}
        <div class="w-full h-[50vh] bg-gray-100 flex items-center justify-center text-text-body">
            Tidak ada slide untuk ditampilkan
        </div>
    {/if}
</div>

<style>
    /* Styling khusus kustomisasi Pagination Modern & Arrow pada Splide */
    :global(.modern-slider-container .splide__pagination__page) {
        width: 10px;
        height: 10px;
        background: rgba(255, 255, 255, 0.4);
        border-radius: 999px;
        transition: all 0.3s ease;
        margin: 0 4px;
    }
    :global(.modern-slider-container .splide__pagination__page.is-active) {
        width: 28px;
        background: #22C55E; /* primary-hover */
    }
    :global(.modern-slider-container .splide__arrow) {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        width: 44px;
        height: 44px;
        transition: all 0.3s ease;
    }
    :global(.modern-slider-container .splide__arrow:hover) {
        background: #166534; /* primary */
        transform: scale(1.1);
    }
    :global(.modern-slider-container .splide__arrow svg) {
        fill: #ffffff;
    }
</style>
