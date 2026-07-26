<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\PpdbRegistration;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PpdbRegistrationController extends Controller
{
    use ApiResponse;

    /**
     * Get paginated list of PPDB registrations with search, filter, and sorting.
     */
    public function index(Request $request): JsonResponse
    {
        $query = PpdbRegistration::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('registration_number', 'like', "%{$search}%")
                  ->orWhere('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('asal_sekolah', 'like', "%{$search}%")
                  ->orWhere('nama_orang_tua', 'like', "%{$search}%")
                  ->orWhere('nomor_whatsapp_ortu', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status', $request->status);
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $allowedSorts = ['created_at', 'nama_lengkap', 'registration_number', 'status', 'asal_sekolah'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $registrations = $query->paginate($request->input('per_page', 10));

        // Add full storage url to each item
        $registrations->getCollection()->transform(function ($item) {
            return $this->appendFileUrls($item);
        });

        return $this->success([
            'data' => $registrations->items(),
            'meta' => [
                'current_page' => $registrations->currentPage(),
                'last_page' => $registrations->lastPage(),
                'per_page' => $registrations->perPage(),
                'total' => $registrations->total(),
            ]
        ], 'Data pendaftar berhasil diambil');
    }

    /**
     * Get all registrations (unpaginated) for Export Excel / PDF / Print.
     */
    public function export(Request $request): JsonResponse
    {
        $query = PpdbRegistration::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('registration_number', 'like', "%{$search}%")
                  ->orWhere('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('asal_sekolah', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status', $request->status);
        }

        $query->orderBy('created_at', 'desc');
        $items = $query->get()->map(function ($item) {
            return $this->appendFileUrls($item);
        });

        return $this->success($items, 'Data eksport berhasil diambil');
    }

    /**
     * Show detail of a registration.
     */
    public function show($id): JsonResponse
    {
        $registration = PpdbRegistration::find($id);
        if (!$registration) {
            return $this->error('Data pendaftar tidak ditemukan', 404);
        }

        return $this->success($this->appendFileUrls($registration), 'Detail pendaftar berhasil diambil');
    }

    /**
     * Update status (Verifikasi, Terima, Tolak).
     */
    public function updateStatus(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Menunggu,Diverifikasi,Diterima,Ditolak',
        ]);

        if ($validator->fails()) {
            return $this->error('Validasi gagal', 422, $validator->errors());
        }

        $registration = PpdbRegistration::find($id);
        if (!$registration) {
            return $this->error('Data pendaftar tidak ditemukan', 404);
        }

        $registration->update(['status' => $request->status]);

        return $this->success($this->appendFileUrls($registration), "Status pendaftar berhasil diubah menjadi {$request->status}");
    }

    /**
     * Delete a registration and its files.
     */
    public function destroy($id): JsonResponse
    {
        $registration = PpdbRegistration::find($id);
        if (!$registration) {
            return $this->error('Data pendaftar tidak ditemukan', 404);
        }

        $fileFields = ['file_kk', 'file_akta', 'file_ijazah', 'file_rapor', 'file_pas_foto', 'file_ktp_ortu'];
        foreach ($fileFields as $field) {
            if ($registration->$field && Storage::disk('public')->exists($registration->$field)) {
                Storage::disk('public')->delete($registration->$field);
            }
        }

        $registration->delete();

        return $this->success(null, 'Data pendaftar dan berkas berhasil dihapus');
    }

    /**
     * Helper to append full URLs for file preview and download.
     */
    private function appendFileUrls(PpdbRegistration $item): PpdbRegistration
    {
        $fileFields = ['file_kk', 'file_akta', 'file_ijazah', 'file_rapor', 'file_pas_foto', 'file_ktp_ortu'];
        foreach ($fileFields as $field) {
            $urlField = $field . '_url';
            $item->$urlField = $item->$field ? asset('storage/' . $item->$field) : null;
        }
        return $item;
    }
}
