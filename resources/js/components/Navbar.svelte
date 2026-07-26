<script>
    import { onMount } from "svelte";
    import { Menu, X } from "lucide-svelte";
    import { menuItems } from "../stores/menu";
    import { mobileMenuOpen } from "../stores/theme";
    import Button from "./Button.svelte";
    import logo from "../assets/logo/ub.jpeg";

    let scrolled = $state(false);

    onMount(() => {
        const handleScroll = () => {
            scrolled = window.scrollY > 20;
        };
        window.addEventListener("scroll", handleScroll);
        return () => window.removeEventListener("scroll", handleScroll);
    });

    // Mencegah scroll pada body saat menu mobile terbuka agar navbar & halaman tidak ikut bergerak/terscroll
    $effect(() => {
        if ($mobileMenuOpen) {
            document.body.style.overflow = "hidden";
        } else {
            document.body.style.overflow = "";
        }
    });

    const toggleMenu = () => {
        $mobileMenuOpen = !$mobileMenuOpen;
    };

    const closeMenu = () => {
        $mobileMenuOpen = false;
    };
</script>

<header
    class="fixed top-0 left-0 right-0 z-[100] transition-all duration-300 ease-in-out {scrolled || $mobileMenuOpen
        ? 'bg-white/95 backdrop-blur-lg shadow-md py-2.5 md:py-3.5 border-b border-border-color/30'
        : 'bg-transparent py-4 md:py-5'}"
>
    <div class="container mx-auto px-6">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <a
                href="/#/"
                class="flex items-center gap-2.5 md:gap-3 relative z-[101] group"
                onclick={closeMenu}
            >
                <img
                    src={logo}
                    alt="Logo Pondok Pesantren Ulul Albab Makassar"
                    class="object-contain rounded-full bg-white p-1 shadow-md transition-all duration-300 group-hover:scale-105 group-hover:rotate-3 {scrolled && !$mobileMenuOpen ? 'w-10 h-10 md:w-12 md:h-12' : 'w-12 h-12 md:w-14 md:h-14'}"
                />

                <div
                    class="transition-colors duration-300 {scrolled || $mobileMenuOpen
                        ? 'text-text-title'
                        : 'text-white'}"
                >
                    <h1 class="font-heading text-base md:text-lg font-bold group-hover:text-primary transition-colors leading-tight">
                        Pondok Pesantren
                    </h1>

                    <p class="text-xs md:text-sm font-semibold leading-tight">
                        Ulul Albab Makassar
                    </p>
                </div>
            </a>

            <!-- Desktop Menu -->
            <nav class="hidden lg:flex items-center gap-1 xl:gap-2">
                {#each $menuItems as item}
                    <a
                        href="/#{item.path}"
                        class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-300 hover:bg-primary/10 hover:scale-105 {scrolled
                            ? 'text-text-title hover:text-primary'
                            : 'text-white/90 hover:text-white'}"
                    >
                        {item.name}
                    </a>
                {/each}
                <div class="ml-4">
                    <Button href="/#/ppdb" size="sm" class="shadow-none"
                        >Daftar PPDB</Button
                    >
                </div>
            </nav>

            <!-- Mobile Menu Toggle -->
            <button
                class="lg:hidden relative z-[101] p-2 rounded-lg transition-colors {scrolled ||
                $mobileMenuOpen
                    ? 'text-text-title hover:bg-bg-section'
                    : 'text-white hover:bg-white/10'}"
                onclick={toggleMenu}
                aria-label="Toggle Menu"
            >
                {#if $mobileMenuOpen}
                    <X size={24} />
                {:else}
                    <Menu size={24} />
                {/if}
            </button>
        </div>
    </div>
</header>

<!-- Mobile Menu Overlay (ditempatkan di luar header agar tidak terpengaruh animasi transisi/scroll header) -->
<div
    class="fixed inset-0 bg-white z-[99] transition-transform duration-500 ease-in-out lg:hidden pt-24 {$mobileMenuOpen
        ? 'translate-x-0 shadow-2xl'
        : 'translate-x-full'}"
>
    <div class="container mx-auto px-6 h-full overflow-y-auto pb-24">
        <nav class="flex flex-col gap-2">
            {#each $menuItems as item}
                <a
                    href="/#{item.path}"
                    class="px-4 py-3 text-lg font-medium text-text-title rounded-xl hover:bg-bg-section active:bg-primary/10 transition-colors"
                    onclick={closeMenu}
                >
                    {item.name}
                </a>
            {/each}
            <div class="mt-6 pt-6 border-t border-border-color">
                <Button
                    href="/#/ppdb"
                    class="w-full justify-center"
                    onclick={closeMenu}>Daftar PPDB Sekarang</Button
                >
            </div>
        </nav>
    </div>
</div>
