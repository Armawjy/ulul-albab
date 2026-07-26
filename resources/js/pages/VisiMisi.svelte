<script>
    import { onMount } from 'svelte';
    import { Target, ListChecks, CheckCircle2 } from 'lucide-svelte';
    import Skeleton from '../animations/components/Skeleton.svelte';
    import api from '../services/axios';
    
    const defaultMisiList = [
        "Menyelenggarakan pendidikan yang mengintegrasikan ilmu agama, ilmu pengetahuan, teknologi, dan keterampilan.",
        "Membina generasi Qurani yang memiliki pemahaman dan pengamalan Al-Qur'an dalam kehidupan sehari-hari.",
        "Mengembangkan potensi akademik dan non-akademik siswa secara optimal untuk meraih prestasi.",
        "Membentuk karakter Islami yang berakhlak mulia, disiplin, mandiri, tanggung jawab, dan berjiwa kepemimpinan.",
        "Menciptakan lingkungan pesantren yang kondusif, aman, dan nyaman untuk belajar, beribadah, dan berkarya.",
        "Menjalin kerja sama dan sinergi dengan orang tua, masyarakat, dan berbagai pihak untuk kemajuan pendidikan."
    ];
    
    const defaultNilaiNilai = [
        { title: "Iman & Taqwa", desc: "Menanamkan keimanan sebagai landasan setiap langkah." },
        { title: "Ilmu & Amal", desc: "Mengutamakan ilmu yang bermanfaat dan diamalkan." },
        { title: "Akhlak Mulia", desc: "Membentuk pribadi berakhlak mulia dalam berperilaku." },
        { title: "Kemandirian", desc: "Melatih kemandirian dan kedisiplinan santri." },
        { title: "Inovasi & Kreativitas", desc: "Mendorong inovasi untuk menghadapi tantangan masa depan." },
        { title: "Kepemimpinan", desc: "Membina jiwa kepemimpinan yang amanah." }
    ];

    let visiText = $state("Menjadi lembaga pendidikan Islam unggul yang melahirkan generasi Qurani, berilmu, berprestasi, berkarakter, dan siap menjadi pemimpin masa depan yang membawa manfaat bagi umat dan bangsa.");
    let misiList = $state(defaultMisiList);
    let nilaiNilai = $state(defaultNilaiNilai);
    let loading = $state(true);

    onMount(async () => {
        try {
            const response = await api.get('/v1/visi-misi');
            if (response.data && response.data.data) {
                const data = response.data.data;
                if (data.visi && data.visi.length > 0) {
                    visiText = data.visi[0].content;
                }
                if (data.misi && data.misi.length > 0) {
                    misiList = data.misi.map(m => m.content);
                }
                if (data.nilai && data.nilai.length > 0) {
                    nilaiNilai = data.nilai.map(n => {
                        const parts = n.content.split(' - ');
                        return {
                            title: parts[0] || 'Nilai Pesantren',
                            desc: parts[1] || n.content
                        };
                    });
                }
            }
        } catch (error) {
            console.error('Error fetching visi misi:', error);
        } finally {
            loading = false;
        }
    });
</script>

<svelte:head>
    <title>Visi & Misi - Pondok Pesantren Ulul Albab Makassar</title>
</svelte:head>

<div class="pt-24 pb-16 bg-primary-dark relative overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img 
            src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
            alt="Visi Misi Background" 
            class="w-full h-full object-cover opacity-20"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-primary-dark to-transparent"></div>
    </div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-3xl animate-fade-up">
            <h1 class="text-4xl md:text-5xl font-heading font-bold text-white mb-6">Visi & Misi</h1>
            <p class="text-lg text-gray-200">
                Menjadi lembaga pendidikan Islam unggul yang melahirkan generasi Qurani, berilmu, berprestasi, dan berkarakter.
            </p>
        </div>
    </div>
</div>

<section class="py-20 bg-bg-base relative -mt-10 rounded-t-[40px] z-20">
    <div class="container mx-auto px-6">
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
            <!-- Visi -->
            <div 
                class="bg-primary/5 rounded-[30px] p-10 border border-primary/10 relative overflow-hidden transition-all duration-500 ease-in-out hover:scale-[1.02] hover:shadow-xl group"
                data-aos="zoom-in"
                data-aos-duration="700"
            >
                <div class="absolute -bottom-10 -right-10 opacity-10 group-hover:scale-110 transition-transform duration-700">
                    <Target size={200} class="text-primary" />
                </div>
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-16 h-16 bg-primary rounded-2xl flex items-center justify-center text-white shadow-lg">
                            <Target size={32} />
                        </div>
                        <h2 class="text-4xl font-heading font-bold text-text-title">Visi</h2>
                    </div>
                    <p class="text-2xl font-heading font-semibold text-text-title leading-relaxed">
                        "{visiText}"
                    </p>
                </div>
            </div>
            
            <!-- Misi -->
            <div 
                class="bg-white rounded-[30px] p-10 shadow-card border border-border-color/50 transition-all duration-500 ease-in-out hover:scale-[1.02] hover:shadow-xl group"
                data-aos="zoom-in"
                data-aos-delay="200"
                data-aos-duration="700"
            >
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-16 h-16 bg-primary-dark rounded-2xl flex items-center justify-center text-white shadow-lg">
                        <ListChecks size={32} />
                    </div>
                    <h2 class="text-4xl font-heading font-bold text-text-title">Misi</h2>
                </div>
                
                <ul class="space-y-6">
                    {#each misiList as misi, i}
                        <li class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm shrink-0 mt-1">
                                {i + 1}
                            </div>
                            <p class="text-text-body leading-relaxed">{misi}</p>
                        </li>
                    {/each}
                </ul>
            </div>
        </div>
        
        <!-- Nilai Nilai -->
        <div class="text-center max-w-2xl mx-auto mb-12">
            <h2 class="text-3xl font-heading font-bold text-text-title mb-4">Nilai-Nilai Kami</h2>
            <p class="text-text-body">Nilai dasar yang menjadi pedoman dalam penyelenggaraan pendidikan di Pondok Pesantren Ulul Albab.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {#each nilaiNilai as nilai, i}
                <div data-aos="zoom-in" data-aos-delay={i * 100} data-aos-duration="600">
                    <div class="bg-white rounded-[20px] p-6 shadow-card border border-border-color/50 flex items-start gap-4 group transition-all duration-500 ease-in-out hover:scale-[1.03] hover:shadow-xl hover:border-primary/40 hover:-translate-y-1.5 h-full">
                        <div class="mt-1 shrink-0 text-primary group-hover:scale-125 transition-transform duration-300">
                            <CheckCircle2 size={24} />
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-lg text-text-title mb-2 group-hover:text-primary transition-colors">{nilai.title}</h3>
                            <p class="text-text-body text-sm leading-relaxed">{nilai.desc}</p>
                        </div>
                    </div>
                </div>
            {/each}
        </div>

    </div>
</section>
