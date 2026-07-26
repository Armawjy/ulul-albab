<script>
    import Button from '../../components/Button.svelte';
</script>

<div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md relative z-10 animate-fade-up">
        <div class="glass rounded-3xl p-8 shadow-soft text-center">
            <h1 class="text-2xl font-heading font-bold text-text-title mb-4">Lupa Password</h1>
            <p class="text-sm text-text-body mb-6">Hubungi super admin (developer) untuk mereset kata sandi Anda secara manual melalui database.</p>
            <Button href="#/auth/login" class="w-full justify-center">Kembali ke Login</Button>
        </div>
    </div>
</div>
