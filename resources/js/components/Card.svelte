<script>
    let { 
        children, 
        class: className = '',
        hoverEffect = true,
        glass = false,
        padding = 'p-6',
        ...rest 
    } = $props();

    const baseClasses = 'bg-white rounded-[20px] shadow-card border border-border-color/50';
    const glassClasses = glass ? 'glass' : '';
    const hoverClasses = hoverEffect ? 'transition-all duration-300 ease-in-out hover:scale-[1.03] hover:shadow-xl hover:border-primary/40 hover:-translate-y-1.5' : '';
</script>

<div 
    class="{baseClasses} {glassClasses} {hoverClasses} {padding} {className}"
    {...rest}
>
    {@render children?.()}
</div>
