<script>
    import Button from '../components/Button.svelte';
    import { AlertTriangle, ArrowLeft } from 'lucide-svelte';
</script>

<svelte:head>
    <title>404 Not Found - Pondok Pesantren Ulul Albab Makassar</title>
</svelte:head>

<div class="min-h-[70vh] flex items-center justify-center pt-24 pb-16">
    <div class="text-center px-6 max-w-lg animate-zoom">
        <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-8 text-red-500 shadow-md">
            <AlertTriangle size={48} />
        </div>
        
        <h1 class="text-8xl font-heading font-bold text-primary-dark mb-4">404</h1>
        <h2 class="text-2xl font-heading font-bold text-text-title mb-4">Halaman Tidak Ditemukan</h2>
        
        <p class="text-text-body mb-8">
            Maaf, halaman yang Anda cari mungkin telah dihapus, namanya diubah, atau sementara tidak tersedia.
        </p>
        
        <Button href="/#/" class="gap-2 inline-flex items-center">
            <ArrowLeft size={18} />
            Kembali ke Beranda
        </Button>
    </div>
</div>
