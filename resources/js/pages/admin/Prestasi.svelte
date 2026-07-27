<script>
    import { onMount } from 'svelte';
    import { Plus, Search, Edit2, Trash2, Image as ImageIcon, AlertCircle, CheckCircle2 } from 'lucide-svelte';
    import Card from '../../components/Card.svelte';
    import Button from '../../components/Button.svelte';
    import Modal from '../../components/Modal.svelte';
    import api from '../../services/axios';

    let prestasis = $state([]);
    let loading = $state(true);
    let pagination = $state(null);
    let currentPage = $state(1);

    // Modal states
    let showModal = $state(false);
    let showDeleteModal = $state(false);
    let isEditing = $state(false);
    let submitting = $state(false);

    // Form states
    let formId = $state(null);
    let formTitle = $state('');
    let formLevel = $state('');
    let formRank = $state('');
    let formWinner = $state('');
    let formYear = $state('');
    let formImage = $state(null);
    let currentImageUrl = $state(null);

    let deleteId = $state(null);
    
    let toast = $state({ show: false, message: '', type: 'success' });
    const showToast = (message, type = 'success') => {
        toast = { show: true, message, type };
        setTimeout(() => toast.show = false, 3000);
    };

    let searchQuery = $state('');

    const fetchPrestasi = async (page = 1) => {
        loading = true;
        try {
            const params = new URLSearchParams({ page: page });
            if (searchQuery) params.append('search', searchQuery);
            const response = await api.get(`/v1/admin/prestasi?${params.toString()}`);
            prestasis = response.data.data;
            pagination = response.data.meta;
            currentPage = page;
        } catch (error) {
            showToast('Gagal memuat data prestasi', 'error');
        } finally {
            loading = false;
        }
    };

    const handleSearch = (e) => {
        if (e) e.preventDefault();
        fetchPrestasi(1);
    };

    onMount(() => {
        fetchPrestasi();
    });

    const openAddModal = () => {
        isEditing = false;
        formId = null;
        formTitle = '';
        formLevel = '';
        formRank = '';
        formWinner = '';
        formYear = new Date().getFullYear().toString();
        formImage = null;
        currentImageUrl = null;
        showModal = true;
    };

    const openEditModal = (item) => {
        isEditing = true;
        formId = item.id;
        formTitle = item.title;
        formLevel = item.level;
        formRank = item.rank;
        formWinner = item.winner_name;
        formYear = item.year;
        formImage = null;
        currentImageUrl = item.image;
        showModal = true;
    };

    const confirmDelete = (id) => {
        deleteId = id;
        showDeleteModal = true;
    };

    const handleFileChange = (e) => {
        if (e.target.files && e.target.files.length > 0) {
            formImage = e.target.files[0];
            currentImageUrl = URL.createObjectURL(formImage);
        }
    };

    const submitForm = async (e) => {
        e.preventDefault();
        submitting = true;

        const formData = new FormData();
        formData.append('title', formTitle);
        if (formLevel) formData.append('level', formLevel);
        if (formRank) formData.append('rank', formRank);
        if (formWinner) formData.append('winner_name', formWinner);
        if (formYear) formData.append('year', formYear);
        if (formImage) formData.append('image', formImage);

        try {
            if (isEditing) {
                formData.append('_method', 'PUT');
                await api.post(`/v1/admin/prestasi/${formId}`, formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });
                showToast('Prestasi berhasil diperbarui');
            } else {
                await api.post('/v1/admin/prestasi', formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });
                showToast('Prestasi berhasil ditambahkan');
            }
            showModal = false;
            fetchPrestasi(currentPage);
        } catch (error) {
            showToast(error.response?.data?.message || 'Gagal menyimpan prestasi', 'error');
        } finally {
            submitting = false;
        }
    };

    const handleDelete = async () => {
        submitting = true;
        try {
            await api.delete(`/v1/admin/prestasi/${deleteId}`);
            showToast('Prestasi berhasil dihapus');
            showDeleteModal = false;
            fetchPrestasi(currentPage);
        } catch (error) {
            showToast('Gagal menghapus prestasi', 'error');
        } finally {
            submitting = false;
        }
    };
</script>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-text-title font-heading">Prestasi Santri</h1>
            <p class="text-text-body text-sm mt-1">Kelola daftar pencapaian dan prestasi santri.</p>
        </div>
        <div class="hidden sm:block">
            <Button size="md" onclick={openAddModal}>
                <Plus size={18} class="mr-2" />
                Tambah Prestasi
            </Button>
        </div>
    </div>

    {#if toast.show}
        <div class="fixed top-24 right-6 z-50 flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg bg-white border border-green-100 animate-fade-left">
            <CheckCircle2 size={20} class="text-green-500" />
            <span class="text-sm font-medium text-text-title">{toast.message}</span>
        </div>
    {/if}

    <Card class="overflow-hidden">
        <div class="p-4 border-b border-border-color flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <form onsubmit={handleSearch} class="flex items-center gap-2 w-full sm:max-w-md">
                <div class="relative flex-1">
                    <Search size={18} class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                    <input 
                        type="text" 
                        bind:value={searchQuery}
                        placeholder="Cari prestasi..." 
                        class="w-full pl-10 pr-4 py-2 bg-bg-section border border-border-color rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                    />
                </div>
                <Button type="submit" size="sm">Cari</Button>
                {#if searchQuery}
                    <button type="button" onclick={() => { searchQuery = ''; fetchPrestasi(1); }} class="px-3 py-2 text-xs text-gray-500 hover:text-red-500 font-bold">Reset</button>
                {/if}
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-bg-section/50 text-text-body text-sm uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold">Nama Prestasi</th>
                        <th class="px-6 py-4 font-semibold">Tingkat</th>
                        <th class="px-6 py-4 font-semibold">Tahun</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-color">
                    {#if loading}
                        <tr><td colspan="4" class="px-6 py-8 text-center"><div class="w-6 h-6 border-2 border-primary border-t-transparent rounded-full animate-spin mx-auto"></div></td></tr>
                    {:else if prestasis.length === 0}
                        <tr><td colspan="4" class="px-6 py-8 text-center text-text-body text-sm">Belum ada data prestasi.</td></tr>
                    {:else}
                        {#each prestasis as item}
                            <tr class="hover:bg-bg-section/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        {#if item.image}
                                            <img src={item.image} alt={item.title} class="w-10 h-10 rounded-lg object-cover bg-gray-100 flex-shrink-0" />
                                        {:else}
                                            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0"><ImageIcon size={20} class="text-gray-400" /></div>
                                        {/if}
                                        <div>
                                            <div class="font-medium text-text-title">{item.title}</div>
                                            <div class="text-xs text-text-body">Juara {item.rank} - {item.winner_name}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-text-body">{item.level || '-'}</td>
                                <td class="px-6 py-4 text-sm text-text-body">{item.year || '-'}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" onclick={() => openEditModal(item)}><Edit2 size={18} /></button>
                                        <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" onclick={() => confirmDelete(item.id)}><Trash2 size={18} /></button>
                                    </div>
                                </td>
                            </tr>
                        {/each}
                    {/if}
                </tbody>
            </table>
        <!-- Pagination -->
        {#if pagination && pagination.last_page > 1}
            <div class="p-4 border-t border-border-color flex items-center justify-between text-sm text-text-body">
                <span>Menampilkan {pagination.from} - {pagination.to} dari {pagination.total}</span>
                <div class="flex gap-1">
                    <button class="px-3 py-1 rounded-lg border border-border-color hover:bg-bg-section disabled:opacity-50" disabled={currentPage === 1} onclick={() => fetchPrestasi(currentPage - 1)}>Sebelumnya</button>
                    <span class="px-3 py-1 rounded-lg bg-primary text-white">{currentPage}</span>
                    <button class="px-3 py-1 rounded-lg border border-border-color hover:bg-bg-section disabled:opacity-50" disabled={currentPage === pagination.last_page} onclick={() => fetchPrestasi(currentPage + 1)}>Selanjutnya</button>
                </div>
            </div>
        {/if}
    </Card>

    <div class="sm:hidden mt-4">
        <Button size="md" class="w-full justify-center shadow-md" onclick={openAddModal}>
            <Plus size={18} class="mr-2" />
            Tambah Prestasi
        </Button>
    </div>
</div>

<!-- Form Modal -->
<Modal bind:show={showModal} title={isEditing ? 'Edit Prestasi' : 'Tambah Prestasi'}>
    <form onsubmit={submitForm} class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-text-title mb-1">Nama Prestasi / Lomba</label>
            <input type="text" bind:value={formTitle} required class="w-full px-4 py-2 border border-border-color rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary text-text-title" />
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-text-title mb-1">Tingkat (Misal: Nasional)</label>
                <input type="text" bind:value={formLevel} class="w-full px-4 py-2 border border-border-color rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary text-text-title" />
            </div>
            <div>
                <label class="block text-sm font-medium text-text-title mb-1">Peringkat (Misal: 1, 2, Harapan)</label>
                <input type="text" bind:value={formRank} class="w-full px-4 py-2 border border-border-color rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary text-text-title" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-text-title mb-1">Nama Pemenang / Tim</label>
                <input type="text" bind:value={formWinner} class="w-full px-4 py-2 border border-border-color rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary text-text-title" />
            </div>
            <div>
                <label class="block text-sm font-medium text-text-title mb-1">Tahun</label>
                <input type="text" bind:value={formYear} class="w-full px-4 py-2 border border-border-color rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary text-text-title" />
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-text-title mb-1">Foto Bukti / Piala</label>
            {#if currentImageUrl}
                <div class="mb-3"><img src={currentImageUrl} class="h-32 rounded-lg object-cover border border-border-color" /></div>
            {/if}
            <input type="file" accept="image/*" onchange={handleFileChange} class="w-full text-sm text-text-body file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-colors" />
        </div>

        <div class="pt-4 flex justify-end gap-3 border-t border-border-color">
            <button type="button" class="px-4 py-2 text-text-body hover:bg-gray-100 rounded-xl transition-colors font-medium" onclick={() => showModal = false}>Batal</button>
            <Button type="submit" disabled={submitting}>{submitting ? 'Menyimpan...' : 'Simpan'}</Button>
        </div>
    </form>
</Modal>

<!-- Delete Modal -->
<Modal bind:show={showDeleteModal} title="Konfirmasi Hapus">
    <div class="text-text-body mb-6">Yakin ingin menghapus data prestasi ini?</div>
    <div class="flex justify-end gap-3">
        <button class="px-4 py-2 text-text-body hover:bg-gray-100 rounded-xl" onclick={() => showDeleteModal = false}>Batal</button>
        <button class="px-4 py-2 bg-red-600 text-white rounded-xl" disabled={submitting} onclick={handleDelete}>Hapus</button>
    </div>
</Modal>
