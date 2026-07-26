<script>
    import { onMount } from 'svelte';
    import { Flag, Building, Award, Book, Monitor, Users, Star } from 'lucide-svelte';
    import Skeleton from '../animations/components/Skeleton.svelte';
    import api from '../services/axios';
    
    const icons = [Flag, Building, Award, Book, Monitor, Users];
    
    const defaultTimeline = [
        { year: '2012', title: 'Pendirian Pesantren', desc: 'Pesantren resmi didirikan dengan 23 santri angkatan pertama dan 5 guru pengajar.', icon: Flag },
        { year: '2014', title: 'Pembangunan Gedung', desc: 'Pembangunan gedung kelas dan asrama putra pertama untuk mendukung kenyamanan.', icon: Building },
        { year: '2016', title: 'Akreditasi A', desc: 'SMA Ulul Albab Makassar berhasil meraih Akreditasi A, membuktikan kualitas.', icon: Award },
        { year: '2018', title: 'Pengembangan Kurikulum', desc: 'Mengintegrasikan kurikulum nasional, kepesantrenan, tahfidz, dan teknologi.', icon: Book },
        { year: '2020', title: 'Fasilitas Modern', desc: 'Pembangunan laboratorium komputer, IPA, dan perpustakaan modern.', icon: Monitor },
        { year: '2023 - Sekarang', title: 'Digitalisasi & Prestasi', desc: 'Mengembangkan sistem digital dan meraih berbagai prestasi internasional.', icon: Users },
    ];

    let timeline = $state(defaultTimeline);
    let loading = $state(true);

    onMount(async () => {
        try {
            const response = await api.get('/v1/sejarah');
            if (response.data && response.data.data && response.data.data.length > 0) {
                timeline = response.data.data.map((item, idx) => ({
                    year: item.year || '',
                    title: item.title || '',
                    desc: item.content || '',
                    icon: icons[idx % icons.length] || Star
                }));
            }
        } catch (error) {
            console.error('Error fetching sejarah:', error);
        } finally {
            loading = false;
        }
    });
</script>

<svelte:head>
    <title>Sejarah - Pondok Pesantren Ulul Albab Makassar</title>
</svelte:head>

<div class="pt-24 pb-16 bg-primary-dark relative overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img 
            src="https://images.unsplash.com/photo-1577896851231-70ef18881754?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
            alt="Sejarah Background" 
            class="w-full h-full object-cover opacity-20"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-primary-dark to-transparent"></div>
    </div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-3xl animate-fade-up">
            <h1 class="text-4xl md:text-5xl font-heading font-bold text-white mb-6">Sejarah Pesantren</h1>
            <p class="text-lg text-gray-200">
                Perjalanan panjang penuh perjuangan, dedikasi, dan keikhlasan dalam membentuk generasi Qurani, berilmu, dan berakhlak mulia.
            </p>
        </div>
    </div>
</div>

<section class="py-20 bg-bg-section relative -mt-10 rounded-t-[40px] z-20">
    <div class="container mx-auto px-6">
        <div class="text-center max-w-3xl mx-auto mb-16 animate-fade-up">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-2xl shadow-card text-primary mb-6 -mt-16 relative z-30 transform rotate-45">
                <div class="transform -rotate-45">
                    <Flag size={32} />
                </div>
            </div>
            <h2 class="text-3xl font-heading font-bold text-text-title mb-6">Perjalanan Pondok Pesantren Ulul Albab Makassar</h2>
            <p class="text-text-body text-lg">
                Sejak didirikan hingga kini, Pondok Pesantren Ulul Albab Makassar terus berkembang menjadi lembaga pendidikan Islam modern yang unggul dalam ilmu, iman, teknologi, dan karakter.
            </p>
        </div>

        <div class="flex flex-col lg:flex-row gap-12">
            <!-- Left Side Info -->
            <div class="lg:w-1/3" data-aos="fade-right" data-aos-duration="700">
                <div class="bg-white rounded-[20px] shadow-card p-8 sticky top-24 transition-all duration-500 hover:shadow-xl hover:border-primary/30 border border-transparent">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center text-white">
                            <Book size={24} />
                        </div>
                        <h3 class="font-heading font-bold text-xl text-text-title">Awal Berdiri</h3>
                    </div>
                    <p class="text-text-body mb-6 text-sm leading-relaxed">
                        Pondok Pesantren Ulul Albab Makassar didirikan pada tahun 2012 atas inisiatif para ulama dan tokoh masyarakat dengan visi mencetak generasi Qurani yang berilmu, berprestasi, dan berakhlak mulia.
                    </p>
                    <p class="text-text-body text-sm leading-relaxed mb-6">
                        Berawal dari sebuah bangunan sederhana dengan jumlah santri yang terbatas, pesantren ini terus tumbuh menjadi lembaga pendidikan modern seperti saat ini.
                    </p>
                    <div class="bg-bg-section rounded-xl p-6 border-l-4 border-primary">
                        <p class="italic text-text-title font-medium">"Ilmu tanpa iman adalah kering, Iman tanpa ilmu adalah buta."</p>
                        <p class="text-xs text-text-body mt-2">- Ulul Albab</p>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="lg:w-2/3">
                <div class="relative pl-8 md:pl-0">
                    <!-- Vertical Line -->
                    <div class="hidden md:block absolute left-1/2 top-0 bottom-0 w-0.5 bg-border-color transform -translate-x-1/2"></div>
                    
                    {#if loading}
                        <div class="space-y-8">
                            {#each Array(4) as _}
                                <div class="bg-white rounded-[20px] p-6 shadow-card border border-border-color/50">
                                    <Skeleton type="title" class="mb-3 w-1/3" />
                                    <Skeleton type="text" class="mb-2" />
                                    <Skeleton type="text" class="w-2/3" />
                                </div>
                            {/each}
                        </div>
                    {:else}
                        <div class="space-y-12">
                            {#each timeline as item, i}
                                <div 
                                    class="relative flex md:justify-between items-center w-full {i % 2 === 0 ? 'md:flex-row-reverse' : ''} group"
                                    data-aos={i % 2 === 0 ? 'fade-left' : 'fade-right'}
                                    data-aos-duration="700"
                                    data-aos-delay={i * 100}
                                >
                                    <!-- Mobile Line -->
                                    <div class="md:hidden absolute left-0 top-0 bottom-[-3rem] w-0.5 bg-border-color -translate-x-[2.25rem]"></div>
                                    
                                    <div class="hidden md:block w-5/12"></div>
                                    
                                    <!-- Center Dot -->
                                    <div class="absolute left-[-2.25rem] md:left-1/2 w-4 h-4 rounded-full bg-primary border-4 border-white shadow-sm md:transform md:-translate-x-1/2 z-10 group-hover:scale-150 group-hover:bg-[#F8B803] transition-all duration-300"></div>
                                    
                                    <div class="w-full md:w-5/12">
                                        <div class="bg-white rounded-[20px] p-6 shadow-card hover:shadow-xl transition-all duration-500 ease-in-out hover:border-primary/40 hover:-translate-y-1.5 border border-border-color/50 relative">
                                            <!-- Arrow -->
                                            <div class="hidden md:block absolute top-6 w-0 h-0 border-y-8 border-y-transparent {i % 2 === 0 ? 'left-[-16px] border-r-[16px] border-r-white' : 'right-[-16px] border-l-[16px] border-l-white'}"></div>
                                            
                                            <div class="flex items-center gap-4 mb-4">
                                                <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center text-primary shrink-0">
                                                    <item.icon size={20} />
                                                </div>
                                                <div>
                                                    <h4 class="font-heading font-bold text-text-title text-lg">{item.year}</h4>
                                                    <div class="text-primary font-medium text-sm">{item.title}</div>
                                                </div>
                                            </div>
                                            <p class="text-text-body text-sm leading-relaxed">{item.desc}</p>
                                        </div>
                                    </div>
                                </div>
                            {/each}
                        </div>
                    {/if}
                </div>
            </div>
        </div>
    </div>
</section>
