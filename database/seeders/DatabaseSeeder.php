<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use App\Models\Program;
use App\Models\Guru;
use App\Models\Berita;
use App\Models\Profil;
use App\Models\Sejarah;
use App\Models\VisiMisi;
use App\Models\Slider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Default Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
            ]
        );

        $this->call([
            SettingSeeder::class,
            ProfilSeeder::class,
            SejarahSeeder::class,
            VisiMisiSeeder::class,
            ProgramSeeder::class,
            GuruSeeder::class,
            BeritaSeeder::class,
            PrestasiSeeder::class,
            GaleriSeeder::class,
            PPDBSeeder::class,
            KontakSeeder::class,
        ]);

        // Slider
        Slider::create([
            'title' => 'Membentuk Generasi Qurani',
            'image' => 'https://images.unsplash.com/photo-1541829070764-84a7d30dd3f3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
            'order' => 1,
            'is_active' => true,
        ]);
    }
}
