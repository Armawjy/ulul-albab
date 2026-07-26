<script>
    let { 
        width = 'w-full', 
        height = 'h-6', 
        rounded = 'rounded-xl',
        class: className = '',
        ...rest 
    } = $props();
</script>

<div 
    class="relative overflow-hidden bg-gray-200/80 dark:bg-gray-700/50 {width} {height} {rounded} {className}" 
    {...rest}
>
    <!-- Shimmer wave effect -->
    <div class="absolute inset-0 -translate-x-full animate-[shimmer_1.5s_infinite] bg-gradient-to-r from-transparent via-white/50 to-transparent dark:via-white/10"></div>
</div>

<style>
    @keyframes shimmer {
        100% {
            transform: translateX(100%);
        }
    }
</style>
