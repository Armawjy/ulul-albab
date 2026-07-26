<?php

namespace App\Services;

use App\Models\PpdbRegistration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppNotificationService
{
    /**
     * Send notification to Admin WhatsApp number when new registration occurs.
     */
    public static function sendAdminNotification(PpdbRegistration $registration): bool
    {
        $adminPhone = config('services.whatsapp.admin_phone', '6285242501959');
        $token = config('services.whatsapp.token', env('FONNTE_TOKEN', ''));
        $apiUrl = config('services.whatsapp.url', 'https://api.fonnte.com/send');

        // Hitung jumlah berkas yang diupload
        $fileCount = 0;
        $files = ['file_kk', 'file_akta', 'file_ijazah', 'file_rapor', 'file_pas_foto', 'file_ktp_ortu'];
        foreach ($files as $field) {
            if (!empty($registration->$field)) {
                $fileCount++;
            }
        }

        $message = "🔔 PENDAFTAR BARU PPDB\n\n"
            . "Nomor Pendaftaran:\n" . $registration->registration_number . "\n\n"
            . "Nama:\n" . $registration->nama_lengkap . "\n\n"
            . "Jenis Kelamin:\n" . $registration->jenis_kelamin . "\n\n"
            . "Asal Sekolah:\n" . $registration->asal_sekolah . "\n\n"
            . "Nama Orang Tua:\n" . $registration->nama_orang_tua . "\n\n"
            . "Nomor WhatsApp:\n" . $registration->nomor_whatsapp_ortu . "\n\n"
            . "Jumlah Berkas:\n" . $fileCount . " File\n\n"
            . "Status:\n" . $registration->status . "\n\n"
            . "Silakan login ke Dashboard Admin untuk melihat data dan seluruh berkas yang telah diupload.";

        try {
            // Jika token tidak ada di env, kita tetap log pesan dan return true agar flow tidak error saat mode development/tanpa token
            if (empty($token)) {
                Log::info("WhatsApp Notification (Simulated to {$adminPhone}):\n{$message}");
                return true;
            }

            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post($apiUrl, [
                'target' => $adminPhone,
                'message' => $message,
                'countryCode' => '62',
            ]);

            if ($response->successful()) {
                Log::info("WhatsApp Notification sent successfully to {$adminPhone} for registration {$registration->registration_number}");
                return true;
            } else {
                Log::error("WhatsApp Notification failed: " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("WhatsApp Notification exception: " . $e->getMessage());
            return false;
        }
    }
}
