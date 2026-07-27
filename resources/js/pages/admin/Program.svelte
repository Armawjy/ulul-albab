<script>
    import { onMount } from 'svelte';
    import { Plus, Search, Edit2, Trash2, CheckCircle2 } from 'lucide-svelte';
    import Card from '../../components/Card.svelte';
    import Button from '../../components/Button.svelte';
    import Modal from '../../components/Modal.svelte';
    import api from '../../services/axios';

    let programs = $state([]);
    let loading = $state(true);

    let showModal = $state(false);
    let showDeleteModal = $state(false);
    let isEditing = $state(false);
    let submitting = $state(false);

    let formId = $state(null);
    let formName = $state('');
    let formIcon = $state('');
    let formIconFile = $state(null);
    let currentIconUrl = $state(null);
    let formDescription = $state('');
    let formOrder = $state(0);

    let deleteId = $state(null);
    
    let toast = $state({ show: false, message: '' });
    const showToast = (message) => { toast = { show: true, message }; setTimeout(() => toast.show = false, 3000); };

    let searchQuery = $state('');

    const fetchProgram = async () => {
        loading = true;
        try {
            const params = new URLSearchParams();
            if (searchQuery) params.append('search', searchQuery);
            const response = await api.get(`/v1/admin/program?${params.toString()}`);
            programs = response.data.data;
        } catch (error) {
            console.error(error);
        } finally {
            loading = false;
        }
    };

    const handleSearch = (e) => {
        if (e) e.preventDefault();
        fetchProgram();
    };

    onMount(() => fetchProgram());

    const openAddModal = () => {
        isEditing = false;
        formId = null; formName = ''; formIcon = ''; formDescription = ''; formOrder = 0;
        formIconFile = null; currentIconUrl = null;
        showModal = true;
    };

    const openEditModal = (item) => {
        isEditing = true;
        formId = item.id; formName = item.name; formIcon = item.icon; formDescription = item.description; formOrder = item.order;
        formIconFile = null; currentIconUrl = item.icon;
        showModal = true;
    };

    const handleFileChange = (e) => {
        if (e.target.files && e.target.files.length > 0) {
            formIconFile = e.target.files[0];
            currentIconUrl = URL.createObjectURL(formIconFile);
        }
    };

    const submitForm = async (e) => {
        e.preventDefault();
        submitting = true;
        const formData = new FormData();
        formData.append('name', formName);
        if (formIconFile) {
            formData.append('icon', formIconFile);
        } else if (formIcon) {
            formData.append('icon', formIcon);
        }
        formData.append('order', formOrder);
        if (formDescription) formData.append('description', formDescription);

        try {
            if (isEditing) {
                formData.append('_method', 'PUT');
                await api.post(`/v1/admin/program/${formId}`, formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });
                showToast('Program diperbarui');
            } else {
                await api.post('/v1/admin/program', formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });
                showToast('Program ditambahkan');
            }
            showModal = false;
            fetchProgram();
        } catch (error) {
            console.error(error);
        } finally {
            submitting = false;
        }
    };

    const handleDelete = async () => {
        submitting = true;
        try {
            await api.delete(`/v1/admin/program/${deleteId}`);
            showToast('Program dihapus');
            showDeleteModal = false;
            fetchProgram();
        } finally { submitting = false; }
    };
</script>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h1 class="text-2xl font-bold font-heading text-text-title">Program Pendidikan</h1>
        <div class="hidden sm:block">
            <Button size="sm" onclick={openAddModal}>
                <Plus size={18} class="mr-2" /> Tambah Program
            </Button>
        </div>
    </div>

    {#if toast.show}
        <div class="fixed top-24 right-6 z-50 px-4 py-3 bg-white border border-green-100 rounded-xl shadow-lg flex gap-3 animate-fade-left">
            <CheckCircle2 size={20} class="text-green-500" /> {toast.message}
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
                        placeholder="Cari program..." 
                        class="w-full pl-10 pr-4 py-2 bg-bg-section border border-border-color rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                    />
                </div>
                <Button type="submit" size="sm">Cari</Button>
                {#if searchQuery}
                    <button type="button" onclick={() => { searchQuery = ''; fetchProgram(); }} class="px-3 py-2 text-xs text-gray-500 hover:text-red-500 font-bold">Reset</button>
                {/if}
            </form>
        </div>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-bg-section/50 text-sm uppercase tracking-wider">
                    <th class="px-6 py-4 font-semibold">Nama Program</th>
                    <th class="px-6 py-4 font-semibold">Urutan</th>
                    <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-color">
                {#if loading}
                    <tr><td colspan="3" class="px-6 py-8 text-center">Loading...</td></tr>
                {:else if programs.length === 0}
                    <tr><td colspan="3" class="px-6 py-8 text-center text-sm">Tidak ada program.</td></tr>
                {:else}
                    {#each programs as item}
                        <tr class="hover:bg-bg-section/30">
                            <td class="px-6 py-4 font-medium">
                                <div class="flex items-center gap-3">
                                    {#if item.icon && (item.icon.startsWith('http') || item.icon.includes('/') || item.icon.includes('.'))}
                                        <img src={item.icon} alt={item.name} class="w-10 h-10 rounded-xl object-cover bg-gray-100 flex-shrink-0 border border-border-color" />
                                    {:else}
                                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0 text-primary font-bold text-xs">
                                            {item.icon || 'Star'}
                                        </div>
                                    {/if}
                                    <span>{item.name}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">{item.order}</td>
                            <td class="px-6 py-4 text-right">
                                <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg" onclick={() => openEditModal(item)}><Edit2 size={18} /></button>
                                <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg" onclick={() => { deleteId = item.id; showDeleteModal = true; }}><Trash2 size={18} /></button>
                            </td>
                        </tr>
                    {/each}
                {/if}
            </tbody>
        </table>
    </Card>

    <div class="sm:hidden mt-4">
        <Button size="md" class="w-full justify-center shadow-md" onclick={openAddModal}>
            <Plus size={18} class="mr-2" />
            Tambah Program
        </Button>
    </div>
</div>

<Modal bind:show={showModal} title={isEditing ? 'Edit Program' : 'Tambah Program'}>
    <form onsubmit={submitForm} class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-text-title mb-1">Nama Program</label>
            <input type="text" bind:value={formName} placeholder="Misal: Madrasah Aliyah Unggulan" required class="w-full px-4 py-2 border rounded-xl" />
        </div>

        <div>
            <label class="block text-sm font-medium text-text-title mb-1">Gambar / Foto Program</label>
            {#if currentIconUrl}
                <div class="mb-3 flex items-center gap-3">
                    {#if currentIconUrl.startsWith('http') || currentIconUrl.includes('/') || currentIconUrl.includes('blob:') || currentIconUrl.includes('.')}
                        <img src={currentIconUrl} alt="Preview" class="w-20 h-20 rounded-xl object-cover border border-border-color shadow-sm" />
                    {:else}
                        <span class="px-3 py-1 bg-gray-100 rounded-lg text-sm font-semibold">{currentIconUrl}</span>
                    {/if}
                </div>
            {/if}
            <input type="file" accept="image/*" onchange={handleFileChange} class="w-full text-sm text-text-body file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-colors" />
            <div class="mt-2 flex items-center gap-2">
                <span class="text-xs text-gray-400">Atau ketik nama icon Lucide:</span>
                <input type="text" bind:value={formIcon} placeholder="Misal: Book, Globe, Microscope..." class="flex-1 px-3 py-1.5 text-xs border rounded-lg focus:outline-none focus:border-primary" />
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-text-title mb-1">Urutan</label>
            <input type="number" bind:value={formOrder} placeholder="Urutan (0, 1, 2...)" class="w-full px-4 py-2 border rounded-xl" />
        </div>

        <div>
            <label class="block text-sm font-medium text-text-title mb-1">Deskripsi</label>
            <textarea bind:value={formDescription} placeholder="Deskripsi program..." rows="3" class="w-full px-4 py-2 border rounded-xl resize-none"></textarea>
        </div>
        <div class="pt-4 flex justify-end gap-3">
            <button type="button" class="px-4 py-2 hover:bg-gray-100 rounded-xl" onclick={() => showModal = false}>Batal</button>
            <Button type="submit" disabled={submitting}>{submitting ? 'Menyimpan...' : 'Simpan'}</Button>
        </div>
    </form>
</Modal>

<Modal bind:show={showDeleteModal} title="Konfirmasi Hapus">
    <div class="mb-6">Hapus program ini?</div>
    <div class="flex justify-end gap-3">
        <button class="px-4 py-2 hover:bg-gray-100 rounded-xl" onclick={() => showDeleteModal = false}>Batal</button>
        <button class="px-4 py-2 bg-red-600 text-white rounded-xl" onclick={handleDelete}>Hapus</button>
    </div>
</Modal>
