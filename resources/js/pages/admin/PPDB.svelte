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

    const printTable = () => {
        window.print();
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
                    <Printer size={16} /> Print
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
                            <th class="px-4 py-3.5">No. Daftar</th>
                            <th class="px-4 py-3.5">Foto</th>
                            <th class="px-4 py-3.5">Nama Lengkap</th>
                            <th class="px-4 py-3.5">JK</th>
                            <th class="px-4 py-3.5">Asal Sekolah</th>
                            <th class="px-4 py-3.5">Ortu / Wali</th>
                            <th class="px-4 py-3.5">No. WhatsApp</th>
                            <th class="px-4 py-3.5">Tgl Daftar</th>
                            <th class="px-4 py-3.5">Status</th>
                            <th class="px-4 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-color text-xs sm:text-sm">
                        {#if regLoading}
                            <tr><td colspan="10" class="px-6 py-12 text-center text-gray-500 font-medium">Memuat data pendaftar...</td></tr>
                        {:else if registrations.length === 0}
                            <tr><td colspan="10" class="px-6 py-12 text-center text-gray-500 font-medium">Belum ada data pendaftar yang sesuai.</td></tr>
                        {:else}
                            {#each registrations as item}
                                <tr class="hover:bg-primary/5 transition-colors">
                                    <td class="px-4 py-3 font-mono font-bold text-primary-dark">{item.registration_number}</td>
                                    <td class="px-4 py-3">
                                        {#if item.file_pas_foto_url}
                                            <img src={item.file_pas_foto_url} alt="Foto" class="w-10 h-10 rounded-xl object-cover border shadow-sm" />
                                        {:else}
                                            <div class="w-10 h-10 rounded-xl bg-gray-100 text-gray-400 flex items-center justify-center"><User size={20} /></div>
                                        {/if}
                                    </td>
                                    <td class="px-4 py-3 font-bold text-text-title">{item.nama_lengkap}</td>
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
                                        <div class="inline-flex items-center gap-1">
                                            <button 
                                                onclick={() => openDetail(item)} 
                                                class="p-2 text-primary hover:bg-primary/10 rounded-xl transition-colors font-bold inline-flex items-center gap-1"
                                                title="Lihat Detail & Berkas"
                                            >
                                                <Eye size={16} /> Detail
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
                        <div class="p-3.5 rounded-2xl border {doc.url ? 'border-green-200 bg-green-50/30' : 'border-gray-200 bg-gray-50'} flex flex-col justify-between gap-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-10 h-10 rounded-xl {doc.url ? 'bg-green-100 text-green-600' : 'bg-gray-200 text-gray-400'} flex items-center justify-center font-bold text-xs shrink-0">
                                    {doc.url ? (doc.url.endsWith('.pdf') ? 'PDF' : 'IMG') : '-'}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-text-title truncate">{doc.label}</p>
                                    <p class="text-[10px] font-semibold {doc.url ? 'text-green-600' : 'text-gray-400'}">
                                        {doc.url ? '✓ File Terupload' : 'Tidak terupload'}
                                    </p>
                                </div>
                            </div>

                            {#if doc.url}
                                <div class="flex gap-1.5 pt-2 border-t border-green-200/60">
                                    <button 
                                        type="button" 
                                        onclick={() => openPreviewFile(doc.label, doc.url)}
                                        class="flex-1 py-1.5 px-2 bg-white hover:bg-primary hover:text-white text-primary border border-primary/30 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1 shadow-sm"
                                    >
                                        <Eye size={13} /> Lihat
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
                                <div class="pt-2 text-[11px] text-gray-400 text-center italic border-t border-gray-200/60">Berkas kosong</div>
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
        class="fixed inset-0 z-60 bg-black/85 backdrop-blur-md flex items-center justify-center p-4 sm:p-6 overflow-hidden animate-fade-in"
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
