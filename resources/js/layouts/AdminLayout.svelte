<script>
    import {
        LayoutDashboard,
        FileText,
        Image as ImageIcon,
        Users,
        LogOut,
        Settings,
        Award,
        GraduationCap,
        Menu,
        X
    } from "lucide-svelte";
    import logo from "../assets/logo/ub.jpeg";

    let { children } = $props();
    let currentHash = $state(window.location.hash);
    let currentTime = $state(new Date());
    let isMobileOpen = $state(false);
    let isDesktopCollapsed = $state(false);

    $effect(() => {
        const handleHash = () => (currentHash = window.location.hash);
        window.addEventListener("hashchange", handleHash);
        
        const timer = setInterval(() => {
            currentTime = new Date();
        }, 1000);
        
        return () => {
            window.removeEventListener("hashchange", handleHash);
            clearInterval(timer);
        };
    });

    const formatTime = (date) => {
        return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }).replace(/\./g, ':');
    };

    const formatDate = (date) => {
        return date.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
    };

    const menu = [
        { name: "Dashboard", path: "#/admin/dashboard", icon: LayoutDashboard },
        { name: "Berita", path: "#/admin/berita", icon: FileText },
        { name: "Galeri", path: "#/admin/galeri", icon: ImageIcon },
        { name: "Guru", path: "#/admin/guru", icon: Users },
        { name: "Program", path: "#/admin/program", icon: GraduationCap },
        { name: "Prestasi", path: "#/admin/prestasi", icon: Award },
        { name: "PPDB", path: "#/admin/ppdb", icon: Users },
        { name: "Pengaturan", path: "#/admin/setting", icon: Settings },
    ];
</script>

<div class="min-h-screen bg-bg-section flex overflow-hidden">
    <!-- Overlay for Mobile -->
    {#if isMobileOpen}
        <div class="fixed inset-0 bg-black/50 z-[90] lg:hidden" onclick={() => isMobileOpen = false}></div>
    {/if}

    <!-- Sidebar -->
    <aside
        class="fixed inset-y-0 left-0 z-[100] bg-white border-r border-border-color flex flex-col transition-all duration-300
        {isMobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'}
        {isDesktopCollapsed ? 'lg:w-20' : 'w-72'}"
    >
        <div class="h-[72px] px-6 border-b border-border-color flex items-center justify-between">
            <div class="flex items-center gap-3 overflow-hidden">
                <img
                    src={logo}
                    alt="Logo"
                    class="w-10 h-10 min-w-[40px] object-contain rounded-xl bg-white shadow-sm"
                />
                {#if !isDesktopCollapsed}
                <div class="whitespace-nowrap transition-opacity">
                    <h2 class="font-heading font-bold text-text-title leading-tight">Admin Panel</h2>
                    <p class="text-[10px] text-text-body truncate">Pesantren Ulul Albab</p>
                </div>
                {/if}
            </div>
            <!-- Mobile close button -->
            <button class="lg:hidden text-text-body hover:text-primary transition-colors" onclick={() => isMobileOpen = false}>
                <X size={24} />
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto p-4 space-y-2">
            {#each menu as item}
                <a
                    href={item.path}
                    title={isDesktopCollapsed ? item.name : ''}
                    onclick={() => isMobileOpen = false}
                    class="flex items-center gap-3 rounded-xl text-sm font-medium transition-colors {isDesktopCollapsed ? 'justify-center p-3' : 'px-4 py-3'}
                    {currentHash === item.path
                        ? 'bg-primary text-white shadow-md'
                        : 'text-text-body hover:bg-bg-section hover:text-text-title'}"
                >
                    <item.icon size={20} class="min-w-[20px]" />
                    {#if !isDesktopCollapsed}
                        <span class="whitespace-nowrap">{item.name}</span>
                    {/if}
                </a>
            {/each}
        </nav>

        <div class="p-4 border-t border-border-color">
            <a
                href="#/auth/login"
                title={isDesktopCollapsed ? 'Keluar' : ''}
                class="flex items-center gap-3 rounded-xl text-sm font-medium text-red-500 hover:bg-red-50 transition-colors {isDesktopCollapsed ? 'justify-center p-3' : 'px-4 py-3'}"
            >
                <LogOut size={20} class="min-w-[20px]" />
                {#if !isDesktopCollapsed}
                    <span class="whitespace-nowrap">Keluar</span>
                {/if}
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col min-w-0 min-h-screen transition-all duration-300 {isDesktopCollapsed ? 'lg:ml-20' : 'lg:ml-72'}">
        <header
            class="bg-white border-b border-border-color h-[72px] flex items-center px-6 sticky top-0 z-10 relative"
        >
            <div class="flex items-center gap-4">
                <button 
                    class="text-text-body hover:text-primary transition-colors focus:outline-none p-1 rounded-lg hover:bg-bg-section"
                    onclick={() => {
                        if (window.innerWidth >= 1024) {
                            isDesktopCollapsed = !isDesktopCollapsed;
                        } else {
                            isMobileOpen = true;
                        }
                    }}
                >
                    <Menu size={24} />
                </button>
            </div>
            
            <!-- Clock & Date (Center) -->
            <div class="absolute left-1/2 -translate-x-1/2 flex flex-col items-center justify-center hidden md:flex">
                <div class="text-sm font-bold text-primary font-mono tracking-widest bg-primary/10 px-4 py-1 rounded-full mb-0.5 shadow-sm border border-primary/20">
                    {formatTime(currentTime)}
                </div>
                <div class="text-[11px] text-text-body font-medium uppercase tracking-wider">
                    {formatDate(currentTime)}
                </div>
            </div>

            <div class="flex-1"></div>
            
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-text-title leading-tight">
                        Admin Master
                    </p>
                    <p class="text-xs text-text-body">admin@ululalbab.com</p>
                </div>
                <div
                    class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold"
                >
                    A
                </div>
            </div>
        </header>

        <div class="p-4 sm:p-6 md:p-8 flex-1 min-w-0 w-full overflow-x-hidden">
            {@render children?.()}
        </div>
    </main>
</div>
