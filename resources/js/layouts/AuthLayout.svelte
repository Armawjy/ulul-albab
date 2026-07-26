<script>
    let { children } = $props();
</script>

<div class="min-h-screen bg-bg-section flex items-center justify-center p-6 relative overflow-hidden">
    <!-- Decorative Background Elements -->
    <div class="absolute top-[-10%] left-[-5%] w-96 h-96 bg-primary/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-[-10%] right-[-5%] w-96 h-96 bg-primary-hover/10 rounded-full blur-3xl"></div>
    
    <div class="w-full max-w-md relative z-10">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center text-white font-bold text-2xl mx-auto mb-4 shadow-lg">
                UA
            </div>
            <h1 class="font-heading font-bold text-2xl text-text-title">Sistem Informasi</h1>
            <p class="text-text-body text-sm">Pondok Pesantren Ulul Albab Makassar</p>
        </div>
        
        <div class="bg-white rounded-[20px] shadow-soft p-8 border border-border-color/50">
            {@render children?.()}
        </div>
    </div>
</div>
