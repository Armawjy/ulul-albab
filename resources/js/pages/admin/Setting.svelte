<script>
    import { onMount } from 'svelte';
    import { Save, Upload, CheckCircle2, Globe, Phone, Share2, MessageSquare, Image, Loader2, RefreshCw } from 'lucide-svelte';
    import Card from '../../components/Card.svelte';
    import Button from '../../components/Button.svelte';
    import api from '../../services/axios';

    let activeTab = $state('identitas');
    let loading = $state(true);
    let saving = $state(false);
    let toast = $state({ show: false, message: '', type: 'success' });

    let settings = $state({
        // Identitas
        nama_pesantren: 'Pondok Pesantren Ulul Albab Makassar',
        tagline_pesantren: 'Membentuk Generasi Qur\'ani, Berilmu, dan Berakhlak Mulia',
        deskripsi_singkat: 'Pondok Pesantren Ulul Albab Makassar adalah lembaga pendidikan Islam modern yang memadukan kurikulum nasional dan kepesantrenan.',
        logo_website: null,
        logo_website_url: null,

        // Kontak & Alamat
        whatsapp_admin: '6285242501959',
        telepon_kantor: '0858-2424-6172',
        email_resmi: 'info@ululalbab.sch.id',
        alamat_lengkap: 'Jl. Perintis Kemerdekaan KM. 13, Daya, Kec. Biringkanaya, Kota Makassar, Sulawesi Selatan 90241',
        map_embed_url: 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4015.4274588743374!2d119.51975067498182!3d-5.098641394878287!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbefb9d60702f9d%3A0x660d019dcb402681!2sPesantren%20Ulul%20Albab!5e1!3m2!1sid!2sid!4v1785071299434!5m2!1sid!2sid',

        // Media Sosial
        facebook_url: 'https://www.facebook.com/pages/Pondok%20Pesantren%20Ulul%20Albab/837724936276664/',
        youtube_url: 'https://www.youtube.com/channel/UC46nQZaypi8dJOH8oNhhhaw',
        instagram_url: 'https://www.instagram.com/maululalbab_/',

        // Notifikasi & WA PPDB
        wa_notification_admin: '6285242501959',
        wa_greeting_template: 'Assalamu\'alaikum Admin PPDB Pesantren Ulul Albab, saya ingin menanyakan informasi seputar pendaftaran santri baru.'
    });

    let logoFile = $state(null);
    let logoPreview = $state(null);

    const showToast = (message, type = 'success') => { 
        toast = { show: true, message, type }; 
        setTimeout(() => toast.show = false, 3500); 
    };

    const fetchSettings = async () => {
        loading = true;
        try {
            const res = await api.get('/v1/admin/settings');
            const data = res.data || res || {};
            
            Object.keys(settings).forEach(key => {
                if (data[key] !== undefined) {
                    settings[key] = data[key];
                }
            });

            if (data.logo_website_url) {
                settings.logo_website_url = data.logo_website_url;
            }
        } catch (err) {
            console.error('Error fetching settings:', err);
            showToast('Gagal memuat konfigurasi website', 'error');
        } finally {
            loading = false;
        }
    };

    const handleLogoSelect = (e) => {
        const file = e.target.files[0];
        if (!file) return;
        if (file.size > 2 * 1024 * 1024) {
            showToast('Ukuran logo maksimal 2 MB', 'error');
            return;
        }
        logoFile = file;
        logoPreview = URL.createObjectURL(file);
    };

    const saveSettings = async (e) => {
        e.preventDefault();
        saving = true;
        toast.show = false;

        const formData = new FormData();
        
        Object.keys(settings).forEach(key => {
            if (key !== 'logo_website' && key !== 'logo_website_url' && settings[key] !== null && settings[key] !== undefined) {
                formData.append(key, settings[key]);
            }
        });

        if (logoFile) {
            formData.append('logo_website', logoFile);
        }

        try {
            const res = await api.post('/v1/admin/settings', formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });
            const updatedData = res.data || res || {};
            
            if (updatedData.logo_website_url) {
                settings.logo_website_url = updatedData.logo_website_url;
                logoPreview = null;
                logoFile = null;
            }
            
            showToast('Pengaturan website berhasil disimpan!');
        } catch (err) {
            console.error('Save Settings Error:', err);
            showToast('Gagal menyimpan pengaturan.', 'error');
        } finally {
            saving = false;
        }
    };

    onMount(() => {
        fetchSettings();
    });
</script>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-heading text-text-title">Pengaturan Website Dinamis</h1>
            <p class="text-xs sm:text-sm text-text-body mt-0.5">Konfigurasikan identitas pesantren, kontak resmi, media sosial, dan integrasi WhatsApp PPDB.</p>
        </div>
        <button 
            onclick={fetchSettings} 
            title="Refresh Pengaturan"
            class="self-start sm:self-center px-4 py-2 bg-white border border-gray-200 hover:bg-gray-100 rounded-xl text-xs sm:text-sm font-bold text-gray-700 inline-flex items-center gap-2 shadow-sm transition-all"
        >
            <RefreshCw size={16} class={loading ? 'animate-spin text-primary' : ''} /> Refresh Data
        </button>
    </div>

    {#if toast.show}
        <div class="fixed top-24 right-6 z-50 px-4 py-3 bg-white border {toast.type === 'error' ? 'border-red-300 text-red-700' : 'border-green-200 text-green-700'} rounded-xl shadow-xl flex gap-3 animate-fade-left items-center font-medium text-sm">
            <CheckCircle2 size={18} class="shrink-0" /> {toast.message}
        </div>
    {/if}

    <!-- Navigation Tabs -->
    <div class="flex border-b border-border-color gap-2 overflow-x-auto">
        <button 
            class="py-3 px-5 font-bold text-sm border-b-2 transition-all flex items-center gap-2 whitespace-nowrap {activeTab === 'identitas' ? 'border-primary text-primary bg-primary/5 rounded-t-xl' : 'border-transparent text-text-body hover:text-text-title'}"
            onclick={() => activeTab = 'identitas'}
        >
            <Globe size={18} /> Identitas & Logo
        </button>
        <button 
            class="py-3 px-5 font-bold text-sm border-b-2 transition-all flex items-center gap-2 whitespace-nowrap {activeTab === 'kontak' ? 'border-primary text-primary bg-primary/5 rounded-t-xl' : 'border-transparent text-text-body hover:text-text-title'}"
            onclick={() => activeTab = 'kontak'}
        >
            <Phone size={18} /> Kontak & Alamat
        </button>
        <button 
            class="py-3 px-5 font-bold text-sm border-b-2 transition-all flex items-center gap-2 whitespace-nowrap {activeTab === 'medsos' ? 'border-primary text-primary bg-primary/5 rounded-t-xl' : 'border-transparent text-text-body hover:text-text-title'}"
            onclick={() => activeTab = 'medsos'}
        >
            <Share2 size={18} /> Media Sosial
        </button>
        <button 
            class="py-3 px-5 font-bold text-sm border-b-2 transition-all flex items-center gap-2 whitespace-nowrap {activeTab === 'notifikasi' ? 'border-primary text-primary bg-primary/5 rounded-t-xl' : 'border-transparent text-text-body hover:text-text-title'}"
            onclick={() => activeTab = 'notifikasi'}
        >
            <MessageSquare size={18} /> Notifikasi WA PPDB
        </button>
    </div>

    <!-- Form Pengaturan -->
    <form onsubmit={saveSettings} class="space-y-6">
        <!-- TAB 1: IDENTITAS & LOGO -->
        {#if activeTab === 'identitas'}
            <Card class="p-6 space-y-6 animate-fade-in">
                <h3 class="text-base font-extrabold text-primary uppercase tracking-wider border-b pb-3">Identitas Umum Pesantren</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                    <!-- Upload Logo -->
                    <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200 text-center space-y-3">
                        <label class="block text-xs font-bold text-text-title">Logo Resmi Website</label>
                        <div class="w-28 h-28 mx-auto rounded-2xl bg-white border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden shadow-sm relative group">
                            {#if logoPreview || settings.logo_website_url}
                                <img src={logoPreview || settings.logo_website_url} alt="Logo Preview" class="w-full h-full object-contain p-2" />
                            {:else}
                                <div class="text-gray-400 flex flex-col items-center">
                                    <Image size={32} />
                                    <span class="text-[10px] mt-1 font-semibold">Upload Logo</span>
                                </div>
                            {/if}
                        </div>
                        <p class="text-[11px] text-text-body">Format PNG, JPG (Transparent disarankan, maks 2 MB)</p>
                        <label class="inline-block px-4 py-2 bg-white hover:bg-gray-100 border border-gray-300 rounded-xl text-xs font-bold text-gray-700 cursor-pointer shadow-sm transition-all">
                            Pilih File Logo
                            <input type="file" accept="image/png,image/jpeg,image/jpg" onchange={handleLogoSelect} class="hidden" />
                        </label>
                    </div>

                    <!-- Input Identitas -->
                    <div class="md:col-span-2 space-y-4">
                        <div>
                            <label for="nama_pesantren" class="block text-xs font-bold text-text-title mb-1.5">Nama Resmi Pesantren</label>
                            <input 
                                type="text" 
                                id="nama_pesantren" 
                                bind:value={settings.nama_pesantren}
                                required 
                                class="w-full px-4 py-3 rounded-xl border border-border-color focus:outline-none focus:ring-2 focus:ring-primary text-sm font-medium bg-white"
                            />
                        </div>
                        <div>
                            <label for="tagline" class="block text-xs font-bold text-text-title mb-1.5">Slogan / Tagline Website</label>
                            <input 
                                type="text" 
                                id="tagline" 
                                bind:value={settings.tagline_pesantren}
                                class="w-full px-4 py-3 rounded-xl border border-border-color focus:outline-none focus:ring-2 focus:ring-primary text-sm font-medium bg-white"
                            />
                        </div>
                        <div>
                            <label for="deskripsi" class="block text-xs font-bold text-text-title mb-1.5">Deskripsi Singkat (Meta Description & Tentang Kami)</label>
                            <textarea 
                                id="deskripsi" 
                                rows="3" 
                                bind:value={settings.deskripsi_singkat}
                                class="w-full px-4 py-3 rounded-xl border border-border-color focus:outline-none focus:ring-2 focus:ring-primary text-sm font-medium bg-white resize-none"
                            ></textarea>
                        </div>
                    </div>
                </div>
            </Card>
        {/if}

        <!-- TAB 2: KONTAK & ALAMAT -->
        {#if activeTab === 'kontak'}
            <Card class="p-6 space-y-6 animate-fade-in">
                <h3 class="text-base font-extrabold text-primary uppercase tracking-wider border-b pb-3">Informasi Kontak & Alamat Google Maps</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="wa_admin" class="block text-xs font-bold text-text-title mb-1.5">Nomor WhatsApp Utama (Format 628xxx)</label>
                        <input 
                            type="text" 
                            id="wa_admin" 
                            bind:value={settings.whatsapp_admin}
                            placeholder="Contoh: 6285242501959"
                            required 
                            class="w-full px-4 py-3 rounded-xl border border-border-color focus:outline-none focus:ring-2 focus:ring-primary text-sm font-mono font-bold bg-white"
                        />
                        <p class="text-[11px] text-emerald-600 font-semibold mt-1">Nomor ini digunakan untuk tombol "Chat via WhatsApp" di website.</p>
                    </div>
                    <div>
                        <label for="telp_kantor" class="block text-xs font-bold text-text-title mb-1.5">Nomor Telepon Kantor / HP</label>
                        <input 
                            type="text" 
                            id="telp_kantor" 
                            bind:value={settings.telepon_kantor}
                            placeholder="Contoh: 0858-2424-6172"
                            class="w-full px-4 py-3 rounded-xl border border-border-color focus:outline-none focus:ring-2 focus:ring-primary text-sm font-medium bg-white"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="block text-xs font-bold text-text-title mb-1.5">Email Resmi Pesantren</label>
                        <input 
                            type="email" 
                            id="email" 
                            bind:value={settings.email_resmi}
                            placeholder="Contoh: info@ululalbab.sch.id"
                            class="w-full px-4 py-3 rounded-xl border border-border-color focus:outline-none focus:ring-2 focus:ring-primary text-sm font-medium bg-white"
                        />
                    </div>
                    <div>
                        <label for="alamat" class="block text-xs font-bold text-text-title mb-1.5">Alamat Lengkap Pesantren</label>
                        <textarea 
                            id="alamat" 
                            rows="2" 
                            bind:value={settings.alamat_lengkap}
                            placeholder="Tuliskan alamat lengkap kantor & pesantren..."
                            class="w-full px-4 py-3 rounded-xl border border-border-color focus:outline-none focus:ring-2 focus:ring-primary text-sm font-medium bg-white resize-none"
                        ></textarea>
                    </div>
                </div>

                <div>
                    <label for="maps_url" class="block text-xs font-bold text-text-title mb-1.5">URL Embed Google Maps (src iframe atau link embed)</label>
                    <textarea 
                        id="maps_url" 
                        rows="3" 
                        bind:value={settings.map_embed_url}
                        placeholder="Tempelkan link embed dari Google Maps..."
                        class="w-full px-4 py-3 rounded-xl border border-border-color focus:outline-none focus:ring-2 focus:ring-primary text-xs font-mono bg-gray-50 resize-none"
                    ></textarea>
                    <p class="text-[11px] text-text-body mt-1">Pastikan link ini valid agar Google Maps dapat tampil dengan sempurna di halaman Kontak.</p>
                </div>

                {#if settings.map_embed_url}
                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-200">
                        <p class="text-xs font-bold text-text-title mb-2">Preview Peta Google Maps Saat Ini:</p>
                        <div class="w-full h-64 rounded-xl overflow-hidden border shadow-inner">
                            <iframe 
                                src={settings.map_embed_url} 
                                width="100%" 
                                height="100%" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade"
                                title="Preview Maps"
                            ></iframe>
                        </div>
                    </div>
                {/if}
            </Card>
        {/if}

        <!-- TAB 3: MEDIA SOSIAL -->
        {#if activeTab === 'medsos'}
            <Card class="p-6 space-y-6 animate-fade-in">
                <h3 class="text-base font-extrabold text-primary uppercase tracking-wider border-b pb-3">Tautan Akun Media Sosial Resmi</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="fb_url" class="block text-xs font-bold text-text-title mb-1.5">URL Halaman Facebook (Tampil di Footer & Menu)</label>
                        <input 
                            type="url" 
                            id="fb_url" 
                            bind:value={settings.facebook_url}
                            placeholder="https://www.facebook.com/..."
                            class="w-full px-4 py-3 rounded-xl border border-border-color focus:outline-none focus:ring-2 focus:ring-primary text-sm font-medium bg-white"
                        />
                    </div>
                    <div>
                        <label for="yt_url" class="block text-xs font-bold text-text-title mb-1.5">URL Channel YouTube Resmi</label>
                        <input 
                            type="url" 
                            id="yt_url" 
                            bind:value={settings.youtube_url}
                            placeholder="https://www.youtube.com/channel/..."
                            class="w-full px-4 py-3 rounded-xl border border-border-color focus:outline-none focus:ring-2 focus:ring-primary text-sm font-medium bg-white"
                        />
                    </div>
                    <div>
                        <label for="ig_url" class="block text-xs font-bold text-text-title mb-1.5">URL Akun Instagram Resmi</label>
                        <input 
                            type="url" 
                            id="ig_url" 
                            bind:value={settings.instagram_url}
                            placeholder="https://www.instagram.com/..."
                            class="w-full px-4 py-3 rounded-xl border border-border-color focus:outline-none focus:ring-2 focus:ring-primary text-sm font-medium bg-white"
                        />
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-blue-50 border border-blue-200 text-xs text-blue-900 leading-relaxed">
                    <strong>Catatan:</strong> Link media sosial yang diisi di atas akan otomatis terhubung pada tombol-tombol ikon di Footer dan halaman Hubungi Kami.
                </div>
            </Card>
        {/if}

        <!-- TAB 4: NOTIFIKASI & WA PPDB -->
        {#if activeTab === 'notifikasi'}
            <Card class="p-6 space-y-6 animate-fade-in">
                <h3 class="text-base font-extrabold text-primary uppercase tracking-wider border-b pb-3">Pengaturan Notifikasi Otomatis PPDB</h3>
                
                <div class="space-y-6">
                    <div>
                        <label for="wa_notif" class="block text-xs font-bold text-text-title mb-1.5">Nomor WhatsApp Admin PPDB (Penerima Notifikasi Registrasi Baru)</label>
                        <input 
                            type="text" 
                            id="wa_notif" 
                            bind:value={settings.wa_notification_admin}
                            placeholder="6285242501959"
                            required 
                            class="w-full px-4 py-3 rounded-xl border border-border-color focus:outline-none focus:ring-2 focus:ring-primary text-sm font-mono font-bold bg-white max-w-md"
                        />
                        <p class="text-[11px] text-text-body mt-1">Nomor ini (6285242501959) akan menerima pesan WhatsApp otomatis setiap kali ada calon santri yang mendaftar dan mengupload dokumen secara online.</p>
                    </div>

                    <div>
                        <label for="wa_template" class="block text-xs font-bold text-text-title mb-1.5">Template Pesan WhatsApp Pendaftaran (Tanya Admin via WA)</label>
                        <textarea 
                            id="wa_template" 
                            rows="4" 
                            bind:value={settings.wa_greeting_template}
                            class="w-full px-4 py-3 rounded-xl border border-border-color focus:outline-none focus:ring-2 focus:ring-primary text-sm font-medium bg-white resize-none"
                        ></textarea>
                        <p class="text-[11px] text-text-body mt-1">Pesan pembuka standar saat calon santri mengklik tombol "Daftar via WhatsApp" atau kontak langsung.</p>
                    </div>
                </div>
            </Card>
        {/if}

        <!-- Tombol Simpan -->
        <div class="flex justify-end pt-4 border-t border-border-color">
            <Button type="submit" size="lg" disabled={saving} class="w-full sm:w-auto px-8 justify-center gap-2 font-bold shadow-lg hover:shadow-xl rounded-xl">
                {#if saving}
                    <Loader2 size={18} class="animate-spin" /> Menyimpan Pengaturan...
                {:else}
                    <Save size={18} /> Simpan Seluruh Pengaturan
                {/if}
            </Button>
        </div>
    </form>
</div>
