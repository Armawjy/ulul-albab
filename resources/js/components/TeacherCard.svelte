<script>
    import { BookOpen } from 'lucide-svelte';
    import { lazyload } from '../animations/actions/lazyload';
    let { 
        image = 'https://images.unsplash.com/photo-1544717302-de2939b7ef71?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
        name = 'Ust. H. Ahmad Fulan, Lc., M.A.',
        role = 'Kepala Sekolah SMA/MA',
        subject = 'Tafsir Al-Qur\'an',
        onclick = () => {}
    } = $props();
</script>

<div 
    class="group bg-white rounded-[20px] shadow-card border border-border-color/50 overflow-hidden transition-all duration-500 ease-in-out hover:-translate-y-2.5 hover:shadow-xl hover:border-primary/40 text-center p-6 cursor-pointer"
    {onclick}
    role="button"
    tabindex="0"
    onkeydown={(e) => (e.key === 'Enter' || e.key === ' ') && onclick()}
>
    <div class="relative w-32 h-32 mx-auto mb-5 rounded-full overflow-hidden border-4 border-primary/10 group-hover:border-primary/40 transition-colors shadow-sm group-hover:shadow-md">
        <img 
            src={image} 
            alt={name} 
            use:lazyload
            class="w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-110"
        />
    </div>
    
    <h3 class="font-heading font-semibold text-lg text-text-title mb-1 group-hover:text-primary transition-colors">
        {name}
    </h3>
    <p class="text-text-body text-sm mb-4">{role}</p>
    
    <div class="inline-flex items-center justify-center gap-2 px-4 py-1.5 bg-bg-section rounded-full text-primary text-xs font-medium w-full">
        <BookOpen size={14} />
        {subject}
    </div>
</div>
