<script>
    import { Search } from 'lucide-svelte';
    import { lazyload } from '../animations/actions/lazyload';

    let { 
        image = 'https://images.unsplash.com/photo-1577896851231-70ef18881754?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 
        title = 'Kegiatan Santri', 
        category = 'Akademik',
        onclick = () => {} 
    } = $props();
</script>

<div 
    class="group relative overflow-hidden rounded-[20px] shadow-card bg-white h-72 cursor-pointer transition-all duration-500 hover:shadow-xl hover:-translate-y-1.5"
    {onclick}
    role="button"
    tabindex="0"
    onkeydown={(e) => (e.key === 'Enter' || e.key === ' ') && onclick()}
>
    <img 
        src={image} 
        alt={title} 
        use:lazyload
        class="w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-110"
    />
    
    <!-- Overlay Hitam Transparan -->
    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
        <div class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
            <span class="inline-block px-3 py-1 bg-primary text-white text-xs font-semibold rounded-full mb-2">
                {category}
            </span>
            <h3 class="text-white font-heading text-lg font-semibold flex items-center justify-between">
                {title}
                <!-- Icon Search muncul saat dihover -->
                <span class="bg-white/20 group-hover:bg-primary p-2.5 rounded-full backdrop-blur-sm transition-all duration-300 transform group-hover:scale-110 flex items-center justify-center">
                    <Search size={18} class="text-white" />
                </span>
            </h3>
        </div>
    </div>
</div>
