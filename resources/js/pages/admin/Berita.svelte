<script>
    import { onMount } from 'svelte';
    import { Plus, Search, Edit2, Trash2, Image as ImageIcon, AlertCircle, CheckCircle2 } from 'lucide-svelte';
    import Card from '../../components/Card.svelte';
    import Button from '../../components/Button.svelte';
    import Modal from '../../components/Modal.svelte';
    import api from '../../services/axios';

    let beritas = $state([]);
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
    let formStatus = $state('published');
    let formContent = $state('');
    let formThumbnail = $state(null); // File object
    let currentThumbnailUrl = $state(null);

    let deleteId = $state(null);
    
    // Toast notification
    let toast = $state({ show: false, message: '', type: 'success' });

    const showToast = (message, type = 'success') => {
        toast = { show: true, message, type };
        setTimeout(() => toast.show = false, 3000);
    };

    const fetchBerita = async (page = 1) => {
        loading = true;
        try {
            const response = await api.get(`/v1/admin/berita?page=${page}`);
            beritas = response.data.data; // Response mapping for resources
            pagination = response.data.meta;
            currentPage = page;
        } catch (error) {
            showToast('Gagal memuat data berita', 'error');
        } finally {
            loading = false;
        }
    };

    onMount(() => {
        fetchBerita();
    });

    const openAddModal = () => {
        isEditing = false;
        formId = null;
        formTitle = '';
        formCategory = '';
        formStatus = 'published';
        formContent = '';
        formThumbnail = null;
        currentThumbnailUrl = null;
        showModal = true;
    };

    const openEditModal = (item) => {
        isEditing = true;
        formId = item.id;
        formTitle = item.title;
        formCategory = item.category;
        formStatus = item.status;
        formContent = item.content;
        formThumbnail = null;
        currentThumbnailUrl = item.thumbnail;
        showModal = true;
    };

    const confirmDelete = (id) => {
        deleteId = id;
        showDeleteModal = true;
    };

    const handleFileChange = (e) => {
        if (e.target.files && e.target.files.length > 0) {
            formThumbnail = e.target.files[0];
            currentThumbnailUrl = URL.createObjectURL(formThumbnail);
        }
    };

    const submitForm = async (e) => {
        e.preventDefault();
        submitting = true;

        const formData = new FormData();
        formData.append('title', formTitle);
        formData.append('category', formCategory);
        formData.append('status', formStatus);
        formData.append('content', formContent);
        if (formThumbnail) {
            formData.append('thumbnail', formThumbnail);
        }

        try {
            if (isEditing) {
                // Laravel convention for PUT with FormData
                formData.append('_method', 'PUT');
                await api.post(`/v1/admin/berita/${formId}`, formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });
                showToast('Berita berhasil diperbarui');
            } else {
                await api.post('/v1/admin/berita', formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });
                showToast('Berita berhasil ditambahkan');
            }
            showModal = false;
            fetchBerita(currentPage);
        } catch (error) {
            showToast(error.response?.data?.message || 'Gagal menyimpan berita', 'error');
        } finally {
            submitting = false;
        }
    };

    const handleDelete = async () => {
        submitting = true;
        try {
            await api.delete(`/v1/admin/berita/${deleteId}`);
            showToast('Berita berhasil dihapus');
            showDeleteModal = false;
            fetchBerita(currentPage);
        } catch (error) {
            showToast('Gagal menghapus berita', 'error');
        } finally {
            submitting = false;
        }
    };
</script>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-text-title font-heading">Manajemen Berita</h1>
            <p class="text-text-body text-sm mt-1">Kelola artikel, pengumuman, dan berita pondok.</p>
        </div>
        <Button size="md" onclick={openAddModal}>
            <Plus size={18} class="mr-2" />
            Tambah Berita
        </Button>
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
        <!-- Toolbar -->
        <div class="p-4 border-b border-border-color flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="relative w-full sm:max-w-xs">
                <Search size={18} class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                <input 
                    type="text" 
                    placeholder="Cari berita..." 
                    class="w-full pl-10 pr-4 py-2 bg-bg-section border border-border-color rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                />
            </div>
            <!-- ... Filters can be added here ... -->
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-bg-section/50 text-text-body text-sm uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold">Judul</th>
                        <th class="px-6 py-4 font-semibold">Kategori</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold">Tanggal</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-color">
                    {#if loading}
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-text-body">
                                <div class="w-6 h-6 border-2 border-primary border-t-transparent rounded-full animate-spin mx-auto"></div>
                            </td>
                        </tr>
                    {:else if beritas.length === 0}
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-text-body text-sm">Tidak ada data berita.</td>
                        </tr>
                    {:else}
                        {#each beritas as item}
                            <tr class="hover:bg-bg-section/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        {#if item.thumbnail}
                                            <img src={item.thumbnail} alt={item.title} class="w-10 h-10 rounded-lg object-cover bg-gray-100 flex-shrink-0" />
                                        {:else}
                                            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                                <ImageIcon size={20} class="text-gray-400" />
                                            </div>
                                        {/if}
                                        <span class="font-medium text-text-title line-clamp-2">{item.title}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-text-body">{item.category || '-'}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {
                                        item.status === 'published' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'
                                    }">
                                        {item.status === 'published' ? 'Dipublikasi' : 'Draft'}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-text-body">{item.created_at?.split(' ')[0]}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit" onclick={() => openEditModal(item)}>
                                            <Edit2 size={18} />
                                        </button>
                                        <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus" onclick={() => confirmDelete(item.id)}>
                                            <Trash2 size={18} />
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
        {#if pagination && pagination.last_page > 1}
            <div class="p-4 border-t border-border-color flex items-center justify-between text-sm text-text-body">
                <span>Menampilkan {pagination.from} - {pagination.to} dari {pagination.total} data</span>
                <div class="flex gap-1">
                    <button 
                        class="px-3 py-1 rounded-lg border border-border-color hover:bg-bg-section disabled:opacity-50" 
                        disabled={currentPage === 1}
                        onclick={() => fetchBerita(currentPage - 1)}
                    >
                        Sebelumnya
                    </button>
                    <span class="px-3 py-1 rounded-lg bg-primary text-white">{currentPage}</span>
                    <button 
                        class="px-3 py-1 rounded-lg border border-border-color hover:bg-bg-section disabled:opacity-50" 
                        disabled={currentPage === pagination.last_page}
                        onclick={() => fetchBerita(currentPage + 1)}
                    >
                        Selanjutnya
                    </button>
                </div>
            </div>
        {/if}
    </Card>
</div>

<!-- Form Modal -->
<Modal bind:show={showModal} title={isEditing ? 'Edit Berita' : 'Tambah Berita'}>
    <form onsubmit={submitForm} class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-text-title mb-1">Judul Berita</label>
            <input type="text" bind:value={formTitle} required class="w-full px-4 py-2 border border-border-color rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary text-text-title" />
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-text-title mb-1">Kategori</label>
                <input type="text" bind:value={formCategory} class="w-full px-4 py-2 border border-border-color rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary text-text-title" placeholder="Misal: Pengumuman" />
            </div>
            <div>
                <label class="block text-sm font-medium text-text-title mb-1">Status</label>
                <select bind:value={formStatus} class="w-full px-4 py-2 border border-border-color rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary text-text-title bg-white">
                    <option value="published">Dipublikasi</option>
                    <option value="draft">Draft</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-text-title mb-1">Gambar Thumbnail</label>
            {#if currentThumbnailUrl}
                <div class="mb-3 relative inline-block">
                    <img src={currentThumbnailUrl} alt="Thumbnail preview" class="h-32 rounded-lg object-cover border border-border-color" />
                </div>
            {/if}
            <input type="file" accept="image/*" onchange={handleFileChange} class="w-full text-sm text-text-body file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-colors" />
        </div>

        <div>
            <label class="block text-sm font-medium text-text-title mb-1">Isi Berita</label>
            <textarea bind:value={formContent} required rows="5" class="w-full px-4 py-2 border border-border-color rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary text-text-title resize-none"></textarea>
        </div>

        <div class="pt-4 flex justify-end gap-3 border-t border-border-color">
            <button type="button" class="px-4 py-2 text-text-body hover:bg-gray-100 rounded-xl transition-colors font-medium" onclick={() => showModal = false}>
                Batal
            </button>
            <Button type="submit" disabled={submitting}>
                {submitting ? 'Menyimpan...' : 'Simpan Berita'}
            </Button>
        </div>
    </form>
</Modal>

<!-- Delete Confirmation Modal -->
<Modal bind:show={showDeleteModal} title="Konfirmasi Hapus">
    <div class="text-text-body mb-6">
        Apakah Anda yakin ingin menghapus berita ini? Tindakan ini tidak dapat dibatalkan.
    </div>
    <div class="flex justify-end gap-3">
        <button class="px-4 py-2 text-text-body hover:bg-gray-100 rounded-xl transition-colors font-medium" onclick={() => showDeleteModal = false}>
            Batal
        </button>
        <button class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl transition-colors font-medium" disabled={submitting} onclick={handleDelete}>
            {submitting ? 'Menghapus...' : 'Ya, Hapus'}
        </button>
    </div>
</Modal>
