<script>
    import { countup } from '../animations/actions/countup';
    
    let { 
        value = 0, 
        label = 'Label', 
        suffix = '+',
        icon: Icon = null
    } = $props();
</script>

<div 
    class="glass dark:glass-dark rounded-[20px] p-6 md:p-8 flex items-center gap-6 shadow-soft hover:-translate-y-2 hover:shadow-xl transition-all duration-500 ease-in-out group"
    data-aos="zoom-in"
    data-aos-duration="600"
>
    {#if Icon}
        <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center text-primary shrink-0 transition-transform duration-500 group-hover:scale-110 group-hover:bg-primary group-hover:text-white">
            <Icon size={32} />
        </div>
    {/if}
    <div>
        <div 
            class="text-3xl md:text-4xl font-heading font-bold text-text-title mb-1"
            use:countup={{ endVal: value, suffix, duration: 2.5 }}
        >
            0{suffix}
        </div>
        <div class="text-text-body font-medium group-hover:text-primary transition-colors">{label}</div>
    </div>
</div>

