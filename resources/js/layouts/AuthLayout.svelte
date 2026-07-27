<script>
    let { children } = $props();
</script>

<div class="min-h-screen bg-bg-section font-sans text-text-body relative overflow-x-hidden">
    {@render children?.()}
</div>
