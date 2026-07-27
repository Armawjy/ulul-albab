<script>
    import { onMount, onDestroy } from 'svelte';
    import TeacherCard from '../components/TeacherCard.svelte';
    import Skeleton from '../animations/components/Skeleton.svelte';
    import LightboxModal from '../animations/components/LightboxModal.svelte';
    import api from '../services/axios';
    import { fade, fly } from 'svelte/transition';
    import { flip } from 'svelte/animate';
    import { cubicOut, quartOut } from 'svelte/easing';
    
    let teachers = $state([]);
    let loading = $state(true);

    let selectedTeacher = $state(null);
    let isOpen = $state(false);

    const openLightbox = (teacher) => {
        selectedTeacher = teacher;
        isOpen = true;
    };

    const closeLightbox = () => {
        isOpen = false;
        selectedTeacher = null;
    };

    let activeCategory = $state('Semua');
    const categories = ['Semua', 'Pimpinan', 'Guru Agama', 'Guru Umum'];

    let filteredTeachers = $derived(
        activeCategory === 'Semua' 
            ? teachers 
            : teachers.filter(t => {
                const pos = (t.position || '').toLowerCase();
                const desc = (t.description || '').toLowerCase();
                
                if (activeCategory === 'Pimpinan') {
                    return pos.includes('pimpinan') || pos.includes('kepala') || pos.includes('waka') || pos.includes('direktur');
                }
                if (activeCategory === 'Guru Agama') {
                    return pos.includes('agama') || pos.includes('qur\'an') || pos.includes('quran') || pos.includes('tahfidz') || pos.includes('tafsir') || pos.includes('arab') || pos.includes('fiqih') || desc.includes('agama');
                }
                if (activeCategory === 'Guru Umum') {
                    const isPimpinan = pos.includes('pimpinan') || pos.includes('kepala') || pos.includes('waka') || pos.includes('direktur');
                    const isAgama = pos.includes('agama') || pos.includes('qur\'an') || pos.includes('quran') || pos.includes('tahfidz') || pos.includes('tafsir') || pos.includes('arab') || pos.includes('fiqih') || desc.includes('agama');
                    return !isPimpinan && !isAgama;
                }
                return true;
            })
    );

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

    onMount(async () => {
        try {
            const response = await api.get('/v1/guru');
            teachers = response.data.data;
            startAutoPlay();
        } catch (error) {
            console.error('Error fetching teachers:', error);
        } finally {
            loading = false;
        }
    });

    onDestroy(() => {
        stopAutoPlay();
    });
</script>

<svelte:head>
    <title>Guru & Staf - Pondok Pesantren Ulul Albab Makassar</title>
</svelte:head>

<div class="pt-24 pb-16 bg-primary-dark relative overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img 
            src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
            alt="Guru Background" 
            class="w-full h-full object-cover opacity-20"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-primary-dark to-transparent"></div>
    </div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-3xl animate-fade-up">
            <h1 class="text-4xl md:text-5xl font-heading font-bold text-white mb-6">Guru & Staf Pendik</h1>
            <p class="text-lg text-gray-200">
                Dididik dan dibimbing oleh tenaga pendidik profesional, kompeten, dan berdedikasi tinggi di bidangnya masing-masing.
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
                    class="px-6 py-2 rounded-full text-sm font-medium transition-all duration-300 transform hover:-translate-y-0.5 {activeCategory === category ? 'bg-primary text-white border-transparent shadow-md shadow-primary/20' : 'bg-white text-text-title border border-border-color hover:border-primary hover:text-primary'}"
                    onclick={() => activeCategory = category}
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
                                <Skeleton height="h-80" rounded="rounded-[20px]" />
                            </div>
                        {/each}
                    {:else if filteredTeachers.length === 0}
                        <div class="w-full py-20 text-center text-text-body">
                            Belum ada data guru untuk kategori ini.
                        </div>
                    {:else}
                        {#each filteredTeachers as teacher, i}
                            <div 
                                class="w-72 sm:w-80 flex-shrink-0 transition-transform duration-300 hover:-translate-y-2"
                                in:fly={{ x: 120, duration: 600, delay: (i % 8) * 80, easing: cubicOut }}
                                out:fade={{ duration: 150 }}
                            >
                                <TeacherCard 
                                    name={teacher.name}
                                    role={teacher.position || 'Staf Pendik'}
                                    subject={teacher.description || 'Guru'}
                                    image={teacher.image || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(teacher.name) + '&background=0D8A4E&color=fff&size=512'}
                                    onclick={() => openLightbox(teacher)}
                                />
                            </div>
                        {/each}
                    {/if}
                </div>
            </div>
        {/key}
        
    </div>
</section>

<LightboxModal 
    {isOpen} 
    image={selectedTeacher?.image || (selectedTeacher ? 'https://ui-avatars.com/api/?name=' + encodeURIComponent(selectedTeacher.name) + '&background=0D8A4E&color=fff&size=512' : null)} 
    title={selectedTeacher?.name} 
    category={selectedTeacher?.position || 'Staf Pendik'} 
    onClose={closeLightbox} 
/>
