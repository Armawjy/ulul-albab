<script>
    import { FileText, CreditCard, CheckSquare, ClipboardList, Calendar, MapPin, Download, X } from 'lucide-svelte';
    import Button from '../components/Button.svelte';
    import Card from '../components/Card.svelte';
    
    const steps = [
        { title: 'Pendaftaran Online', desc: 'Mengisi formulir pendaftaran melalui website PPDB dan mengunggah berkas persyaratan.', icon: FileText },
        { title: 'Pembayaran Biaya', desc: 'Melakukan pembayaran biaya pendaftaran melalui Virtual Account Bank yang disediakan.', icon: CreditCard },
        { title: 'Tes Seleksi', desc: 'Mengikuti tes akademik, tes membaca Al-Qur\'an, dan wawancara sesuai jadwal.', icon: CheckSquare },
        { title: 'Pengumuman', desc: 'Melihat hasil seleksi melalui website resmi dan melakukan daftar ulang bagi yang lulus.', icon: ClipboardList },
    ];

    // State Modal & Formulir PPDB
    let showFormModal = $state(false);
    let formPPDB = $state({
        nama: '',
        jk: 'Putra (Santriwan)',
        ttl: '',
        asalSekolah: '',
        namaOrtu: '',
        noWa: '',
        jenjang: 'SMA (Pondok Pesantren)',
        catatan: ''
    });

    function kirimWA() {
        if (!formPPDB.nama || !formPPDB.ttl || !formPPDB.asalSekolah || !formPPDB.namaOrtu || !formPPDB.noWa) {
            alert('Mohon lengkapi data wajib (Nama, Tempat/Tgl Lahir, Asal Sekolah, Nama Orang Tua, dan No. WhatsApp) terlebih dahulu.');
            return;
        }

        const pesan = `*FORMULIR PENDAFTARAN SANTRI BARU (PPDB)*
*Pondok Pesantren Ulul Albab Makassar*

Assalamu'alaikum Warahmatullahi Wabarakatuh.
Saya ingin mendaftarkan calon santri baru dengan data sebagai berikut:

👤 *Nama Calon Santri:* ${formPPDB.nama}
🚻 *Jenis Kelamin:* ${formPPDB.jk}
📅 *Tempat, Tgl Lahir:* ${formPPDB.ttl}
🏫 *Asal Sekolah:* ${formPPDB.asalSekolah}
👨‍👩‍👦 *Nama Orang Tua/Wali:* ${formPPDB.namaOrtu}
📱 *No. WhatsApp/HP:* ${formPPDB.noWa}
🎓 *Pilihan Jenjang:* ${formPPDB.jenjang}

📝 *Catatan / Pertanyaan:*
${formPPDB.catatan || '-'}

💡 *Informasi Pengiriman Berkas:*
Saya akan melampirkan berkas persyaratan (Foto Ijazah/SKL, KK, Akta Lahir, Pasfoto, dll) secara langsung melalui chat WhatsApp ini.

Terima kasih. Wassalamu'alaikum.`;

        const urlWA = `https://wa.me/6285824246172?text=${encodeURIComponent(pesan)}`;
        window.open(urlWA, '_blank');
        showFormModal = false;
    }
</script>

<svelte:head>
    <title>PPDB - Pondok Pesantren Ulul Albab Makassar</title>
</svelte:head>

<div class="pt-24 pb-16 bg-primary-dark relative overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img 
            src="https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
            alt="PPDB Background" 
            class="w-full h-full object-cover opacity-20"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-primary-dark to-transparent"></div>
    </div>
    
    <div class="container mx-auto px-6 relative z-10 text-center">
        <div class="max-w-3xl mx-auto animate-fade-up">
            <div class="inline-block px-4 py-1.5 rounded-full bg-white/20 backdrop-blur-sm text-white text-sm font-semibold mb-6">
                TAHUN AJARAN 2026/2027
            </div>
            <h1 class="text-4xl md:text-5xl font-heading font-bold text-white mb-6">Penerimaan Peserta Didik Baru</h1>
            <p class="text-lg text-gray-200 mb-8">
                Pendaftaran santri baru tingkat SMA/MA telah dibuka. Segera daftarkan putra/putri Anda!
            </p>
            <Button size="lg" class="bg-[#F8B803] hover:bg-yellow-500 text-text-title font-bold shadow-lg hover:shadow-xl transition-all scale-105" onclick={() => showFormModal = true}>Daftar Sekarang</Button>
        </div>
    </div>
</div>

<section class="py-20 bg-bg-base relative -mt-10 rounded-t-[40px] z-20">
    <div class="container mx-auto px-6">
        
        <div class="text-center max-w-2xl mx-auto mb-16">
            <h2 class="text-3xl font-heading font-bold text-text-title mb-4">Alur Pendaftaran</h2>
            <p class="text-text-body">Ikuti langkah-langkah berikut untuk mendaftar sebagai calon santri baru.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-20 relative">
            <!-- Connecting Line for Desktop -->
            <div class="hidden lg:block absolute top-12 left-[12.5%] right-[12.5%] h-0.5 bg-border-color z-0"></div>
            
            {#each steps as step, i}
                <div class="relative z-10 text-center group" data-aos="fade-up" data-aos-delay={i * 150} data-aos-duration="600">
                    <div class="w-24 h-24 mx-auto bg-white rounded-full border-4 border-bg-section shadow-lg flex items-center justify-center mb-6 group-hover:border-primary group-hover:shadow-2xl group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 ease-in-out">
                        <step.icon size={32} class="text-primary group-hover:scale-110 transition-transform duration-300" />
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-[#F8B803] text-white rounded-full flex items-center justify-center font-bold shadow-md group-hover:scale-110 transition-transform">
                            {i + 1}
                        </div>
                    </div>
                    <h3 class="font-heading font-bold text-lg text-text-title mb-3 group-hover:text-primary transition-colors">{step.title}</h3>
                    <p class="text-sm text-text-body leading-relaxed">{step.desc}</p>
                </div>
            {/each}
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Jadwal -->
            <div data-aos="fade-right" data-aos-duration="700">
                <Card class="border border-border-color/50 h-full">
                    <h3 class="font-heading font-bold text-2xl text-text-title mb-6 flex items-center gap-3">
                        <Calendar size={28} class="text-primary" />
                        Jadwal Pelaksanaan
                    </h3>
                
                <div class="space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-bg-section rounded-xl border-l-4 border-primary">
                        <div>
                            <h4 class="font-semibold text-text-title">Pendaftaran Gelombang 1</h4>
                            <p class="text-sm text-text-body">Online via Website</p>
                        </div>
                        <div class="mt-2 sm:mt-0 text-right">
                            <span class="inline-block px-3 py-1 bg-white text-primary text-sm font-semibold rounded-full border border-primary/20">
                                01 Jan - 30 Mar 2026
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-bg-section rounded-xl border-l-4 border-[#F8B803]">
                        <div>
                            <h4 class="font-semibold text-text-title">Tes Seleksi Akademik</h4>
                            <p class="text-sm text-text-body">Offline di Pesantren</p>
                        </div>
                        <div class="mt-2 sm:mt-0 text-right">
                            <span class="inline-block px-3 py-1 bg-white text-[#F8B803] text-sm font-semibold rounded-full border border-[#F8B803]/20">
                                05 April 2026
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-bg-section rounded-xl border-l-4 border-[#F8B803]">
                        <div>
                            <h4 class="font-semibold text-text-title">Tes Baca Al-Qur'an & Wawancara</h4>
                            <p class="text-sm text-text-body">Offline di Pesantren</p>
                        </div>
                        <div class="mt-2 sm:mt-0 text-right">
                            <span class="inline-block px-3 py-1 bg-white text-[#F8B803] text-sm font-semibold rounded-full border border-[#F8B803]/20">
                                06 - 07 April 2026
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-bg-section rounded-xl border-l-4 border-green-500">
                        <div>
                            <h4 class="font-semibold text-text-title">Pengumuman Kelulusan</h4>
                            <p class="text-sm text-text-body">Online via Website</p>
                        </div>
                        <div class="mt-2 sm:mt-0 text-right">
                            <span class="inline-block px-3 py-1 bg-white text-green-600 text-sm font-semibold rounded-full border border-green-500/20">
                                15 April 2026
                            </span>
                        </div>
                    </div>
                    </div>
                </Card>
            </div>

            <!-- Persyaratan -->
            <div data-aos="fade-left" data-aos-duration="700">
                <Card class="border border-border-color/50 bg-primary-dark text-white h-full">
                    <h3 class="font-heading font-bold text-2xl mb-6 flex items-center gap-3">
                        <FileText size={28} class="text-[#F8B803]" />
                        Syarat Pendaftaran
                    </h3>
                
                <ul class="space-y-4 mb-8">
                    <li class="flex items-start gap-3">
                        <CheckSquare size={20} class="text-[#F8B803] shrink-0 mt-0.5" />
                        <span class="text-gray-200">Mengisi formulir pendaftaran online.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <CheckSquare size={20} class="text-[#F8B803] shrink-0 mt-0.5" />
                        <span class="text-gray-200">Scan Asli Surat Keterangan Lulus (SKL) / Ijazah SMP/MTs sederajat.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <CheckSquare size={20} class="text-[#F8B803] shrink-0 mt-0.5" />
                        <span class="text-gray-200">Scan Asli Kartu Keluarga (KK) dan Akte Kelahiran.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <CheckSquare size={20} class="text-[#F8B803] shrink-0 mt-0.5" />
                        <span class="text-gray-200">Scan Asli Rapor SMP/MTs semester 1-5.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <CheckSquare size={20} class="text-[#F8B803] shrink-0 mt-0.5" />
                        <span class="text-gray-200">Pas Foto berwarna terbaru 3x4 (Latar Merah).</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <CheckSquare size={20} class="text-[#F8B803] shrink-0 mt-0.5" />
                        <span class="text-gray-200">Membayar biaya pendaftaran sebesar Rp 300.000,-</span>
                    </li>
                </ul>
                
                <Button variant="secondary" class="w-full gap-2 justify-center bg-[#F8B803] hover:bg-yellow-500 text-text-title font-bold shadow-lg hover:shadow-xl mb-3" onclick={() => showFormModal = true}>
                    <FileText size={18} />
                    Isi Formulir Pendaftaran Sekarang
                </Button>
                <Button variant="white" class="w-full gap-2 justify-center text-primary-dark font-semibold shadow-lg hover:shadow-xl">
                    <Download size={18} />
                    Unduh Brosur Lengkap
                </Button>
            </Card>
            </div>
        </div>

        <!-- Formulir Pendaftaran Online via WhatsApp -->
        <div id="form-daftar" class="mt-20 scroll-mt-28" data-aos="fade-up" data-aos-duration="800">
            <Card class="border-2 border-primary/30 p-6 sm:p-10 md:p-12 shadow-2xl bg-gradient-to-br from-white via-bg-section/50 to-white relative overflow-hidden">
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-primary/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-[#F8B803]/10 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="max-w-3xl mx-auto relative z-10">
                    <div class="text-center mb-10">
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-primary/10 text-primary text-sm font-semibold mb-4 shadow-sm border border-primary/20">
                            <FileText size={16} /> Formulir PPDB Online via WhatsApp
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-heading font-bold text-text-title mb-4">Daftar Santri Baru Sekarang</h2>
                        <p class="text-text-body text-sm sm:text-base leading-relaxed">
                            Isi formulir pendaftaran di bawah ini dengan lengkap dan benar. Setelah klik tombol kirim, sistem akan otomatis menyusun pesan pendaftaran resmi dan mengarahkan Anda ke WhatsApp Admin PPDB kami.
                        </p>
                    </div>

                    <form class="space-y-6" onsubmit={(e) => { e.preventDefault(); kirimWA(); }}>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="nama" class="block text-sm font-semibold text-text-title mb-2">Nama Lengkap Calon Santri <span class="text-red-500">*</span></label>
                                <input 
                                    type="text" 
                                    id="nama" 
                                    bind:value={formPPDB.nama}
                                    required
                                    placeholder="Contoh: Muhammad Ahmad Zaki" 
                                    class="w-full px-4 py-3.5 rounded-xl border border-border-color focus:outline-none focus:ring-4 focus:ring-primary/20 focus:border-primary transition-all text-sm bg-white shadow-sm"
                                />
                            </div>
                            <div>
                                <label for="jk" class="block text-sm font-semibold text-text-title mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                                <select 
                                    id="jk" 
                                    bind:value={formPPDB.jk}
                                    class="w-full px-4 py-3.5 rounded-xl border border-border-color focus:outline-none focus:ring-4 focus:ring-primary/20 focus:border-primary transition-all text-sm bg-white shadow-sm font-medium"
                                >
                                    <option value="Putra (Santriwan)">Putra (Santriwan)</option>
                                    <option value="Putri (Santriwati)">Putri (Santriwati)</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="ttl" class="block text-sm font-semibold text-text-title mb-2">Tempat, Tanggal Lahir <span class="text-red-500">*</span></label>
                                <input 
                                    type="text" 
                                    id="ttl" 
                                    bind:value={formPPDB.ttl}
                                    required
                                    placeholder="Contoh: Makassar, 15 Mei 2011" 
                                    class="w-full px-4 py-3.5 rounded-xl border border-border-color focus:outline-none focus:ring-4 focus:ring-primary/20 focus:border-primary transition-all text-sm bg-white shadow-sm"
                                />
                            </div>
                            <div>
                                <label for="asal" class="block text-sm font-semibold text-text-title mb-2">Asal Sekolah (SMP/MTs/Sederajat) <span class="text-red-500">*</span></label>
                                <input 
                                    type="text" 
                                    id="asal" 
                                    bind:value={formPPDB.asalSekolah}
                                    required
                                    placeholder="Contoh: MTs Negeri 1 Makassar" 
                                    class="w-full px-4 py-3.5 rounded-xl border border-border-color focus:outline-none focus:ring-4 focus:ring-primary/20 focus:border-primary transition-all text-sm bg-white shadow-sm"
                                />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="ortu" class="block text-sm font-semibold text-text-title mb-2">Nama Orang Tua / Wali <span class="text-red-500">*</span></label>
                                <input 
                                    type="text" 
                                    id="ortu" 
                                    bind:value={formPPDB.namaOrtu}
                                    required
                                    placeholder="Contoh: H. Abdullah" 
                                    class="w-full px-4 py-3.5 rounded-xl border border-border-color focus:outline-none focus:ring-4 focus:ring-primary/20 focus:border-primary transition-all text-sm bg-white shadow-sm"
                                />
                            </div>
                            <div>
                                <label for="nowa" class="block text-sm font-semibold text-text-title mb-2">No. WhatsApp Orang Tua / Wali <span class="text-red-500">*</span></label>
                                <input 
                                    type="tel" 
                                    id="nowa" 
                                    bind:value={formPPDB.noWa}
                                    required
                                    placeholder="Contoh: 081234567890" 
                                    class="w-full px-4 py-3.5 rounded-xl border border-border-color focus:outline-none focus:ring-4 focus:ring-primary/20 focus:border-primary transition-all text-sm bg-white shadow-sm"
                                />
                            </div>
                        </div>

                        <div>
                            <label for="jenjang" class="block text-sm font-semibold text-text-title mb-2">Pilihan Jenjang Pendidikan <span class="text-red-500">*</span></label>
                            <select 
                                id="jenjang" 
                                bind:value={formPPDB.jenjang}
                                class="w-full px-4 py-3.5 rounded-xl border border-border-color focus:outline-none focus:ring-4 focus:ring-primary/20 focus:border-primary transition-all text-sm bg-white shadow-sm font-medium"
                            >
                                <option value="SMA (Pondok Pesantren)">SMA (Sekolah Menengah Atas) - Pondok Pesantren</option>
                                <option value="MA (Pondok Pesantren)">MA (Madrasah Aliyah) - Pondok Pesantren</option>
                            </select>
                        </div>

                        <div>
                            <label for="catatan" class="block text-sm font-semibold text-text-title mb-2">Catatan Tambahan / Pertanyaan (Opsional)</label>
                            <textarea 
                                id="catatan" 
                                rows="3"
                                bind:value={formPPDB.catatan}
                                placeholder="Tuliskan jika ada pertanyaan, informasi medis, atau catatan khusus terkait pendaftaran..."
                                class="w-full px-4 py-3.5 rounded-xl border border-border-color focus:outline-none focus:ring-4 focus:ring-primary/20 focus:border-primary transition-all text-sm bg-white shadow-sm resize-none"
                            ></textarea>
                        </div>

                        <!-- Info Pengiriman Berkas via WA -->
                        <div class="p-5 rounded-2xl bg-amber-50 border border-amber-200/80 shadow-sm flex items-start gap-4 transition-all hover:bg-amber-100/50">
                            <div class="w-8 h-8 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 mt-0.5 text-sm font-bold shadow-sm">i</div>
                            <div class="text-xs sm:text-sm text-amber-950 leading-relaxed">
                                <span class="font-bold text-amber-900 block mb-1 text-sm">Informasi Pengiriman Berkas & Dokumen:</span>
                                Setelah Anda mengklik tombol di bawah, aplikasi WhatsApp akan otomatis terbuka dan berisi format data pendaftaran di atas. Selanjutnya, Anda dapat langsung <strong>melampirkan file berkas persyaratan</strong> (seperti Foto Kartu Keluarga, Akta Kelahiran, Rapor/Ijazah, dan Pasfoto) di ruang obrolan WhatsApp kepada Admin PPDB kami.
                            </div>
                        </div>

                        <Button 
                            type="submit" 
                            size="lg"
                            class="w-full justify-center gap-3 bg-[#25D366] hover:bg-[#20bd5a] text-white font-bold py-4 shadow-lg hover:shadow-xl transition-all text-base rounded-xl"
                        >
                            <svg viewBox="0 0 24 24" width="24" height="24" class="fill-white shrink-0"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                            Kirim Formulir Pendaftaran via WhatsApp (0858-2424-6172)
                        </Button>
                    </form>
                </div>
            </Card>
        </div>

    </div>
</section>

{#if showFormModal}
    <!-- Modal Overlay Backdrop -->
    <div 
        class="fixed inset-0 z-50 bg-black/70 backdrop-blur-md flex items-center justify-center p-4 sm:p-6 overflow-y-auto animate-fade-in"
        onclick={(e) => { if (e.target === e.currentTarget) showFormModal = false; }}
    >
        <!-- Modal Content Card -->
        <div class="bg-white rounded-[28px] max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-white/40 p-6 sm:p-8 relative animate-scale-up">
            <!-- Tombol Close (X) -->
            <button 
                class="absolute top-6 right-6 w-10 h-10 rounded-full bg-gray-100 hover:bg-red-500 hover:text-white text-gray-500 flex items-center justify-center transition-all shadow-sm z-20"
                onclick={() => showFormModal = false}
                aria-label="Tutup Formulir"
            >
                <X size={20} />
            </button>

            <!-- Modal Header -->
            <div class="text-center mb-6 pr-6 pl-6">
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold mb-3">
                    <FileText size={14} /> PPDB ONLINE VIA WHATSAPP
                </div>
                <h3 class="text-2xl sm:text-3xl font-heading font-bold text-text-title">Formulir Pendaftaran Santri Baru</h3>
                <p class="text-xs sm:text-sm text-text-body mt-1.5">
                    Isi data di bawah ini. Klik tombol kirim untuk otomatis terhubung ke WhatsApp Admin PPDB.
                </p>
            </div>

            <!-- Form -->
            <form class="space-y-4 sm:space-y-5" onsubmit={(e) => { e.preventDefault(); kirimWA(); }}>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                    <div>
                        <label for="modal-nama" class="block text-xs font-bold text-text-title mb-1">Nama Lengkap Calon Santri <span class="text-red-500">*</span></label>
                        <input 
                            type="text" 
                            id="modal-nama" 
                            bind:value={formPPDB.nama}
                            required
                            placeholder="Contoh: Muhammad Ahmad Zaki" 
                            class="w-full px-3.5 py-3 rounded-xl border border-border-color focus:outline-none focus:ring-4 focus:ring-primary/20 focus:border-primary transition-all text-xs sm:text-sm bg-gray-50/50"
                        />
                    </div>
                    <div>
                        <label for="modal-jk" class="block text-xs font-bold text-text-title mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select 
                            id="modal-jk" 
                            bind:value={formPPDB.jk}
                            class="w-full px-3.5 py-3 rounded-xl border border-border-color focus:outline-none focus:ring-4 focus:ring-primary/20 focus:border-primary transition-all text-xs sm:text-sm bg-gray-50/50 font-medium"
                        >
                            <option value="Putra (Santriwan)">Putra (Santriwan)</option>
                            <option value="Putri (Santriwati)">Putri (Santriwati)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                    <div>
                        <label for="modal-ttl" class="block text-xs font-bold text-text-title mb-1">Tempat, Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input 
                            type="text" 
                            id="modal-ttl" 
                            bind:value={formPPDB.ttl}
                            required
                            placeholder="Contoh: Makassar, 15 Mei 2011" 
                            class="w-full px-3.5 py-3 rounded-xl border border-border-color focus:outline-none focus:ring-4 focus:ring-primary/20 focus:border-primary transition-all text-xs sm:text-sm bg-gray-50/50"
                        />
                    </div>
                    <div>
                        <label for="modal-asal" class="block text-xs font-bold text-text-title mb-1">Asal Sekolah (SMP/MTs) <span class="text-red-500">*</span></label>
                        <input 
                            type="text" 
                            id="modal-asal" 
                            bind:value={formPPDB.asalSekolah}
                            required
                            placeholder="Contoh: MTs Negeri 1 Makassar" 
                            class="w-full px-3.5 py-3 rounded-xl border border-border-color focus:outline-none focus:ring-4 focus:ring-primary/20 focus:border-primary transition-all text-xs sm:text-sm bg-gray-50/50"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                    <div>
                        <label for="modal-ortu" class="block text-xs font-bold text-text-title mb-1">Nama Orang Tua / Wali <span class="text-red-500">*</span></label>
                        <input 
                            type="text" 
                            id="modal-ortu" 
                            bind:value={formPPDB.namaOrtu}
                            required
                            placeholder="Contoh: H. Abdullah" 
                            class="w-full px-3.5 py-3 rounded-xl border border-border-color focus:outline-none focus:ring-4 focus:ring-primary/20 focus:border-primary transition-all text-xs sm:text-sm bg-gray-50/50"
                        />
                    </div>
                    <div>
                        <label for="modal-nowa" class="block text-xs font-bold text-text-title mb-1">No. WhatsApp Ortu / Wali <span class="text-red-500">*</span></label>
                        <input 
                            type="tel" 
                            id="modal-nowa" 
                            bind:value={formPPDB.noWa}
                            required
                            placeholder="Contoh: 081234567890" 
                            class="w-full px-3.5 py-3 rounded-xl border border-border-color focus:outline-none focus:ring-4 focus:ring-primary/20 focus:border-primary transition-all text-xs sm:text-sm bg-gray-50/50"
                        />
                    </div>
                </div>

                <div>
                    <label for="modal-jenjang" class="block text-xs font-bold text-text-title mb-1">Pilihan Jenjang Pendidikan <span class="text-red-500">*</span></label>
                    <select 
                        id="modal-jenjang" 
                        bind:value={formPPDB.jenjang}
                        class="w-full px-3.5 py-3 rounded-xl border border-border-color focus:outline-none focus:ring-4 focus:ring-primary/20 focus:border-primary transition-all text-xs sm:text-sm bg-gray-50/50 font-medium"
                    >
                        <option value="SMA (Pondok Pesantren)">SMA (Sekolah Menengah Atas) - Pondok Pesantren</option>
                        <option value="MA (Pondok Pesantren)">MA (Madrasah Aliyah) - Pondok Pesantren</option>
                    </select>
                </div>

                <div>
                    <label for="modal-catatan" class="block text-xs font-bold text-text-title mb-1">Catatan / Pertanyaan (Opsional)</label>
                    <textarea 
                        id="modal-catatan" 
                        rows="2"
                        bind:value={formPPDB.catatan}
                        placeholder="Tuliskan catatan khusus atau pertanyaan..."
                        class="w-full px-3.5 py-3 rounded-xl border border-border-color focus:outline-none focus:ring-4 focus:ring-primary/20 focus:border-primary transition-all text-xs sm:text-sm bg-gray-50/50 resize-none"
                    ></textarea>
                </div>

                <!-- Info Pengiriman Berkas via WA -->
                <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200 flex items-start gap-3">
                    <div class="w-6 h-6 rounded-lg bg-amber-500 text-white flex items-center justify-center shrink-0 text-xs font-bold mt-0.5">i</div>
                    <div class="text-xs text-amber-950 leading-relaxed">
                        <strong>Info Pengiriman Berkas:</strong> Setelah klik tombol di bawah, aplikasi WhatsApp akan terbuka. Anda dapat langsung <strong>melampirkan foto/dokumen persyaratan</strong> (Ijazah/SKL, KK, Akta Lahir, dan Pasfoto) ke chat Admin PPDB kami.
                    </div>
                </div>

                <div class="pt-2 flex gap-3">
                    <button 
                        type="button" 
                        class="px-5 py-3.5 rounded-xl border border-gray-300 hover:bg-gray-100 text-gray-700 font-bold text-xs sm:text-sm transition-colors"
                        onclick={() => showFormModal = false}
                    >
                        Batal
                    </button>
                    <Button 
                        type="submit" 
                        size="lg"
                        class="flex-1 justify-center gap-2 bg-[#25D366] hover:bg-[#20bd5a] text-white font-bold py-3.5 shadow-lg hover:shadow-xl transition-all text-xs sm:text-sm rounded-xl"
                    >
                        <svg viewBox="0 0 24 24" width="18" height="18" class="fill-white shrink-0"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        Kirim via WhatsApp (0858-2424-6172)
                    </Button>
                </div>
            </form>
        </div>
    </div>
{/if}
