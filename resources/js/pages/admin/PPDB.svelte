<script>
    import { onMount } from 'svelte';
    import { Plus, Edit2, Trash2, CheckCircle2 } from 'lucide-svelte';
    import Card from '../../components/Card.svelte';
    import Button from '../../components/Button.svelte';
    import Modal from '../../components/Modal.svelte';
    import api from '../../services/axios';

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
    let toast = $state({ show: false, message: '' });

    const showToast = (message) => { toast = { show: true, message }; setTimeout(() => toast.show = false, 3000); };

    const fetchPPDB = async (page = 1) => {
        loading = true;
        try {
            const response = await api.get(`/v1/admin/ppdb?page=${page}`);
            ppdbs = response.data.data;
            pagination = response.data.meta;
            currentPage = page;
        } finally { loading = false; }
    };

    onMount(() => fetchPPDB());

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

    const handleDelete = async () => {
        submitting = true;
        try {
            await api.delete(`/v1/admin/ppdb/${deleteId}`);
            showToast('Gelombang dihapus');
            showDeleteModal = false;
            fetchPPDB(currentPage);
        } finally { submitting = false; }
    };
</script>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h1 class="text-2xl font-bold font-heading text-text-title">Manajemen PPDB</h1>
        <Button size="sm" onclick={openAddModal} class="w-full sm:w-auto">
            <Plus size={18} class="mr-2" /> Buka Gelombang
        </Button>
    </div>

    {#if toast.show}
        <div class="fixed top-24 right-6 z-50 px-4 py-3 bg-white border border-green-100 rounded-xl shadow-lg flex gap-3 animate-fade-left">
            <CheckCircle2 size={20} class="text-green-500" /> {toast.message}
        </div>
    {/if}

    <Card class="overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-bg-section/50 text-sm uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold">Nama Gelombang</th>
                        <th class="px-6 py-4 font-semibold">Periode</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-color">
                    {#if loading}
                        <tr><td colspan="4" class="px-6 py-8 text-center">Loading...</td></tr>
                    {:else if ppdbs.length === 0}
                        <tr><td colspan="4" class="px-6 py-8 text-center text-sm">Tidak ada data PPDB.</td></tr>
                    {:else}
                        {#each ppdbs as item}
                            <tr class="hover:bg-bg-section/30">
                                <td class="px-6 py-4 font-medium">{item.batch_name}</td>
                                <td class="px-6 py-4 text-sm">{item.start_date} s/d {item.end_date}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {item.status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}">
                                        {item.status ? 'Buka' : 'Tutup'}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg" onclick={() => openEditModal(item)}><Edit2 size={18} /></button>
                                    <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg" onclick={() => { deleteId = item.id; showDeleteModal = true; }}><Trash2 size={18} /></button>
                                </td>
                            </tr>
                        {/each}
                    {/if}
                </tbody>
            </table>
        </div>
    </Card>
</div>

<Modal bind:show={showModal} title={isEditing ? 'Edit PPDB' : 'Tambah PPDB'}>
    <form onsubmit={submitForm} class="space-y-4">
        <input type="text" bind:value={formBatch} placeholder="Nama Gelombang (Misal: Gelombang 1 2024)" required class="w-full px-4 py-2 border rounded-xl" />
        <div class="grid grid-cols-2 gap-4">
            <input type="date" bind:value={formStartDate} required class="w-full px-4 py-2 border rounded-xl" />
            <input type="date" bind:value={formEndDate} required class="w-full px-4 py-2 border rounded-xl" />
        </div>
        <input type="number" bind:value={formFee} placeholder="Biaya Pendaftaran (Rp)" required class="w-full px-4 py-2 border rounded-xl" />
        <textarea bind:value={formReqs} placeholder="Persyaratan" rows="3" class="w-full px-4 py-2 border rounded-xl resize-none"></textarea>
        
        <label class="flex items-center gap-2 text-sm font-medium">
            <input type="checkbox" bind:checked={formStatus} class="w-4 h-4 rounded text-primary focus:ring-primary" />
            Buka Pendaftaran (Aktif)
        </label>

        <div class="pt-4 flex justify-end gap-3">
            <button type="button" class="px-4 py-2 hover:bg-gray-100 rounded-xl" onclick={() => showModal = false}>Batal</button>
            <Button type="submit" disabled={submitting}>{submitting ? 'Menyimpan...' : 'Simpan'}</Button>
        </div>
    </form>
</Modal>

<Modal bind:show={showDeleteModal} title="Konfirmasi Hapus">
    <div class="mb-6">Hapus gelombang ini?</div>
    <div class="flex justify-end gap-3">
        <button class="px-4 py-2 hover:bg-gray-100 rounded-xl" onclick={() => showDeleteModal = false}>Batal</button>
        <button class="px-4 py-2 bg-red-600 text-white rounded-xl" onclick={handleDelete}>Hapus</button>
    </div>
</Modal>
