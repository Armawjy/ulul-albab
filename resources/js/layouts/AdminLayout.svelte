<script>
    import { onMount } from "svelte";
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
        X,
        Camera,
        Save,
        User,
        ShieldCheck,
        Mail,
        Briefcase,
        CheckCircle2
    } from "lucide-svelte";
    import logo from "../assets/logo/ub.jpeg";

    let { children } = $props();
    let currentHash = $state(window.location.hash);
    let currentTime = $state(new Date());
    let isMobileOpen = $state(false);
    let isDesktopCollapsed = $state(false);

    let showProfileModal = $state(false);
    let adminProfile = $state({
        name: 'dev: Arma Wijaya',
        role: 'DEVELOPER WEBSITE',
        email: 'ponpesululalbabmks@gmail.com',
        bio: 'Creator & Lead Developer Sistem Informasi Portal Resmi Pondok Pesantren Ulul Albab Makassar.',
        photoUrl: null
    });
    let editForm = $state({ ...adminProfile });
    let toastMsg = $state('');

    onMount(() => {
        const saved = localStorage.getItem('admin_profile_custom');
        if (saved) {
            try {
                const parsed = JSON.parse(saved);
                adminProfile = { ...adminProfile, ...parsed };
                editForm = { ...adminProfile };
            } catch (e) {}
        }
    });

    const handlePhotoUpload = (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                editForm.photoUrl = event.target.result;
            };
            reader.readAsDataURL(file);
        }
    };

    const saveProfile = () => {
        adminProfile = { ...editForm };
        localStorage.setItem('admin_profile_custom', JSON.stringify(adminProfile));
        toastMsg = 'Profil berhasil disimpan!';
        setTimeout(() => {
            toastMsg = '';
            showProfileModal = false;
        }, 1200);
    };

    const resetProfile = () => {
        editForm = {
            name: 'dev: Arma Wijaya',
            role: 'DEVELOPER WEBSITE',
            email: 'ponpesululalbabmks@gmail.com',
            bio: 'Creator & Lead Developer Sistem Informasi Portal Resmi Pondok Pesantren Ulul Albab Makassar.',
            photoUrl: null
        };
    };

    onMount(() => {
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
            <!-- {#if !isDesktopCollapsed}
            <div class="mb-3 px-3 py-2.5 bg-gradient-to-r from-emerald-500/10 to-teal-500/10 rounded-xl border border-emerald-500/20 text-center shadow-sm">
                <span class="text-[9px] font-extrabold text-emerald-600 uppercase tracking-wider block">Created & Developed By</span>
                <span class="text-xs font-black text-emerald-800 font-heading tracking-wide block mt-0.5">{adminProfile.name}</span>
            </div>
            {/if} -->
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
            
            <button 
                type="button"
                onclick={() => { showProfileModal = true; editForm = { ...adminProfile }; }}
                class="flex items-center gap-3 p-1.5 rounded-2xl hover:bg-bg-section transition-all text-left cursor-pointer group"
                title="Klik untuk lihat & ganti foto profil pengembang"
            >
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-extrabold text-text-title leading-tight font-heading group-hover:text-primary transition-colors">
                        {adminProfile.name}
                    </p>
                    <p class="text-[11px] text-emerald-600 font-bold tracking-wide uppercase">{adminProfile.role}</p>
                </div>
                {#if adminProfile.photoUrl}
                    <img src={adminProfile.photoUrl} alt="Avatar" class="w-10 h-10 rounded-full object-cover border-2 border-emerald-500 shadow-md group-hover:scale-105 transition-transform" />
                {:else}
                    <div
                        class="w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center font-extrabold shadow-md group-hover:scale-105 transition-transform"
                    >
                        {adminProfile.name ? adminProfile.name.replace('dev:', '').trim().split(' ').map(n=>n[0]).join('').substring(0,2).toUpperCase() : 'AW'}
                    </div>
                {/if}
            </button>
        </header>

        <div class="p-4 sm:p-6 md:p-8 flex-1 min-w-0 w-full overflow-x-hidden">
            {@render children?.()}
        </div>

        <!-- Modal Kartu Identitas & Edit Profil Pengembang -->
        {#if showProfileModal}
            <div class="fixed inset-0 z-[9999] bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 animate-fade-in">
                <div class="bg-white rounded-[24px] shadow-2xl border border-border-color/50 max-w-md w-full overflow-hidden animate-scale-up relative">
                    <!-- Header Modal -->
                    <div class="bg-gradient-to-r from-emerald-600 to-teal-700 p-6 text-white relative">
                        <button 
                            type="button" 
                            onclick={() => showProfileModal = false}
                            class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors cursor-pointer"
                        >
                            <X size={18} />
                        </button>
                        <div class="flex items-center gap-2 text-emerald-200 text-xs font-bold uppercase tracking-wider mb-1">
                            <ShieldCheck size={16} /> Profil Pengembang & Administrator
                        </div>
                        <h3 class="font-heading font-extrabold text-xl">Kartu Identitas Developer</h3>
                    </div>

                    <div class="p-6 space-y-5">
                        {#if toastMsg}
                            <div class="p-3 rounded-xl bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs font-bold flex items-center justify-center gap-2">
                                <CheckCircle2 size={16} /> {toastMsg}
                            </div>
                        {/if}

                        <!-- Avatar Preview & Upload -->
                        <div class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-2xl border border-dashed border-gray-300 relative group">
                            <div class="relative mb-3">
                                {#if editForm.photoUrl}
                                    <img src={editForm.photoUrl} alt="Avatar" class="w-24 h-24 rounded-full object-cover border-4 border-emerald-500 shadow-lg" />
                                    <button 
                                        type="button" 
                                        onclick={() => editForm.photoUrl = null}
                                        class="absolute -top-1 -right-1 bg-red-500 text-white p-1 rounded-full text-[10px] hover:bg-red-600 shadow cursor-pointer"
                                        title="Hapus foto"
                                    >
                                        <X size={12} />
                                    </button>
                                {:else}
                                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-emerald-500 to-teal-700 text-white flex items-center justify-center font-extrabold text-3xl shadow-lg border-4 border-white">
                                        {editForm.name ? editForm.name.replace('dev:', '').trim().split(' ').map(n=>n[0]).join('').substring(0,2).toUpperCase() : 'AW'}
                                    </div>
                                {/if}
                                <label class="absolute bottom-0 right-0 bg-emerald-600 hover:bg-emerald-700 text-white p-2 rounded-full cursor-pointer shadow-md transition-transform hover:scale-110" title="Upload Foto Baru">
                                    <Camera size={16} />
                                    <input type="file" accept="image/*" class="hidden" onchange={handlePhotoUpload} />
                                </label>
                            </div>
                            <span class="text-xs font-bold text-gray-700">Foto Profil Developer</span>
                            <span class="text-[10px] text-gray-400">Klik ikon kamera untuk upload foto JPG/PNG Anda</span>
                        </div>

                        <!-- Form Inputs -->
                        <div class="space-y-3">
                            <div>
                                <label class="block text-[11px] font-bold text-text-body uppercase mb-1 flex items-center gap-1.5">
                                    <User size={14} class="text-emerald-600" /> Nama Lengkap / Tag
                                </label>
                                <input 
                                    type="text" 
                                    bind:value={editForm.name} 
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-border-color focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm font-bold text-text-title outline-none transition-all bg-gray-50/50"
                                    placeholder="Contoh: dev: Arma Wijaya"
                                />
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-bold text-text-body uppercase mb-1 flex items-center gap-1.5">
                                        <Briefcase size={14} class="text-emerald-600" /> Jabatan / Role
                                    </label>
                                    <input 
                                        type="text" 
                                        bind:value={editForm.role} 
                                        class="w-full px-3 py-2 rounded-xl border border-border-color focus:border-emerald-500 text-xs font-bold text-text-title outline-none bg-gray-50/50"
                                        placeholder="DEVELOPER WEBSITE"
                                    />
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-text-body uppercase mb-1 flex items-center gap-1.5">
                                        <Mail size={14} class="text-emerald-600" /> Email Kontak
                                    </label>
                                    <input 
                                        type="text" 
                                        bind:value={editForm.email} 
                                        class="w-full px-3 py-2 rounded-xl border border-border-color focus:border-emerald-500 text-xs font-medium text-text-title outline-none bg-gray-50/50"
                                        placeholder="email@domain.com"
                                    />
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-text-body uppercase mb-1">
                                    Bio / Catatan Singkat
                                </label>
                                <textarea 
                                    bind:value={editForm.bio} 
                                    rows="2" 
                                    class="w-full px-3 py-2 rounded-xl border border-border-color focus:border-emerald-500 text-xs text-text-body outline-none resize-none bg-gray-50/50"
                                    placeholder="Tuliskan catatan singkat pengembang..."
                                ></textarea>
                            </div>
                        </div>

                        <!-- Tombol Simpan & Reset -->
                        <div class="pt-2 flex items-center justify-between gap-3 border-t border-border-color/50">
                            <button 
                                type="button"
                                onclick={resetProfile}
                                class="px-4 py-2.5 rounded-xl border border-gray-300 hover:bg-gray-100 text-xs font-bold text-gray-600 transition-colors cursor-pointer"
                            >
                                Reset Default
                            </button>
                            <button 
                                type="button" 
                                onclick={saveProfile}
                                class="flex-1 py-2.5 px-4 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-xs font-extrabold flex items-center justify-center gap-2 shadow-md hover:shadow-lg transition-all cursor-pointer"
                            >
                                <Save size={16} /> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        {/if}
    </main>
</div>
