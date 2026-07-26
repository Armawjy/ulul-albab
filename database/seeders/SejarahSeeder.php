<?php

namespace Database\Seeders;

use App\Models\Sejarah;
use Illuminate\Database\Seeder;

class SejarahSeeder extends Seeder
{
    public function run(): void
    {
        $sejarahData = [
            [
                'title' => 'Pendirian Pesantren',
                'content' => 'Pesantren resmi didirikan dengan 23 santri angkatan pertama dan 5 guru pengajar.',
                'year' => '2012',
            ],
            [
                'title' => 'Pembangunan Gedung',
                'content' => 'Pembangunan gedung kelas dan asrama putra pertama untuk mendukung kenyamanan proses belajar mengajar.',
                'year' => '2014',
            ],
            [
                'title' => 'Akreditasi A',
                'content' => 'SMA Ulul Albab Makassar berhasil meraih Akreditasi A dari BAN-S/M, membuktikan kualitas pendidikan yang unggul.',
                'year' => '2016',
            ],
            [
                'title' => 'Pengembangan Kurikulum',
                'content' => 'Mengintegrasikan kurikulum nasional, kepesantrenan modern, tahfidz bersanad, dan teknologi informasi.',
                'year' => '2018',
            ],
            [
                'title' => 'Fasilitas Modern',
                'content' => 'Pembangunan laboratorium komputer, laboratorium IPA sains modern, dan perpustakaan digital terintegrasi.',
                'year' => '2020',
            ],
            [
                'title' => 'Digitalisasi & Prestasi Internasional',
                'content' => 'Mengembangkan sistem manajemen pesantren digital dan santri berhasil meraih berbagai prestasi di tingkat nasional maupun internasional.',
                'year' => '2023',
            ],
        ];

        foreach ($sejarahData as $item) {
            Sejarah::create($item);
        }
    }
}
