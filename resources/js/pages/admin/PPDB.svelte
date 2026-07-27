<script>
    import { onMount } from 'svelte';
    import { Plus, Edit2, Trash2, CheckCircle2, Search, Filter, Download, Printer, Eye, CheckCircle, XCircle, Clock, FileCheck, User, ExternalLink, RefreshCw, FileText } from 'lucide-svelte';
    import Card from '../../components/Card.svelte';
    import Button from '../../components/Button.svelte';
    import Modal from '../../components/Modal.svelte';
    import api from '../../services/axios';

    // Tabs state: 'pendaftar' (default) or 'gelombang'
    let activeTab = $state('pendaftar');

    // === STATE DATA PENDAFTAR (TAB 1) ===
    let registrations = $state([]);
    let regLoading = $state(true);
    let regMeta = $state(null);
    let regPage = $state(1);
    let searchQuery = $state('');
    let statusFilter = $state('Semua');
    let sortBy = $state('created_at');
    let sortOrder = $state('desc');

    // Detail modal state
    let showDetailModal = $state(false);
    let selectedReg = $state(null);
    let statusUpdating = $state(false);

    // Preview File modal state
    let showFilePreviewModal = $state(false);
    let previewFileTitle = $state('');
    let previewFileUrl = $state('');
    let isPdfPreview = $state(false);

    // === STATE GELOMBANG / BATCH (TAB 2) ===
    let ppdbs = $state([]);
    let loading = $state(true);
    let pagination = $state(null);
    let currentPage = $state(1);

    let showModal = $state(false);
    let showDeleteModal = $state(false);
    let isEditing = $state(false);
    let submitting = $state(false);

    let formId = $state(null);
    let formBatch = $state('');
    let formStartDate = $state('');
    let formEndDate = $state('');
    let formFee = $state(0);
    let formStatus = $state(false);
    let formReqs = $state('');

    let deleteId = $state(null);
    let deleteType = $state('gelombang'); // 'gelombang' or 'registration'
    let toast = $state({ show: false, message: '', type: 'success' });

    const showToast = (message, type = 'success') => { 
        toast = { show: true, message, type }; 
        setTimeout(() => toast.show = false, 3500); 
    };

    // --- METHODS DATA PENDAFTAR ---
    const fetchRegistrations = async (page = 1) => {
        regLoading = true;
        try {
            const params = new URLSearchParams({
                page: page,
                per_page: 10,
                sort_by: sortBy,
                sort_order: sortOrder
            });
            if (searchQuery) params.append('search', searchQuery);
            if (statusFilter !== 'Semua') params.append('status', statusFilter);

            const response = await api.get(`/v1/admin/ppdb-registrations?${params.toString()}`);
            if (response.data) {
                registrations = response.data.data || [];
                regMeta = response.data.meta || null;
            } else {
                registrations = [];
            }
            regPage = page;
        } catch (err) {
            console.error('Error fetching registrations:', err);
            showToast('Gagal memuat data pendaftar', 'error');
        } finally { 
            regLoading = false; 
        }
    };

    const handleSearch = (e) => {
        e.preventDefault();
        fetchRegistrations(1);
    };

    const handleFilterChange = (status) => {
        statusFilter = status;
        fetchRegistrations(1);
    };

    const openDetail = (item) => {
        selectedReg = item;
        showDetailModal = true;
    };

    const updateStatus = async (id, newStatus) => {
        statusUpdating = true;
        try {
            const res = await api.patch(`/v1/admin/ppdb-registrations/${id}/status`, { status: newStatus });
            showToast(`Status berhasil diubah menjadi: ${newStatus}`);
            if (selectedReg && selectedReg.id === id) {
                selectedReg = res.data || selectedReg;
                selectedReg.status = newStatus;
            }
            fetchRegistrations(regPage);
        } catch (err) {
            showToast('Gagal mengubah status', 'error');
        } finally {
            statusUpdating = false;
        }
    };

    const confirmDeleteReg = (id) => {
        deleteId = id;
        deleteType = 'registration';
        showDeleteModal = true;
    };

    const handleDeleteReg = async () => {
        submitting = true;
        try {
            await api.delete(`/v1/admin/ppdb-registrations/${deleteId}`);
            showToast('Data pendaftar berhasil dihapus');
            showDeleteModal = false;
            if (showDetailModal && selectedReg?.id === deleteId) showDetailModal = false;
            fetchRegistrations(regPage);
        } finally { submitting = false; }
    };

    const openPreviewFile = (title, url) => {
        if (!url) return;
        previewFileTitle = title;
        previewFileUrl = url;
        isPdfPreview = url.toLowerCase().endsWith('.pdf');
        showFilePreviewModal = true;
    };

    // Export Excel / CSV Helper
    const exportExcel = async () => {
        try {
            showToast('Menyiapkan file ekspor...', 'success');
            const res = await api.get('/v1/admin/ppdb-registrations/export');
            const items = res.data || res || [];
            
            if (items.length === 0) {
                showToast('Tidak ada data untuk diekspor', 'error');
                return;
            }

            // Convert to CSV
            const headers = ['Nomor Pendaftaran', 'Nama Lengkap', 'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir', 'Asal Sekolah', 'Nama Orang Tua', 'Nomor WhatsApp', 'Status', 'Tanggal Daftar'];
            const rows = items.map(i => [
                `"${i.registration_number || ''}"`,
                `"${i.nama_lengkap || ''}"`,
                `"${i.jenis_kelamin || ''}"`,
                `"${i.tempat_lahir || ''}"`,
                `"${i.tanggal_lahir || ''}"`,
                `"${i.asal_sekolah || ''}"`,
                `"${i.nama_orang_tua || ''}"`,
                `"${i.nomor_whatsapp_ortu || ''}"`,
                `"${i.status || ''}"`,
                `"${i.created_at ? new Date(i.created_at).toLocaleDateString('id-ID') : ''}"`
            ]);

            const csvContent = "data:text/csv;charset=utf-8," + [headers.join(','), ...rows.map(r => r.join(','))].join('\n');
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement('a');
            link.setAttribute('href', encodedUri);
            link.setAttribute('download', `Data_Pendaftar_PPDB_${new Date().toISOString().slice(0,10)}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            showToast('File berhasil diunduh');
        } catch (err) {
            showToast('Gagal mengekspor data', 'error');
        }
    };

    const printTable = async () => {
        const printWin = window.open('', '_blank', 'width=1200,height=800');
        if (printWin) {
            printWin.document.write('<html><head><title>Memuat Laporan...</title></head><body style="font-family: Arial, sans-serif; text-align: center; padding: 50px;"><h3>Menyiapkan Laporan Resmi PPDB...</h3><p>Mohon tunggu sebentar, sedang mengambil data rekapitulasi dari server...</p></body></html>');
        }
        
        try {
            showToast('Menyiapkan Laporan PDF...', 'success');
            const params = new URLSearchParams();
            if (searchQuery) params.append('search', searchQuery);
            if (statusFilter !== 'Semua') params.append('status', statusFilter);

            const res = await api.get(`/v1/admin/ppdb-registrations/export?${params.toString()}`);
            const items = res.data || res || [];

            const logoUrl = window.location.origin + '/logo.jpeg';
            const todayStr = new Date().toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });

            let rowsHtml = '';
            items.forEach((item, idx) => {
                let count = 0;
                if (item.file_kk_url) count++;
                if (item.file_akta_url) count++;
                if (item.file_ijazah_url) count++;
                if (item.file_rapor_url) count++;
                if (item.file_pas_foto_url) count++;
                if (item.file_ktp_ortu_url) count++;
                
                const badgeStyle = count === 6 ? 'background: #d1fae5; color: #065f46; border: 1px solid #10b981;' : (count >= 3 ? 'background: #fef3c7; color: #92400e; border: 1px solid #f59e0b;' : 'background: #fee2e2; color: #991b1b; border: 1px solid #ef4444;');
                const statusStyle = item.status === 'Diterima' ? 'background: #d1fae5; color: #065f46;' : (item.status === 'Diverifikasi' ? 'background: #dbeafe; color: #1e40af;' : (item.status === 'Ditolak' ? 'background: #fee2e2; color: #991b1b;' : 'background: #fef3c7; color: #92400e;'));

                const fotoHtml = item.file_pas_foto_url 
                    ? `<a href="${item.file_pas_foto_url}" target="_blank" style="text-decoration: none;"><img src="${item.file_pas_foto_url}" alt="Foto" style="width: 44px; height: 54px; object-fit: cover; border-radius: 6px; border: 2px solid #047857; display: block; margin: 0 auto; box-shadow: 0 1px 2px rgba(0,0,0,0.1);" /></a><span style="font-size: 8px; color: #047857; font-weight: bold; display: block; margin-top: 2px; text-align: center;">Pas Foto</span>`
                    : `<span style="color: #94a3b8; font-size: 9px; display: block; text-align: center;">✗ No Foto</span>`;

                const allDocs = [
                    { label: 'KK', url: item.file_kk_url },
                    { label: 'Akta', url: item.file_akta_url },
                    { label: 'Ijazah', url: item.file_ijazah_url },
                    { label: 'Rapor', url: item.file_rapor_url },
                    { label: 'KTP Ortu', url: item.file_ktp_ortu_url }
                ];

                const imgDocs = allDocs.filter(d => d.url && !d.url.toLowerCase().endsWith('.pdf'));
                const imagesHtml = imgDocs.length > 0 
                    ? `<div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 4px; max-width: 140px; margin: 0 auto;">` + 
                      imgDocs.map(d => `<a href="${d.url}" target="_blank" style="text-decoration: none; text-align: center;"><img src="${d.url}" alt="${d.label}" style="width: 36px; height: 36px; object-fit: cover; border-radius: 4px; border: 1.5px solid #10b981; display: block; margin: 0 auto;" /><span style="font-size: 8px; color: #065f46; font-weight: bold; display: block;">${d.label}</span></a>`).join('') + 
                      `</div>`
                    : `<span style="color: #94a3b8; font-size: 9px; font-style: italic; display: block; text-align: center;">Tidak ada gambar</span>`;

                const pdfDocs = allDocs.filter(d => d.url && d.url.toLowerCase().endsWith('.pdf'));
                const pdfsHtml = pdfDocs.length > 0 
                    ? `<div style="display: flex; flex-direction: column; gap: 3px; align-items: center;">` + 
                      pdfDocs.map(d => `<a href="${d.url}" target="_blank" style="display: inline-block; padding: 3px 6px; background: #fee2e2; color: #991b1b; border: 1px solid #ef4444; border-radius: 4px; font-size: 9px; font-weight: bold; text-decoration: none;">📄 PDF ${d.label}</a>`).join('') + 
                      `</div>`
                    : `<span style="color: #94a3b8; font-size: 9px; font-style: italic; display: block; text-align: center;">Tidak ada PDF</span>`;

                rowsHtml += `
                    <tr>
                        <td style="text-align: center;">${idx + 1}</td>
                        <td>
                            <strong style="color: #047857; font-family: monospace;">${item.registration_number || '-'}</strong><br>
                            <span style="font-size: 10px; color: #64748b;">${item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID') : '-'}</span>
                        </td>
                        <td>
                            <strong>${item.nama_lengkap || '-'}</strong><br>
                            <span style="font-size: 11px; color: #475569;">${item.jenis_kelamin || '-'}</span>
                        </td>
                        <td>${item.asal_sekolah || '-'}</td>
                        <td>
                            <strong>${item.nama_orang_tua || '-'}</strong><br>
                            <span style="font-size: 11px; color: #059669; font-family: monospace;">WA: ${item.nomor_whatsapp_ortu || '-'}</span>
                        </td>
                        <td style="text-align: center; padding: 6px;">${fotoHtml}</td>
                        <td style="text-align: center; padding: 6px;">${imagesHtml}</td>
                        <td style="text-align: center; padding: 6px;">${pdfsHtml}</td>
                        <td style="text-align: center;">
                            <div style="margin-bottom: 4px;">
                                <span style="padding: 2px 8px; border-radius: 99px; font-size: 9px; font-weight: bold; display: inline-block; ${badgeStyle}">
                                    ${count}/6 Berkas
                                </span>
                            </div>
                            <span style="padding: 4px 10px; border-radius: 99px; font-size: 11px; font-weight: bold; display: inline-block; ${statusStyle}">
                                ${item.status || 'Menunggu'}
                            </span>
                        </td>
                    </tr>
                `;
            });

            const html = `<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan PPDB Online - Pesantren Ulul Albab</title>
    <style>
        @page { size: A4 landscape; margin: 15mm; }
        body { font-family: 'Arial', sans-serif; color: #1e293b; margin: 0; padding: 20px; font-size: 12px; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .kop-surat { display: flex; align-items: center; border-bottom: 3px double #047857; padding-bottom: 15px; margin-bottom: 20px; }
        .logo { width: 85px; height: 85px; object-fit: contain; margin-right: 20px; }
        .kop-text { flex: 1; text-align: center; }
        .kop-text h1 { font-size: 18px; margin: 0 0 5px 0; color: #047857; font-weight: 900; text-transform: uppercase; }
        .kop-text h2 { font-size: 15px; margin: 0 0 5px 0; color: #0f172a; font-weight: 800; }
        .kop-text p { font-size: 11px; margin: 2px 0; color: #475569; }
        .judul-laporan { text-align: center; margin-bottom: 20px; }
        .judul-laporan h3 { font-size: 16px; margin: 0 0 5px 0; text-transform: uppercase; color: #0f172a; }
        .judul-laporan p { font-size: 12px; color: #64748b; margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #cbd5e1; padding: 8px 10px; text-align: left; vertical-align: middle; }
        th { background-color: #f1f5f9; font-size: 11px; text-transform: uppercase; color: #334155; font-weight: bold; text-align: center; }
        tr:nth-child(even) { background-color: #f8fafc; }
        .footer-ttd { float: right; width: 260px; text-align: center; margin-top: 20px; page-break-inside: avoid; }
        .footer-ttd p { margin: 5px 0; }
        .ttd-space { height: 70px; }
    </style>
</head>
<body>
    <div class="kop-surat">
        <img src="${logoUrl}" alt="Logo Ulul Albab" class="logo" onerror="this.style.display='none'" />
        <div class="kop-text">
            <h1>PONDOK PESANTREN ULUL ALBAB MAKASSAR</h1>
            <h2>PANITIA PENERIMAAN PESERTA DIDIK BARU (PPDB) ONLINE T.A. 2026/2027</h2>
            <p>Jenjang Pendidikan: Madrasah Aliyah (MA) Berakreditasi Unggul</p>
            <p>Alamat: Jl. Perintis Kemerdekaan KM. 13, Daya, Kec. Biringkanaya, Kota Makassar, Sulawesi Selatan 90241</p>
            <p>Website: www.ululalbab.sch.id | Email: ponpesululalbabmks@gmail.com | WA: 0858-2424-6172 / 0852-4250-1959</p>
        </div>
    </div>

    <div class="judul-laporan">
        <h3>Laporan Rekapitulasi Data Pendaftar & Kelengkapan Berkas</h3>
        <p>Filter Status: <strong>${statusFilter}</strong> | Dicetak pada: <strong>${todayStr}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 120px;">No. Daftar & Tgl</th>
                <th style="width: 130px;">Nama & JK</th>
                <th style="width: 120px;">Asal Sekolah</th>
                <th style="width: 130px;">Ortu & WhatsApp</th>
                <th style="width: 65px;">Foto Siswa</th>
                <th style="width: 140px;">Berkas Gambar</th>
                <th style="width: 120px;">Berkas PDF</th>
                <th style="width: 90px;">Status</th>
            </tr>
        </thead>
        <tbody>
            ${rowsHtml || '<tr><td colspan="9" style="text-align:center; padding: 20px;">Tidak ada data pendaftar yang sesuai filter</td></tr>'}
        </tbody>
    </table>

    <div class="footer-ttd">
        <p>Makassar, ${todayStr}</p>
        <p>Mengetahui,</p>
        <p><strong>Ketua Panitia PPDB Online</strong></p>
        <div class="ttd-space"></div>
        <p style="text-decoration: underline; font-weight: bold;">(________________________)</p>
        <p style="font-size: 11px; color: #64748b;">NIP / NIK. ........................</p>
    </div>
</body>
</html>`;

            if (printWin) {
                printWin.document.open();
                printWin.document.write(html);
                printWin.document.close();
                setTimeout(() => {
                    printWin.focus();
                    printWin.print();
                }, 750);
            }
        } catch (err) {
            console.error('Print error:', err);
            showToast('Gagal menyiapkan cetak laporan', 'error');
            if (printWin) printWin.close();
        }
    };

    // --- METHODS GELOMBANG (TAB 2) ---
    const fetchPPDB = async (page = 1) => {
        loading = true;
        try {
            const response = await api.get(`/v1/admin/ppdb?page=${page}`);
            ppdbs = response.data?.data || [];
            pagination = response.data?.meta || null;
            currentPage = page;
        } finally { loading = false; }
    };

    const openAddModal = () => {
        isEditing = false;
        formId = null; formBatch = ''; formStartDate = ''; formEndDate = ''; formFee = 0; formStatus = false; formReqs = '';
        showModal = true;
    };

    const openEditModal = (item) => {
        isEditing = true;
        formId = item.id; formBatch = item.batch_name; formStartDate = item.start_date; formEndDate = item.end_date; formFee = item.fee; formStatus = item.status == 1; formReqs = item.requirements;
        showModal = true;
    };

    const submitForm = async (e) => {
        e.preventDefault();
        submitting = true;
        const payload = { batch_name: formBatch, start_date: formStartDate, end_date: formEndDate, fee: formFee, status: formStatus ? 1 : 0, requirements: formReqs };

        try {
            if (isEditing) await api.put(`/v1/admin/ppdb/${formId}`, payload);
            else await api.post('/v1/admin/ppdb', payload);
            showToast(isEditing ? 'Gelombang diperbarui' : 'Gelombang ditambahkan');
            showModal = false;
            fetchPPDB(currentPage);
        } finally { submitting = false; }
    };

    const handleDeleteBatch = async () => {
        submitting = true;
        try {
            await api.delete(`/v1/admin/ppdb/${deleteId}`);
            showToast('Gelombang dihapus');
            showDeleteModal = false;
            fetchPPDB(currentPage);
        } finally { submitting = false; }
    };

    onMount(() => {
        fetchRegistrations(1);
        fetchPPDB(1);
    });
</script>

<div class="space-y-6">
    <!-- Title & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-heading text-text-title">Manajemen PPDB Online</h1>
            <p class="text-xs sm:text-sm text-text-body mt-0.5">Kelola data pendaftar santri baru dan pengaturan gelombang pendaftaran.</p>
        </div>

        {#if activeTab === 'pendaftar'}
            <div class="flex items-center gap-2 flex-wrap">
                <button 
                    onclick={exportExcel} 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs sm:text-sm rounded-xl shadow-sm transition-all"
                >
                    <Download size={16} /> Export Excel / CSV
                </button>
                <button 
                    onclick={printTable} 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-black text-white font-semibold text-xs sm:text-sm rounded-xl shadow-sm transition-all"
                >
                    <Printer size={16} /> Cetak Laporan (PDF)
                </button>
            </div>
        {:else}
            <Button size="sm" onclick={openAddModal} class="w-full sm:w-auto">
                <Plus size={18} class="mr-2" /> Buka Gelombang Baru
            </Button>
        {/if}
    </div>

    {#if toast.show}
        <div class="fixed top-24 right-6 z-50 px-4 py-3 bg-white border {toast.type === 'error' ? 'border-red-300 text-red-700' : 'border-green-200 text-green-700'} rounded-xl shadow-xl flex gap-3 animate-fade-left items-center font-medium text-sm">
            <CheckCircle2 size={18} class="shrink-0" /> {toast.message}
        </div>
    {/if}

    <!-- Navigation Tabs -->
    <div class="flex border-b border-border-color gap-4">
        <button 
            class="py-3 px-5 font-bold text-sm border-b-2 transition-all flex items-center gap-2 {activeTab === 'pendaftar' ? 'border-primary text-primary bg-primary/5 rounded-t-xl' : 'border-transparent text-text-body hover:text-text-title'}"
            onclick={() => activeTab = 'pendaftar'}
        >
            <User size={18} /> Data Pendaftar
            {#if regMeta?.total}
                <span class="px-2 py-0.5 rounded-full bg-primary text-white text-[10px] font-bold">{regMeta.total}</span>
            {/if}
        </button>
        <button 
            class="py-3 px-5 font-bold text-sm border-b-2 transition-all flex items-center gap-2 {activeTab === 'gelombang' ? 'border-primary text-primary bg-primary/5 rounded-t-xl' : 'border-transparent text-text-body hover:text-text-title'}"
            onclick={() => activeTab = 'gelombang'}
        >
            <FileText size={18} /> Pengaturan Gelombang & Biaya
        </button>
    </div>

    <!-- TAB 1 CONTENT: DATA PENDAFTAR -->
    {#if activeTab === 'pendaftar'}
        <!-- Filter & Search Bar -->
        <Card class="p-4 bg-gray-50/50 border border-border-color">
            <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
                <form onsubmit={handleSearch} class="flex items-center gap-2 flex-1 max-w-md">
                    <div class="relative flex-1">
                        <Search size={16} class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" />
                        <input 
                            type="text" 
                            bind:value={searchQuery}
                            placeholder="Cari nama, nomor pendaftaran, sekolah..." 
                            class="w-full pl-9 pr-4 py-2 text-xs sm:text-sm rounded-xl border border-border-color focus:outline-none focus:ring-2 focus:ring-primary bg-white font-medium"
                        />
                    </div>
                    <Button type="submit" size="sm">Cari</Button>
                    {#if searchQuery}
                        <button type="button" onclick={() => { searchQuery = ''; fetchRegistrations(1); }} class="px-3 py-2 text-xs text-gray-500 hover:text-red-500 font-bold">Reset</button>
                    {/if}
                </form>

                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-xs font-bold text-text-body flex items-center gap-1"><Filter size={14} /> Status:</span>
                    {#each ['Semua', 'Menunggu', 'Diverifikasi', 'Diterima', 'Ditolak'] as st}
                        <button 
                            type="button" 
                            onclick={() => handleFilterChange(st)}
                            class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {statusFilter === st ? 'bg-primary text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-100'}"
                        >
                            {st}
                        </button>
                    {/each}
                    <button onclick={() => fetchRegistrations(regPage)} title="Refresh" class="p-2 bg-white border border-gray-200 rounded-xl text-gray-600 hover:bg-gray-100">
                        <RefreshCw size={16} class={regLoading ? 'animate-spin text-primary' : ''} />
                    </button>
                </div>
            </div>
        </Card>

        <!-- Tabel Data Pendaftar -->
        <Card class="overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100/80 text-[11px] uppercase tracking-wider text-text-title font-extrabold border-b border-border-color">
                            <th class="px-3 py-3.5">No. Daftar</th>
                            <th class="px-3 py-3.5 text-center">Foto Siswa</th>
                            <th class="px-3 py-3.5">Berkas Gambar</th>
                            <th class="px-3 py-3.5">Berkas PDF</th>
                            <th class="px-3 py-3.5">Nama Lengkap</th>
                            <th class="px-3 py-3.5">JK</th>
                            <th class="px-3 py-3.5">Asal Sekolah</th>
                            <th class="px-3 py-3.5">Ortu / Wali</th>
                            <th class="px-3 py-3.5">No. WhatsApp</th>
                            <th class="px-3 py-3.5">Tgl Daftar</th>
                            <th class="px-3 py-3.5">Status</th>
                            <th class="px-3 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-color text-xs sm:text-sm">
                        {#if regLoading}
                            <tr><td colspan="12" class="px-6 py-12 text-center text-gray-500 font-medium">Memuat data pendaftar...</td></tr>
                        {:else if registrations.length === 0}
                            <tr><td colspan="12" class="px-6 py-12 text-center text-gray-500 font-medium">Belum ada data pendaftar yang sesuai.</td></tr>
                        {:else}
                            {#each registrations as item}
                                <tr class="hover:bg-primary/5 transition-colors">
                                    <td class="px-3 py-3 font-mono font-bold text-primary-dark">{item.registration_number}</td>
                                    
                                    <!-- 1. FOTO SISWA -->
                                    <td class="px-3 py-3 text-center">
                                        {#if item.file_pas_foto_url}
                                            <div class="relative group inline-block">
                                                <img 
                                                    src={item.file_pas_foto_url} 
                                                    alt="Foto" 
                                                    class="w-12 h-14 rounded-lg object-cover border-2 border-emerald-500 shadow-sm cursor-pointer hover:scale-110 transition-transform" 
                                                    onclick={() => openPreviewFile('Pas Foto 3x4', item.file_pas_foto_url)} 
                                                    title="Klik untuk perbesar Pas Foto" 
                                                />
                                                <span class="absolute -bottom-1 inset-x-0 bg-emerald-600 text-white text-[8px] font-bold text-center rounded-b py-0.5">Foto</span>
                                            </div>
                                        {:else}
                                            <div class="w-12 h-14 rounded-lg bg-gray-100 text-gray-400 flex flex-col items-center justify-center border mx-auto"><User size={18} /><span class="text-[8px] mt-0.5">No Foto</span></div>
                                        {/if}
                                    </td>

                                    <!-- 2. BERKAS GAMBAR (Foto Dokumen) -->
                                    <td class="px-3 py-3">
                                        <div class="flex items-center gap-1.5 flex-wrap max-w-[150px]">
                                            {#each [
                                                { label: 'Kartu Keluarga', url: item.file_kk_url, name: 'KK' },
                                                { label: 'Akta Kelahiran', url: item.file_akta_url, name: 'Akta' },
                                                { label: 'Ijazah / SKL', url: item.file_ijazah_url, name: 'Ijazah' },
                                                { label: 'Rapor Terakhir', url: item.file_rapor_url, name: 'Rapor' },
                                                { label: 'KTP Ortu', url: item.file_ktp_ortu_url, name: 'KTP' }
                                            ].filter(f => f.url && !f.url.toLowerCase().endsWith('.pdf')) as f}
                                                <button 
                                                    type="button" 
                                                    onclick={() => openPreviewFile(f.label, f.url)}
                                                    class="w-11 h-12 rounded-lg overflow-hidden border border-emerald-400 bg-gray-50 hover:border-emerald-600 transition-all shadow-sm relative group flex flex-col justify-between cursor-pointer shrink-0"
                                                    title="Klik lihat Gambar: {f.label}"
                                                >
                                                    <img src={f.url} alt={f.label} class="w-full h-7 object-cover group-hover:scale-110 transition-transform" />
                                                    <span class="bg-emerald-600 text-white text-[8px] font-bold text-center w-full py-0.5 truncate px-0.5">{f.name}</span>
                                                </button>
                                            {:else}
                                                <span class="text-[10px] text-gray-400 italic">Tidak ada gambar</span>
                                            {/each}
                                        </div>
                                    </td>

                                    <!-- 3. BERKAS PDF -->
                                    <td class="px-3 py-3">
                                        <div class="flex items-center gap-1.5 flex-wrap max-w-[150px]">
                                            {#each [
                                                { label: 'Kartu Keluarga', url: item.file_kk_url, name: 'KK' },
                                                { label: 'Akta Kelahiran', url: item.file_akta_url, name: 'Akta' },
                                                { label: 'Ijazah / SKL', url: item.file_ijazah_url, name: 'Ijazah' },
                                                { label: 'Rapor Terakhir', url: item.file_rapor_url, name: 'Rapor' },
                                                { label: 'KTP Ortu', url: item.file_ktp_ortu_url, name: 'KTP' }
                                            ].filter(f => f.url && f.url.toLowerCase().endsWith('.pdf')) as f}
                                                <button 
                                                    type="button" 
                                                    onclick={() => openPreviewFile(f.label, f.url)}
                                                    class="w-11 h-12 rounded-lg bg-red-50 hover:bg-red-100 border border-red-300 flex flex-col items-center justify-between p-0.5 transition-all shadow-sm group cursor-pointer shrink-0"
                                                    title="Klik lihat PDF: {f.label}"
                                                >
                                                    <span class="text-[9px] font-black text-red-600 mt-1">PDF</span>
                                                    <span class="bg-red-600 text-white text-[8px] font-bold text-center w-full rounded py-0.5 truncate px-0.5">{f.name}</span>
                                                </button>
                                            {:else}
                                                <span class="text-[10px] text-gray-400 italic">Tidak ada PDF</span>
                                            {/each}
                                        </div>
                                    </td>

                                    <td class="px-3 py-3 font-bold text-text-title">{item.nama_lengkap}</td>
                                    <td class="px-4 py-3 font-medium text-text-body">{item.jenis_kelamin?.includes('Putra') ? 'Putra' : 'Putri'}</td>
                                    <td class="px-4 py-3 font-medium text-text-body">{item.asal_sekolah}</td>
                                    <td class="px-4 py-3 font-medium text-text-body">{item.nama_orang_tua}</td>
                                    <td class="px-4 py-3 font-mono">
                                        <a href="https://wa.me/{item.nomor_whatsapp_ortu?.replace(/^0/, '62')}" target="_blank" class="text-emerald-600 hover:underline font-bold inline-flex items-center gap-1">
                                            {item.nomor_whatsapp_ortu} <ExternalLink size={12} />
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 text-text-body text-xs">{item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID') : '-'}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold 
                                            {item.status === 'Diterima' ? 'bg-green-100 text-green-700 border border-green-200' : 
                                             item.status === 'Diverifikasi' ? 'bg-blue-100 text-blue-700 border border-blue-200' : 
                                             item.status === 'Ditolak' ? 'bg-red-100 text-red-700 border border-red-200' : 
                                             'bg-amber-100 text-amber-700 border border-amber-200'}"
                                        >
                                            {item.status || 'Menunggu'}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                            {#if item.status !== 'Diterima'}
                                                <button 
                                                    onclick={() => updateStatus(item.id, 'Diterima')} 
                                                    disabled={statusUpdating}
                                                    class="px-3 py-1.5 bg-green-600 hover:bg-green-700 disabled:opacity-50 text-white rounded-xl text-xs font-bold transition-all shadow-sm inline-flex items-center gap-1"
                                                    title="Terima Santri Ini"
                                                >
                                                    <CheckCircle size={14} /> Terima
                                                </button>
                                            {/if}
                                            {#if item.status !== 'Ditolak'}
                                                <button 
                                                    onclick={() => updateStatus(item.id, 'Ditolak')} 
                                                    disabled={statusUpdating}
                                                    class="px-3 py-1.5 bg-red-600 hover:bg-red-700 disabled:opacity-50 text-white rounded-xl text-xs font-bold transition-all shadow-sm inline-flex items-center gap-1"
                                                    title="Tolak Santri Ini"
                                                >
                                                    <XCircle size={14} /> Tolak
                                                </button>
                                            {/if}
                                            <button 
                                                onclick={() => openDetail(item)} 
                                                class="px-3 py-1.5 bg-primary/10 hover:bg-primary text-primary hover:text-white rounded-xl transition-all font-bold text-xs inline-flex items-center gap-1 shadow-sm"
                                                title="Lihat Detail & 6 Berkas"
                                            >
                                                <Eye size={14} /> Detail & Berkas
                                            </button>
                                            <button 
                                                onclick={() => confirmDeleteReg(item.id)} 
                                                class="p-2 text-red-500 hover:bg-red-50 rounded-xl transition-colors"
                                                title="Hapus Data"
                                            >
                                                <Trash2 size={16} />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            {/each}
                        {/if}
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            {#if regMeta && regMeta.last_page > 1}
                <div class="p-4 border-t border-border-color flex items-center justify-between gap-4 text-xs font-bold text-text-body">
                    <span>Menampilkan {registrations.length} dari total {regMeta.total} pendaftar</span>
                    <div class="flex gap-2">
                        <button 
                            disabled={regPage <= 1} 
                            onclick={() => fetchRegistrations(regPage - 1)}
                            class="px-3 py-1.5 rounded-xl border bg-white hover:bg-gray-50 disabled:opacity-50"
                        >
                            Sebelumnya
                        </button>
                        <span class="px-3 py-1.5 bg-primary/10 text-primary rounded-xl">Halaman {regPage} / {regMeta.last_page}</span>
                        <button 
                            disabled={regPage >= regMeta.last_page} 
                            onclick={() => fetchRegistrations(regPage + 1)}
                            class="px-3 py-1.5 rounded-xl border bg-white hover:bg-gray-50 disabled:opacity-50"
                        >
                            Selanjutnya
                        </button>
                    </div>
                </div>
            {/if}
        </Card>
    {/if}

    <!-- TAB 2 CONTENT: GELOMBANG / BATCHES -->
    {#if activeTab === 'gelombang'}
        <Card class="overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-bg-section/50 text-sm uppercase tracking-wider font-semibold text-text-title border-b">
                            <th class="px-6 py-4">Nama Gelombang</th>
                            <th class="px-6 py-4">Periode</th>
                            <th class="px-6 py-4">Biaya</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-color">
                        {#if loading}
                            <tr><td colspan="5" class="px-6 py-8 text-center">Loading...</td></tr>
                        {:else if ppdbs.length === 0}
                            <tr><td colspan="5" class="px-6 py-8 text-center text-sm">Tidak ada data gelombang PPDB.</td></tr>
                        {:else}
                            {#each ppdbs as item}
                                <tr class="hover:bg-bg-section/30">
                                    <td class="px-6 py-4 font-bold text-text-title">{item.batch_name}</td>
                                    <td class="px-6 py-4 text-sm">{item.start_date} s/d {item.end_date}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-primary">Rp {new Intl.NumberFormat('id-ID').format(item.fee)}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold {item.status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}">
                                            {item.status ? 'Buka (Aktif)' : 'Tutup'}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg" onclick={() => openEditModal(item)}><Edit2 size={18} /></button>
                                        <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg" onclick={() => { deleteId = item.id; deleteType = 'gelombang'; showDeleteModal = true; }}><Trash2 size={18} /></button>
                                    </td>
                                </tr>
                            {/each}
                        {/if}
                    </tbody>
                </table>
            </div>
        </Card>
    {/if}
</div>

<!-- MODAL DETAIL & BERKAS PENDAFTAR -->
{#if showDetailModal && selectedReg}
    <div 
        class="fixed inset-0 z-50 bg-black/75 backdrop-blur-md flex items-center justify-center p-4 sm:p-6 overflow-y-auto animate-fade-in"
        onclick={(e) => { if (e.target === e.currentTarget) showDetailModal = false; }}
    >
        <div class="bg-white rounded-[24px] max-w-4xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border p-6 sm:p-8 relative animate-scale-up my-auto">
            <button 
                class="absolute top-5 right-5 w-10 h-10 rounded-full bg-gray-100 hover:bg-red-500 hover:text-white text-gray-500 flex items-center justify-center transition-all"
                onclick={() => showDetailModal = false}
            >
                <X size={20} />
            </button>

            <!-- Header Modal -->
            <div class="border-b border-border-color pb-4 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <span class="px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-mono font-bold">{selectedReg.registration_number}</span>
                    <h3 class="text-2xl font-heading font-bold text-text-title mt-1.5">{selectedReg.nama_lengkap}</h3>
                    <p class="text-xs text-text-body">Pendaftar Jenjang MA (Madrasah Aliyah) • Terdaftar tgl: {selectedReg.created_at ? new Date(selectedReg.created_at).toLocaleDateString('id-ID') : '-'}</p>
                </div>
                <div>
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-extrabold shadow-sm
                        {selectedReg.status === 'Diterima' ? 'bg-green-600 text-white' : 
                         selectedReg.status === 'Diverifikasi' ? 'bg-blue-600 text-white' : 
                         selectedReg.status === 'Ditolak' ? 'bg-red-600 text-white' : 
                         'bg-amber-500 text-white'}"
                    >
                        Status: {selectedReg.status || 'Menunggu'}
                    </span>
                </div>
            </div>

            <!-- Grid Data Pribadi & Sekolah -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 text-xs sm:text-sm">
                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200 space-y-2.5">
                    <h4 class="font-bold text-primary uppercase text-xs tracking-wider border-b pb-1.5">Informasi Santri</h4>
                    <div class="flex justify-between py-1 border-b border-gray-200/60"><span class="text-gray-500">Jenis Kelamin:</span> <span class="font-bold">{selectedReg.jenis_kelamin}</span></div>
                    <div class="flex justify-between py-1 border-b border-gray-200/60"><span class="text-gray-500">Tempat, Tgl Lahir:</span> <span class="font-bold">{selectedReg.tempat_lahir}, {selectedReg.tanggal_lahir}</span></div>
                    <div class="py-1"><span class="text-gray-500 block mb-0.5">Alamat Lengkap:</span> <span class="font-semibold text-gray-800 leading-relaxed block">{selectedReg.alamat_lengkap}</span></div>
                </div>

                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200 space-y-2.5">
                    <h4 class="font-bold text-primary uppercase text-xs tracking-wider border-b pb-1.5">Sekolah & Wali</h4>
                    <div class="flex justify-between py-1 border-b border-gray-200/60"><span class="text-gray-500">Asal Sekolah:</span> <span class="font-bold">{selectedReg.asal_sekolah}</span></div>
                    <div class="flex justify-between py-1 border-b border-gray-200/60"><span class="text-gray-500">Nama Orang Tua/Wali:</span> <span class="font-bold">{selectedReg.nama_orang_tua}</span></div>
                    <div class="flex justify-between py-1 border-b border-gray-200/60">
                        <span class="text-gray-500">Nomor WhatsApp:</span> 
                        <a href="https://wa.me/{selectedReg.nomor_whatsapp_ortu?.replace(/^0/, '62')}" target="_blank" class="font-bold text-emerald-600 hover:underline inline-flex items-center gap-1">
                            {selectedReg.nomor_whatsapp_ortu} <ExternalLink size={12} />
                        </a>
                    </div>
                    <div class="py-1"><span class="text-gray-500 block mb-0.5">Catatan:</span> <span class="font-semibold text-gray-800 italic">{selectedReg.catatan || 'Tidak ada catatan'}</span></div>
                </div>
            </div>

            <!-- Section: BERKAS PERSYARATAN UPLOADED -->
            <div class="mb-8">
                <h4 class="text-sm font-extrabold text-text-title uppercase tracking-wider mb-3 flex items-center gap-2">
                    <FileCheck size={18} class="text-primary" /> Dokumen Berkas Persyaratan (6 File)
                </h4>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    {#each [
                        { label: 'Kartu Keluarga', url: selectedReg.file_kk_url },
                        { label: 'Akta Kelahiran', url: selectedReg.file_akta_url },
                        { label: 'Ijazah / SKL', url: selectedReg.file_ijazah_url },
                        { label: 'Rapor Terakhir', url: selectedReg.file_rapor_url },
                        { label: 'Pas Foto 3x4', url: selectedReg.file_pas_foto_url },
                        { label: 'KTP Orang Tua', url: selectedReg.file_ktp_ortu_url }
                    ] as doc}
                        <div class="p-3 rounded-2xl border {doc.url ? 'border-green-300 bg-green-50/40 shadow-sm' : 'border-gray-200 bg-gray-50'} flex flex-col justify-between gap-3 overflow-hidden">
                            <div class="flex items-center justify-between gap-2 border-b border-gray-200/60 pb-2">
                                <p class="text-xs font-extrabold text-text-title truncate flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full {doc.url ? 'bg-green-500' : 'bg-gray-300'}"></span>
                                    {doc.label}
                                </p>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {doc.url ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-500'}">
                                    {doc.url ? (doc.url.toLowerCase().endsWith('.pdf') ? 'PDF' : 'GAMBAR') : 'KOSONG'}
                                </span>
                            </div>

                            <!-- DIRECT PREVIEW THUMBNAIL / EMBED -->
                            <div class="w-full h-40 rounded-xl overflow-hidden bg-white border border-gray-200 flex items-center justify-center relative group">
                                {#if doc.url}
                                    {#if doc.url.toLowerCase().endsWith('.pdf')}
                                        <div class="w-full h-full flex flex-col items-center justify-center p-3 text-center bg-red-50/30">
                                            <div class="w-12 h-12 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center font-black text-xs mb-1.5 shadow-sm">PDF</div>
                                            <span class="text-[11px] font-bold text-gray-700">Dokumen PDF Terupload</span>
                                            <span class="text-[9px] text-gray-500 mt-0.5">Klik tombol di bawah untuk membuka</span>
                                        </div>
                                    {:else}
                                        <img 
                                            src={doc.url} 
                                            alt={doc.label} 
                                            class="w-full h-full object-cover cursor-pointer group-hover:scale-105 transition-transform duration-300" 
                                            onclick={() => openPreviewFile(doc.label, doc.url)}
                                            title="Klik untuk memperbesar gambar"
                                        />
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                                            <span class="px-3 py-1 bg-white/90 rounded-full text-black text-xs font-bold flex items-center gap-1 shadow"><Eye size={12} /> Perbesar</span>
                                        </div>
                                    {/if}
                                {:else}
                                    <div class="text-gray-400 text-xs italic flex flex-col items-center gap-1">
                                        <XCircle size={24} class="text-gray-300" />
                                        <span>Tidak ada berkas</span>
                                    </div>
                                {/if}
                            </div>

                            {#if doc.url}
                                <div class="flex gap-1.5 pt-1">
                                    <button 
                                        type="button" 
                                        onclick={() => openPreviewFile(doc.label, doc.url)}
                                        class="flex-1 py-1.5 px-2 bg-white hover:bg-primary hover:text-white text-primary border border-primary/40 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1 shadow-sm"
                                    >
                                        <Eye size={13} /> {doc.url.toLowerCase().endsWith('.pdf') ? 'Buka PDF' : 'Lihat Gambar'}
                                    </button>
                                    <a 
                                        href={doc.url} 
                                        download
                                        target="_blank"
                                        class="flex-1 py-1.5 px-2 bg-primary hover:bg-primary-dark text-white rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1 shadow-sm text-center"
                                    >
                                        <Download size={13} /> Unduh
                                    </a>
                                </div>
                            {:else}
                                <div class="py-1.5 text-[11px] text-gray-400 text-center italic">Berkas belum diunggah</div>
                            {/if}
                        </div>
                    {/each}
                </div>
            </div>

            <!-- Tombol Aksi Verifikasi Status -->
            <div class="p-4 rounded-2xl bg-gray-100/80 border border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-xs font-bold text-text-title">
                    <span>Ubah Status Verifikasi Admin:</span>
                    <p class="text-[11px] text-text-body font-normal">Klik tombol di kanan untuk memverifikasi, menerima, atau menolak santri ini.</p>
                </div>
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <button 
                        type="button" 
                        disabled={statusUpdating || selectedReg.status === 'Diverifikasi'}
                        onclick={() => updateStatus(selectedReg.id, 'Diverifikasi')}
                        class="flex-1 sm:flex-none px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white font-bold text-xs rounded-xl shadow transition-all flex items-center justify-center gap-1"
                    >
                        <Clock size={14} /> Verifikasi
                    </button>
                    <button 
                        type="button" 
                        disabled={statusUpdating || selectedReg.status === 'Diterima'}
                        onclick={() => updateStatus(selectedReg.id, 'Diterima')}
                        class="flex-1 sm:flex-none px-4 py-2 bg-green-600 hover:bg-green-700 disabled:opacity-50 text-white font-bold text-xs rounded-xl shadow transition-all flex items-center justify-center gap-1"
                    >
                        <CheckCircle size={14} /> Terima
                    </button>
                    <button 
                        type="button" 
                        disabled={statusUpdating || selectedReg.status === 'Ditolak'}
                        onclick={() => updateStatus(selectedReg.id, 'Ditolak')}
                        class="flex-1 sm:flex-none px-4 py-2 bg-red-600 hover:bg-red-700 disabled:opacity-50 text-white font-bold text-xs rounded-xl shadow transition-all flex items-center justify-center gap-1"
                    >
                        <XCircle size={14} /> Tolak
                    </button>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <Button onclick={() => showDetailModal = false}>Tutup Detail</Button>
            </div>
        </div>
    </div>
{/if}

<!-- MODAL PREVIEW FILE (LIHAT BERKAS) -->
{#if showFilePreviewModal}
    <div 
        class="fixed inset-0 z-[9999] bg-black/85 backdrop-blur-md flex items-center justify-center p-4 sm:p-6 overflow-hidden animate-fade-in"
        onclick={(e) => { if (e.target === e.currentTarget) showFilePreviewModal = false; }}
    >
        <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] flex flex-col overflow-hidden shadow-2xl animate-scale-up">
            <div class="p-4 border-b flex items-center justify-between bg-gray-50">
                <h4 class="font-bold text-sm text-text-title flex items-center gap-2">
                    <Eye size={18} class="text-primary" /> Preview Dokumen: {previewFileTitle}
                </h4>
                <div class="flex items-center gap-2">
                    <a href={previewFileUrl} download target="_blank" class="px-3 py-1.5 bg-primary text-white rounded-lg text-xs font-bold hover:bg-primary-dark inline-flex items-center gap-1">
                        <Download size={14} /> Download
                    </a>
                    <button onclick={() => showFilePreviewModal = false} class="w-8 h-8 rounded-full bg-gray-200 hover:bg-red-500 hover:text-white flex items-center justify-center">
                        <X size={18} />
                    </button>
                </div>
            </div>

            <div class="flex-1 p-4 bg-gray-100 flex items-center justify-center overflow-auto min-h-[400px]">
                {#if isPdfPreview}
                    <iframe src={previewFileUrl} title="PDF Preview" class="w-full h-[600px] rounded-xl border bg-white"></iframe>
                {:else}
                    <img src={previewFileUrl} alt="Preview Dokumen" class="max-w-full max-h-[70vh] rounded-xl shadow-lg object-contain" />
                {/if}
            </div>
        </div>
    </div>
{/if}

<!-- MODAL TAMBAH/EDIT GELOMBANG -->
<Modal bind:show={showModal} title={isEditing ? 'Edit Gelombang PPDB' : 'Buka Gelombang PPDB Baru'}>
    <form onsubmit={submitForm} class="space-y-4">
        <input type="text" bind:value={formBatch} placeholder="Nama Gelombang (Misal: Gelombang 1 2026/2027)" required class="w-full px-4 py-2 border rounded-xl font-medium text-sm" />
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold mb-1">Tanggal Mulai</label>
                <input type="date" bind:value={formStartDate} required class="w-full px-4 py-2 border rounded-xl text-sm font-medium" />
            </div>
            <div>
                <label class="block text-xs font-bold mb-1">Tanggal Selesai</label>
                <input type="date" bind:value={formEndDate} required class="w-full px-4 py-2 border rounded-xl text-sm font-medium" />
            </div>
        </div>
        <div>
            <label class="block text-xs font-bold mb-1">Biaya Pendaftaran (Rp)</label>
            <input type="number" bind:value={formFee} placeholder="Biaya Pendaftaran (Rp)" required class="w-full px-4 py-2 border rounded-xl text-sm font-medium" />
        </div>
        <div>
            <label class="block text-xs font-bold mb-1">Persyaratan Khusus</label>
            <textarea bind:value={formReqs} placeholder="Tuliskan persyaratan jika ada..." rows="3" class="w-full px-4 py-2 border rounded-xl resize-none text-sm font-medium"></textarea>
        </div>
        
        <label class="flex items-center gap-2 text-sm font-bold pt-2 cursor-pointer">
            <input type="checkbox" bind:checked={formStatus} class="w-4 h-4 rounded text-primary focus:ring-primary" />
            Buka Pendaftaran (Aktifkan Gelombang Ini)
        </label>

        <div class="pt-4 flex justify-end gap-3">
            <button type="button" class="px-4 py-2 hover:bg-gray-100 rounded-xl text-sm font-bold text-gray-600" onclick={() => showModal = false}>Batal</button>
            <Button type="submit" disabled={submitting}>{submitting ? 'Menyimpan...' : 'Simpan Gelombang'}</Button>
        </div>
    </form>
</Modal>

<!-- MODAL KONFIRMASI HAPUS -->
<Modal bind:show={showDeleteModal} title="Konfirmasi Hapus Data">
    <div class="mb-6 text-sm text-gray-700">Apakah Anda yakin ingin menghapus data {deleteType === 'registration' ? 'pendaftar' : 'gelombang'} ini? Tindakan ini tidak dapat dibatalkan dan seluruh berkas yang terupload akan dihapus.</div>
    <div class="flex justify-end gap-3">
        <button class="px-4 py-2 hover:bg-gray-100 rounded-xl text-sm font-bold text-gray-600" onclick={() => showDeleteModal = false}>Batal</button>
        <button class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl text-sm transition-colors" onclick={deleteType === 'registration' ? handleDeleteReg : handleDeleteBatch}>Ya, Hapus Sekarang</button>
    </div>
</Modal>
