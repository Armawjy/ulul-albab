<script>
    import { LogIn, Mail, Lock, AlertCircle } from 'lucide-svelte';
    import Button from '../../components/Button.svelte';
    import logo from '../../assets/logo/ub.jpeg';


    let email = $state('');
    let password = $state('');
    let remember = $state(false);
    let loading = $state(false);
    let error = $state('');

    const handleLogin = async (e) => {
        e.preventDefault();
        loading = true;
        error = '';
        
        try {
            // Mock login for now
            setTimeout(() => {
                loading = false;
                window.location.hash = '#/admin/dashboard';
            }, 1000);
        } catch (err) {
            loading = false;
            error = 'Kredensial tidak valid.';
        }
    };
</script>

<div class="min-h-screen flex items-center justify-center p-4">
    <!-- Decorative Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-20 -left-20 w-96 h-96 bg-primary/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-20 -right-20 w-96 h-96 bg-primary-hover/20 rounded-full blur-3xl"></div>
    </div>

    <div class="w-full max-w-md relative z-10 animate-fade-up">
        <!-- Card -->
        <div class="glass rounded-3xl p-8 shadow-soft">
            <div class="text-center mb-8">
                <img src={logo} alt="Logo" class="w-16 h-16 object-contain rounded-full bg-white shadow-lg mx-auto mb-4 p-1" />
                <h1 class="text-2xl font-heading font-bold text-text-title">Selamat Datang</h1>
                <p class="text-sm text-text-body mt-2">Login ke Dashboard Admin Ulul Albab</p>
            </div>

            {#if error}
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 flex items-start gap-3 animate-fade-up">
                    <AlertCircle size={20} class="text-red-500 mt-0.5 shrink-0" />
                    <p class="text-sm text-red-600">{error}</p>
                </div>
            {/if}

            <form onsubmit={handleLogin} class="space-y-5">
                <div class="space-y-1.5">
                    <label for="email" class="text-sm font-medium text-text-title ml-1">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <Mail size={18} class="text-gray-400" />
                        </div>
                        <input
                            type="email"
                            id="email"
                            bind:value={email}
                            class="w-full pl-11 pr-4 py-3 bg-white/50 border border-border-color rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-text-title"
                            placeholder="admin@gmail.com"
                            required
                        />
                    </div>
                </div>

                <div class="space-y-1.5">
                    <div class="flex items-center justify-between ml-1">
                        <label for="password" class="text-sm font-medium text-text-title">Password</label>
                        <a href="#/auth/forgot-password" class="text-xs text-primary hover:text-primary-hover font-medium transition-colors">Lupa Password?</a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <Lock size={18} class="text-gray-400" />
                        </div>
                        <input
                            type="password"
                            id="password"
                            bind:value={password}
                            class="w-full pl-11 pr-4 py-3 bg-white/50 border border-border-color rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-text-title"
                            placeholder="••••••••"
                            required
                        />
                    </div>
                </div>

                <div class="flex items-center gap-2 ml-1">
                    <input
                        type="checkbox"
                        id="remember"
                        bind:checked={remember}
                        class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary"
                    />
                    <label for="remember" class="text-sm text-text-body cursor-pointer">
                        Ingat Saya
                    </label>
                </div>

                <Button
                    type="submit"
                    class="w-full justify-center py-3.5 text-base mt-2"
                    disabled={loading}
                >
                    {#if loading}
                        <div class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                        <span class="ml-2">Memeriksa...</span>
                    {:else}
                        <LogIn size={20} class="mr-2" />
                        Login ke Dashboard
                    {/if}
                </Button>
            </form>
        </div>

        <p class="text-center text-xs text-text-body mt-6">
            &copy; {new Date().getFullYear()} Pondok Pesantren Ulul Albab Makassar. <br> All rights reserved.
        </p>
    </div>
</div>
