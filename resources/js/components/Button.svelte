<script>
    import { ripple } from '../animations/actions/ripple';

    let { 
        children, 
        variant = 'primary', 
        size = 'md', 
        class: className = '', 
        type = 'button',
        href = null,
        ...rest 
    } = $props();

    const variants = {
        primary: 'bg-gradient-to-r from-primary to-primary-light hover:from-primary-hover hover:to-primary text-white shadow-md hover:shadow-lg shadow-primary/20 hover:shadow-primary/40',
        outline: 'border-2 border-primary text-primary hover:bg-gradient-to-r hover:from-primary hover:to-primary-light hover:text-white hover:border-transparent shadow-sm hover:shadow-md',
        ghost: 'text-text-body hover:text-primary hover:bg-primary/10',
        white: 'bg-white text-primary hover:bg-gray-100 shadow-md hover:shadow-lg',
    };

    const sizes = {
        sm: 'px-3 py-1.5 text-xs sm:px-4 sm:py-2 sm:text-sm',
        md: 'px-4 py-2 text-sm sm:px-6 sm:py-3 sm:text-base',
        lg: 'px-6 py-3 text-base sm:px-8 sm:py-4 sm:text-lg',
    };

    const baseClasses = 'inline-flex items-center justify-center font-medium rounded-[20px] transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105 active:scale-95';
</script>

{#if href}
    <a 
        {href}
        use:ripple
        class="{baseClasses} {variants[variant]} {sizes[size]} {className}"
        {...rest}
    >
        {@render children?.()}
    </a>
{:else}
    <button 
        {type}
        use:ripple
        class="{baseClasses} {variants[variant]} {sizes[size]} {className}"
        {...rest}
    >
        {@render children?.()}
    </button>
{/if}
