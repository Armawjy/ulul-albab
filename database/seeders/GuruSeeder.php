<?php

namespace Database\Seeders;

use App\Models\Guru;
use Illuminate\Database\Seeder;

class GuruSeeder extends Seeder
{
    public function run(): void
    {
        $guruData = [
            [
                'name' => 'Dr. K.H. Abdullah Al-Makassari, M.A.',
                'nip' => '197501012000011001',
                'position' => 'Pimpinan Pesantren',
                'description' => 'Pakar Tafsir dan Ulumul Quran, Lulusan Al-Azhar Kairo Mesir.',
            ],
            [
                'name' => 'Ustadz H. Muhammad Zaki, Lc., Al-Hafidz',
                'nip' => '198503122010011002',
                'position' => 'Kepala Tahfidz & Guru Agama',
                'description' => 'Pemegang Sanad Qiraah Sab\'ah & Pembina Tahfidz Al-Qur\'an.',
            ],
            [
                'name' => 'Ustadzah Nurul Hikmah, S.Pd.I., M.Pd.',
                'nip' => '198807212012022001',
                'position' => 'Guru Agama & Fiqih',
                'description' => 'Pengajar Fiqih Muamalah & Pembina Keputrian Santriwati.',
            ],
            [
                'name' => 'Ahmad Fauzi, S.Si., M.Sc.',
                'nip' => '199005152015011003',
                'position' => 'Guru Umum (Matematika & Sains)',
                'description' => 'Pembina Olimpiade Sains Nasional (OSN) & Riset Ilmiah Santri.',
            ],
            [
                'name' => 'Ir. Hendra Pratama, M.Kom.',
                'nip' => '199208102018011004',
                'position' => 'Guru Umum (TIK & Robotik)',
                'description' => 'Kepala Laboratorium Komputer & Pembina Ekstrakurikuler IoT.',
            ],
            [
                'name' => 'Siti Aisyah, S.Pd., M.A.',
                'nip' => '199111202017022002',
                'position' => 'Guru Umum (Bahasa Inggris)',
                'description' => 'Koordinator English & Arabic Club Pesantren.',
            ],
        ];

        foreach ($guruData as $item) {
            Guru::create($item);
        }
    }
}
