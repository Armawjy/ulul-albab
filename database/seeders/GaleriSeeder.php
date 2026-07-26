<?php

namespace Database\Seeders;

use App\Models\Galeri;
use Illuminate\Database\Seeder;

class GaleriSeeder extends Seeder
{
    public function run(): void
    {
        Galeri::create([
            'title' => 'Kegiatan Belajar Mengajar',
            'category' => 'Akademik',
            'image' => 'https://images.unsplash.com/photo-1541829070764-84a7d30dd3f3',
            'description' => 'Suasana belajar santri di kelas',
        ]);
    }
}
