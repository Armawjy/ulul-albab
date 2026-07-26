<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PpdbRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_number',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat_lengkap',
        'asal_sekolah',
        'nama_orang_tua',
        'nomor_whatsapp_ortu',
        'catatan',
        'file_kk',
        'file_akta',
        'file_ijazah',
        'file_rapor',
        'file_pas_foto',
        'file_ktp_ortu',
        'status',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public static function generateRegistrationNumber(): string
    {
        $year = now()->year;
        $prefix = "PPDB-{$year}-";
        
        $lastRecord = self::where('registration_number', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        if (!$lastRecord) {
            return "{$prefix}0001";
        }

        $lastNumber = (int) substr($lastRecord->registration_number, strlen($prefix));
        $nextNumber = $lastNumber + 1;

        return $prefix . sprintf('%04d', $nextNumber);
    }
}
