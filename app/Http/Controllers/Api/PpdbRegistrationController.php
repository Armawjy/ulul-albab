<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PpdbRegistration;
use App\Services\WhatsAppNotificationService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PpdbRegistrationController extends Controller
{
    use ApiResponse;

    /**
     * Store a new PPDB registration with files.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string|max:50',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'alamat_lengkap' => 'required|string',
            'asal_sekolah' => 'required|string|max:255',
            'nama_orang_tua' => 'required|string|max:255',
            'nomor_whatsapp_ortu' => 'required|string|max:50',
            'catatan' => 'nullable|string',
            'file_kk' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:2048',
            'file_akta' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:2048',
            'file_ijazah' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:2048',
            'file_rapor' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:2048',
            'file_pas_foto' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:2048',
            'file_ktp_ortu' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:2048',
        ], [
            'required' => 'Kolom :attribute wajib diisi.',
            'mimes' => 'Format file :attribute harus berupa PDF, PNG, JPG, atau JPEG.',
            'max' => 'Ukuran file :attribute maksimal 2 MB.',
        ]);

        if ($validator->fails()) {
            return $this->error('Validasi gagal', 422, $validator->errors());
        }

        DB::beginTransaction();
        try {
            $data = $validator->validated();
            $data['registration_number'] = PpdbRegistration::generateRegistrationNumber();
            $data['status'] = 'Menunggu';

            // Upload files to storage/app/public/ppdb
            $fileFields = ['file_kk', 'file_akta', 'file_ijazah', 'file_rapor', 'file_pas_foto', 'file_ktp_ortu'];
            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $path = $file->store('ppdb', 'public');
                    $data[$field] = $path;
                }
            }

            $registration = PpdbRegistration::create($data);

            DB::commit();

            // Send WhatsApp notification in background without breaking if it fails
            try {
                WhatsAppNotificationService::sendAdminNotification($registration);
            } catch (\Exception $e) {
                Log::error('WA Notification Error: ' . $e->getMessage());
            }

            return $this->success(
                $registration, 
                "Pendaftaran berhasil dikirim! Nomor Pendaftaran Anda: {$registration->registration_number}", 
                201
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PPDB Store Error: ' . $e->getMessage());
            return $this->error('Terjadi kesalahan saat menyimpan data pendaftaran.', 500);
        }
    }
}
