<script>
    import { onMount } from 'svelte';
    import { initAOS, refreshAOS } from './animations/aos';
    import LoadingScreen from './animations/components/LoadingScreen.svelte';
    import MainLayout from './layouts/MainLayout.svelte';
    import AdminLayout from './layouts/AdminLayout.svelte';
    import AuthLayout from './layouts/AuthLayout.svelte';

    // Import Public pages
    import Home from './pages/Home.svelte';
    import Profil from './pages/Profil.svelte';
    import Sejarah from './pages/Sejarah.svelte';
    import VisiMisi from './pages/VisiMisi.svelte';
    import Program from './pages/Program.svelte';
    import Guru from './pages/Guru.svelte';
    import Prestasi from './pages/Prestasi.svelte';
    import Galeri from './pages/Galeri.svelte';
    import Berita from './pages/Berita.svelte';
    import PPDB from './pages/PPDB.svelte';
    import Kontak from './pages/Kontak.svelte';
    import NotFound from './pages/NotFound.svelte';

    // Import Auth pages
    import Login from './pages/auth/Login.svelte';
    import ForgotPassword from './pages/auth/ForgotPassword.svelte';

    // Import Admin pages
    import Dashboard from './pages/admin/Dashboard.svelte';
    import AdminBerita from './pages/admin/Berita.svelte';
    import AdminGuru from './pages/admin/Guru.svelte';
    import AdminProfil from './pages/admin/Profil.svelte';
    import AdminSejarah from './pages/admin/Sejarah.svelte';
    import AdminVisiMisi from './pages/admin/VisiMisi.svelte';
    import AdminProgram from './pages/admin/Program.svelte';
    import AdminPrestasi from './pages/admin/Prestasi.svelte';
    import AdminGaleri from './pages/admin/Galeri.svelte';
    import AdminPPDB from './pages/admin/PPDB.svelte';
    import AdminKontak from './pages/admin/Kontak.svelte';
    import AdminSlider from './pages/admin/Slider.svelte';
    import AdminSetting from './pages/admin/Setting.svelte';
    import AdminUser from './pages/admin/User.svelte';

    // Simple Hash Router
    let currentRoute = $state('/');
    
    // Determine Layout
    let currentLayout = $derived.by(() => {
        if (currentRoute === '/login' || currentRoute === '/admin' || currentRoute === '/admin/login' || currentRoute.startsWith('/auth')) return AuthLayout;
        if (currentRoute.startsWith('/admin')) return AdminLayout;
        return MainLayout;
    });

    // Derived component to render
    let PageComponent = $derived.by(() => {
        switch (currentRoute) {
            // Public Routes
            case '/': return Home;
            case '/profil': return Profil;
            case '/sejarah': return Sejarah;
            case '/visi-misi': return VisiMisi;
            case '/program': return Program;
            case '/guru': return Guru;
            case '/prestasi': return Prestasi;
            case '/galeri': return Galeri;
            case '/berita': return Berita;
            case '/ppdb': return PPDB;
            case '/kontak': return Kontak;
            
            // Auth / Admin Login Routes
            case '/login':
            case '/admin':
            case '/admin/login':
            case '/auth/login': return Login;
            case '/auth/forgot-password': return ForgotPassword;

            // Admin Routes
            case '/admin/dashboard': return Dashboard;
            case '/admin/berita': return AdminBerita;
            case '/admin/guru': return AdminGuru;
            case '/admin/profil': return AdminProfil;
            case '/admin/sejarah': return AdminSejarah;
            case '/admin/visi-misi': return AdminVisiMisi;
            case '/admin/program': return AdminProgram;
            case '/admin/prestasi': return AdminPrestasi;
            case '/admin/galeri': return AdminGaleri;
            case '/admin/ppdb': return AdminPPDB;
            case '/admin/kontak': return AdminKontak;
            case '/admin/slider': return AdminSlider;
            case '/admin/setting': return AdminSetting;
            case '/admin/user': return AdminUser;

            default: return NotFound;
        }
    });

    let initialLoading = $state(true);

    onMount(() => {
        initAOS();
        
        // Sembunyikan loading screen setelah aplikasi siap (800ms)
        setTimeout(() => {
            initialLoading = false;
            refreshAOS();
        }, 800);

        const hashChange = () => {
            const hash = window.location.hash.replace('#', '') || '/';
            currentRoute = hash;
            window.scrollTo(0, 0);
            refreshAOS();
        };

        window.addEventListener('hashchange', hashChange);
        hashChange(); // Initialize

        return () => {
            window.removeEventListener('hashchange', hashChange);
        };
    });
</script>

<LoadingScreen visible={initialLoading} />

<svelte:component this={currentLayout}>
    <PageComponent />
</svelte:component>