<script>
    import { onMount } from 'svelte';
    import { Plus, Edit2, Trash2, CheckCircle2 } from 'lucide-svelte';
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
    let formDescription = $state('');
    let formOrder = $state(0);

    let deleteId = $state(null);
    
    let toast = $state({ show: false, message: '' });
    const showToast = (message) => { toast = { show: true, message }; setTimeout(() => toast.show = false, 3000); };

    const fetchProgram = async () => {
        loading = true;
        try {
            const response = await api.get('/v1/admin/program');
            programs = response.data.data;
        } catch (error) {
            console.error(error);
        } finally {
            loading = false;
        }
    };

    onMount(() => fetchProgram());

    const openAddModal = () => {
        isEditing = false;
        formId = null; formName = ''; formIcon = ''; formDescription = ''; formOrder = 0;
        showModal = true;
    };

    const openEditModal = (item) => {
        isEditing = true;
        formId = item.id; formName = item.name; formIcon = item.icon; formDescription = item.description; formOrder = item.order;
        showModal = true;
    };

    const submitForm = async (e) => {
        e.preventDefault();
        submitting = true;
        const payload = { name: formName, icon: formIcon, description: formDescription, order: formOrder };

        try {
            if (isEditing) {
                await api.put(`/v1/admin/program/${formId}`, payload);
                showToast('Program diperbarui');
            } else {
                await api.post('/v1/admin/program', payload);
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
        <Button size="sm" onclick={openAddModal} class="w-full sm:w-auto">
            <Plus size={18} class="mr-2" /> Tambah Program
        </Button>
    </div>

    {#if toast.show}
        <div class="fixed top-24 right-6 z-50 px-4 py-3 bg-white border border-green-100 rounded-xl shadow-lg flex gap-3 animate-fade-left">
            <CheckCircle2 size={20} class="text-green-500" /> {toast.message}
        </div>
    {/if}

    <Card class="overflow-hidden">
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
                            <td class="px-6 py-4 font-medium">{item.name}</td>
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
</div>

<Modal bind:show={showModal} title={isEditing ? 'Edit Program' : 'Tambah Program'}>
    <form onsubmit={submitForm} class="space-y-4">
        <input type="text" bind:value={formName} placeholder="Nama Program" required class="w-full px-4 py-2 border rounded-xl" />
        <input type="text" bind:value={formIcon} placeholder="Icon (lucide)" class="w-full px-4 py-2 border rounded-xl" />
        <input type="number" bind:value={formOrder} placeholder="Urutan (0, 1, 2...)" class="w-full px-4 py-2 border rounded-xl" />
        <textarea bind:value={formDescription} placeholder="Deskripsi" rows="3" class="w-full px-4 py-2 border rounded-xl resize-none"></textarea>
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
