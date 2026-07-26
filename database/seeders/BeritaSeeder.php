<?php

namespace Database\Seeders;

use App\Models\Berita;
use App\Models\User;
use Illuminate\Database\Seeder;

class BeritaSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        if ($admin) {
            Berita::create([
                'title' => 'Pelepasan Santri Kelas 12 Angkatan ke-10',
                'slug' => 'pelepasan-santri-kelas-12-angkatan-10',
                'content' => 'Acara pelepasan berlangsung khidmat...',
                'category' => 'Akademik',
                'author_id' => $admin->id,
                'status' => 'published',
                'published_at' => now(),
            ]);
        }
    }
}
