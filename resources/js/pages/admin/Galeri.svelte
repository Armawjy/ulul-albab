<script>
    import { onMount } from 'svelte';
    import { Plus, Search, Edit2, Trash2, Image as ImageIcon, AlertCircle, CheckCircle2 } from 'lucide-svelte';
    import Card from '../../components/Card.svelte';
    import Button from '../../components/Button.svelte';
    import Modal from '../../components/Modal.svelte';
    import api from '../../services/axios';

    let galeris = $state([]);
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
    let formCategory = $state('');
    let formDescription = $state('');
    let formImage = $state(null);
    let currentImageUrl = $state(null);

    let deleteId = $state(null);
    
    // Toast
    let toast = $state({ show: false, message: '', type: 'success' });
    const showToast = (message, type = 'success') => {
        toast = { show: true, message, type };
        setTimeout(() => toast.show = false, 3000);
    };

    let searchQuery = $state('');

    const fetchGaleri = async (page = 1) => {
        loading = true;
        try {
            const params = new URLSearchParams({ page: page });
            if (searchQuery) params.append('search', searchQuery);
            const response = await api.get(`/v1/admin/galeri?${params.toString()}`);
            galeris = response.data.data;
            pagination = response.data.meta;
            currentPage = page;
        } catch (error) {
            showToast('Gagal memuat data galeri', 'error');
        } finally {
            loading = false;
        }
    };

    const handleSearch = (e) => {
        if (e) e.preventDefault();
        fetchGaleri(1);
    };

    onMount(() => {
        fetchGaleri();
    });

    const openAddModal = () => {
        isEditing = false;
        formId = null;
        formTitle = '';
        formCategory = '';
        formDescription = '';
        formImage = null;
        currentImageUrl = null;
        showModal = true;
    };

    const openEditModal = (item) => {
        isEditing = true;
        formId = item.id;
        formTitle = item.title;
        formCategory = item.category;
        formDescription = item.description;
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
        if (formCategory) formData.append('category', formCategory);
        if (formDescription) formData.append('description', formDescription);
        if (formImage) formData.append('image', formImage);

        try {
            if (isEditing) {
                formData.append('_method', 'PUT');
                await api.post(`/v1/admin/galeri/${formId}`, formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });
                showToast('Foto berhasil diperbarui');
            } else {
                await api.post('/v1/admin/galeri', formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });
                showToast('Foto berhasil ditambahkan ke galeri');
            }
            showModal = false;
            fetchGaleri(currentPage);
        } catch (error) {
            showToast(error.response?.data?.message || 'Gagal menyimpan foto', 'error');
        } finally {
            submitting = false;
        }
    };

    const handleDelete = async () => {
        submitting = true;
        try {
            await api.delete(`/v1/admin/galeri/${deleteId}`);
            showToast('Foto berhasil dihapus dari galeri');
            showDeleteModal = false;
            fetchGaleri(currentPage);
        } catch (error) {
            showToast('Gagal menghapus foto', 'error');
        } finally {
            submitting = false;
        }
    };
</script>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-text-title font-heading">Galeri Foto</h1>
            <p class="text-text-body text-sm mt-1">Kelola album foto kegiatan pondok.</p>
        </div>
        <div class="hidden sm:block">
            <Button size="md" onclick={openAddModal}>
                <Plus size={18} class="mr-2" />
                Tambah Foto
            </Button>
        </div>
    </div>

    <!-- Toast -->
    {#if toast.show}
        <div class="fixed top-24 right-6 z-50 flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg bg-white border {toast.type === 'error' ? 'border-red-100' : 'border-green-100'} animate-fade-left">
            {#if toast.type === 'error'}
                <AlertCircle size={20} class="text-red-500" />
            {:else}
                <CheckCircle2 size={20} class="text-green-500" />
            {/if}
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
                        placeholder="Cari foto..." 
                        class="w-full pl-10 pr-4 py-2 bg-bg-section border border-border-color rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                    />
                </div>
                <Button type="submit" size="sm">Cari</Button>
                {#if searchQuery}
                    <button type="button" onclick={() => { searchQuery = ''; fetchGaleri(1); }} class="px-3 py-2 text-xs text-gray-500 hover:text-red-500 font-bold">Reset</button>
                {/if}
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-bg-section/50 text-text-body text-sm uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold">Foto & Judul</th>
                        <th class="px-6 py-4 font-semibold">Kategori</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-color">
                    {#if loading}
                        <tr><td colspan="3" class="px-6 py-8 text-center"><div class="w-6 h-6 border-2 border-primary border-t-transparent rounded-full animate-spin mx-auto"></div></td></tr>
                    {:else if galeris.length === 0}
                        <tr><td colspan="3" class="px-6 py-8 text-center text-text-body text-sm">Tidak ada foto di galeri.</td></tr>
                    {:else}
                        {#each galeris as item}
                            <tr class="hover:bg-bg-section/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        {#if item.image}
                                            <img src={item.image} alt={item.title} class="w-16 h-12 rounded-lg object-cover bg-gray-100 flex-shrink-0" />
                                        {:else}
                                            <div class="w-16 h-12 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0"><ImageIcon size={20} class="text-gray-400" /></div>
                                        {/if}
                                        <span class="font-medium text-text-title">{item.title}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-text-body">{item.category || '-'}</td>
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
        </div>
        
        <!-- Pagination -->
        {#if pagination && pagination.last_page > 1}
            <div class="p-4 border-t border-border-color flex items-center justify-between text-sm text-text-body">
                <span>Menampilkan {pagination.from} - {pagination.to} dari {pagination.total}</span>
                <div class="flex gap-1">
                    <button class="px-3 py-1 rounded-lg border border-border-color hover:bg-bg-section disabled:opacity-50" disabled={currentPage === 1} onclick={() => fetchGaleri(currentPage - 1)}>Sebelumnya</button>
                    <span class="px-3 py-1 rounded-lg bg-primary text-white">{currentPage}</span>
                    <button class="px-3 py-1 rounded-lg border border-border-color hover:bg-bg-section disabled:opacity-50" disabled={currentPage === pagination.last_page} onclick={() => fetchGaleri(currentPage + 1)}>Selanjutnya</button>
                </div>
            </div>
        {/if}
    </Card>

    <div class="sm:hidden mt-4">
        <Button size="md" class="w-full justify-center shadow-md" onclick={openAddModal}>
            <Plus size={18} class="mr-2" />
            Tambah Foto
        </Button>
    </div>
</div>

<!-- Form Modal -->
<Modal bind:show={showModal} title={isEditing ? 'Edit Foto Galeri' : 'Upload Foto Baru'}>
    <form onsubmit={submitForm} class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-text-title mb-1">Judul Foto</label>
            <input type="text" bind:value={formTitle} required class="w-full px-4 py-2 border border-border-color rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary text-text-title" placeholder="Misal: Kegiatan Santri Baru" />
        </div>
        
        <div>
            <label class="block text-sm font-medium text-text-title mb-1">Kategori</label>
            <input type="text" bind:value={formCategory} class="w-full px-4 py-2 border border-border-color rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary text-text-title" placeholder="Misal: Ekstrakurikuler" />
        </div>

        <div>
            <label class="block text-sm font-medium text-text-title mb-1">File Gambar (Wajib)</label>
            {#if currentImageUrl}
                <div class="mb-3"><img src={currentImageUrl} alt="Preview" class="h-32 rounded-lg object-cover border border-border-color" /></div>
            {/if}
            <input type="file" accept="image/*" onchange={handleFileChange} required={!isEditing} class="w-full text-sm text-text-body file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-colors" />
        </div>

        <div>
            <label class="block text-sm font-medium text-text-title mb-1">Deskripsi Singkat (Opsional)</label>
            <textarea bind:value={formDescription} rows="2" class="w-full px-4 py-2 border border-border-color rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary text-text-title resize-none"></textarea>
        </div>

        <div class="pt-4 flex justify-end gap-3 border-t border-border-color">
            <button type="button" class="px-4 py-2 text-text-body hover:bg-gray-100 rounded-xl transition-colors font-medium" onclick={() => showModal = false}>Batal</button>
            <Button type="submit" disabled={submitting}>{submitting ? 'Mengupload...' : 'Simpan Foto'}</Button>
        </div>
    </form>
</Modal>

<!-- Delete Modal -->
<Modal bind:show={showDeleteModal} title="Konfirmasi Hapus">
    <div class="text-text-body mb-6">Apakah Anda yakin ingin menghapus foto galeri ini?</div>
    <div class="flex justify-end gap-3">
        <button class="px-4 py-2 text-text-body hover:bg-gray-100 rounded-xl transition-colors font-medium" onclick={() => showDeleteModal = false}>Batal</button>
        <button class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl transition-colors font-medium" disabled={submitting} onclick={handleDelete}>Ya, Hapus</button>
    </div>
</Modal>
