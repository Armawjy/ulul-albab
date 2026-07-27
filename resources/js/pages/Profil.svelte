<script>
    import { onMount } from 'svelte';
    import { Building2, Users, BookOpen, Target, GraduationCap } from 'lucide-svelte';
    import api from '../services/axios';
    
    let profilTitle = $state("Pondok Pesantren Ulul Albab Makassar");
    let profilContent = $state("Pondok Pesantren Ulul Albab Makassar merupakan lembaga pendidikan Islam modern tingkat SMA/MA yang mengintegrasikan pendidikan agama, akademik, teknologi, kepemimpinan, dan pembinaan karakter dalam lingkungan pesantren yang kondusif dan islami.\n\nKami berkomitmen mencetak generasi Qurani, berprestasi, berkarakter, dan siap menjadi pemimpin masa depan yang membawa manfaat bagi umat dan bangsa.");

    onMount(async () => {
        try {
            const response = await api.get('/v1/profil');
            if (response.data && response.data.data) {
                if (response.data.data.title) profilTitle = response.data.data.title;
                if (response.data.data.content) profilContent = response.data.data.content;
            }
        } catch (error) {
            console.error('Error fetching profil:', error);
        }
    });
</script>

<svelte:head>
    <title>Profil - Pondok Pesantren Ulul Albab Makassar</title>
</svelte:head>

<div class="pt-24 pb-16 bg-primary-dark relative overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img 
            src="https://images.unsplash.com/photo-1541829070764-84a7d30dd3f3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
            alt="Profil Background" 
            class="w-full h-full object-cover opacity-20"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-primary-dark to-transparent"></div>
    </div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-3xl animate-fade-up">
            <h1 class="text-4xl md:text-5xl font-heading font-bold text-white mb-6">Profil Pesantren</h1>
            <p class="text-lg text-gray-200">
                Mengenal lebih dekat Pondok Pesantren Ulul Albab Makassar, lembaga pendidikan Islam modern tingkat SMA/MA.
            </p>
        </div>
    </div>
</div>

<section class="py-20 bg-bg-base relative -mt-10 rounded-t-[40px] z-20">
    <div class="container mx-auto px-6">
        <div class="flex flex-col lg:flex-row gap-16 items-start">
            <div class="lg:w-1/2 order-2 lg:order-1" data-aos="fade-right" data-aos-duration="700">
                <div class="inline-block px-4 py-1.5 rounded-full bg-primary/10 text-primary text-sm font-semibold mb-6 animate-floating">
                    TENTANG KAMI
                </div>
                <h2 class="text-3xl font-heading font-bold text-text-title mb-6">{profilTitle}</h2>
                
                <div class="prose prose-lg text-text-body">
                    {#each profilContent.split('\n\n') as paragraph}
                        <p class="mb-4 leading-relaxed">{paragraph}</p>
                    {/each}
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-8">
                    <div class="flex gap-4 items-start">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary shrink-0">
                            <GraduationCap size={24} />
                        </div>
                        <div>
                            <h4 class="font-heading font-semibold text-text-title mb-1">Pendidikan Berkualitas</h4>
                            <p class="text-sm text-text-body">Kombinasi ilmu agama dan ilmu pengetahuan modern.</p>
                        </div>
                    </div>
                    <div class="flex gap-4 items-start">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary shrink-0">
                            <Users size={24} />
                        </div>
                        <div>
                            <h4 class="font-heading font-semibold text-text-title mb-1">Pembinaan Karakter</h4>
                            <p class="text-sm text-text-body">Membentuk akhlak mulia dan kepribadian unggul.</p>
                        </div>
                    </div>
                    <div class="flex gap-4 items-start">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary shrink-0">
                            <Building2 size={24} />
                        </div>
                        <div>
                            <h4 class="font-heading font-semibold text-text-title mb-1">Lingkungan Islami</h4>
                            <p class="text-sm text-text-body">Lingkungan pesantren yang kondusif untuk belajar.</p>
                        </div>
                    </div>
                    <div class="flex gap-4 items-start">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary shrink-0">
                            <Target size={24} />
                        </div>
                        <div>
                            <h4 class="font-heading font-semibold text-text-title mb-1">Prestasi & Inovasi</h4>
                            <p class="text-sm text-text-body">Mendorong santri berinovasi di berbagai bidang.</p>
                        </div>
                    </div>
                </div>

                <!-- Developer Attribution -->
                <div class="mt-8 pt-6 border-t border-border-color/60 flex items-center gap-3.5 bg-primary/5 p-4 rounded-2xl border border-primary/20">
                    <div class="w-11 h-11 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-base shadow-md shrink-0">
                        AW
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold tracking-wider text-emerald-600 uppercase block">Developer & Creator Website</span>
                        <h4 class="font-heading font-extrabold text-text-title text-base leading-tight">dev: Arma Wijaya</h4>
                    </div>
                </div>
            </div>
            
            <div class="lg:w-1/2 w-full order-1 lg:order-2" data-aos="fade-left" data-aos-duration="700">
                <div class="relative rounded-[20px] overflow-hidden shadow-xl group">
                    <img src="https://images.unsplash.com/photo-1541829070764-84a7d30dd3f3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Gedung Sekolah" class="w-full aspect-[4/3] object-cover transition-transform duration-700 group-hover:scale-105" />
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-8">
                        <h3 class="text-white font-heading font-semibold text-xl">Gedung Utama Pesantren</h3>
                        <p class="text-white/80 text-sm">Pusat kegiatan akademik dan administratif</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
