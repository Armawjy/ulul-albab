<script>
    import { Calendar, User, ArrowRight } from 'lucide-svelte';
    import { lazyload } from '../animations/actions/lazyload';
    let { 
        image = 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
        title = 'Penerimaan Santri Baru Tahun Ajaran 2026/2027 Resmi Dibuka',
        excerpt = 'Pondok Pesantren Ulul Albab Makassar kembali membuka pendaftaran santri baru untuk jenjang SMA/MA...',
        date = '24 Juli 2026',
        author = 'Admin',
        slug = '#',
        onImageClick = () => {}
    } = $props();
</script>

<div 
    class="bg-white rounded-[20px] overflow-hidden shadow-card border border-border-color/50 transition-all duration-500 ease-in-out hover:scale-[1.03] hover:shadow-xl hover:border-primary/40 hover:-translate-y-1.5 group flex flex-col h-full"
>
    <div 
        class="relative h-56 overflow-hidden cursor-pointer"
        onclick={onImageClick}
        role="button"
        tabindex="0"
        onkeydown={(e) => (e.key === 'Enter' || e.key === ' ') && onImageClick()}
    >
        <img 
            src={image} 
            alt={title} 
            use:lazyload
            class="w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-110"
        />
        <div class="absolute inset-0 bg-primary/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
            <span class="bg-black/60 text-white text-xs px-3 py-1.5 rounded-full font-medium backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity">
                Klik Lihat Foto
            </span>
        </div>
    </div>
    
    <div class="p-6 flex flex-col flex-grow">
        <div class="flex items-center gap-4 text-xs text-text-body mb-3">
            <div class="flex items-center gap-1.5">
                <Calendar size={14} class="text-primary" />
                <span>{date}</span>
            </div>
            <div class="flex items-center gap-1.5">
                <User size={14} class="text-primary" />
                <span>{author}</span>
            </div>
        </div>
        
        <h3 class="font-heading font-semibold text-text-title text-xl mb-3 line-clamp-2 group-hover:text-primary transition-colors">
            <a href={slug}>{title}</a>
        </h3>
        
        <p class="text-text-body text-sm mb-5 line-clamp-3 flex-grow">
            {excerpt}
        </p>
        
        <div class="mt-auto">
            <a href={slug} class="inline-flex items-center gap-2 text-primary font-medium hover:text-primary-hover transition-colors text-sm">
                Baca Selengkapnya
                <ArrowRight size={16} class="transform group-hover:translate-x-1 transition-transform" />
            </a>
        </div>
    </div>
</div>
