<?php

namespace Database\Seeders;

use App\Models\Profil;
use Illuminate\Database\Seeder;

class ProfilSeeder extends Seeder
{
    public function run(): void
    {
        Profil::create([
            'title' => 'Pondok Pesantren Ulul Albab Makassar',
            'content' => 'Pondok Pesantren Ulul Albab Makassar merupakan lembaga pendidikan Islam modern tingkat SMA/MA yang mengintegrasikan pendidikan agama, akademik, teknologi, kepemimpinan, dan pembinaan karakter dalam lingkungan pesantren yang kondusif dan islami.',
        ]);
    }
}
