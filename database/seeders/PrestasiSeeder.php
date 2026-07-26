<?php

namespace Database\Seeders;

use App\Models\Prestasi;
use Illuminate\Database\Seeder;

class PrestasiSeeder extends Seeder
{
    public function run(): void
    {
        $prestasiData = [
            [
                'title' => 'Olimpiade Sains Nasional (OSN) Matematika',
                'level' => 'Nasional',
                'rank' => 'Medali Emas',
                'winner_name' => 'Ahmad Abdullah & Tim',
                'year' => '2024',
            ],
            [
                'title' => 'Musabaqah Qira\'atil Kutub (MQK) Tingkat Nasional',
                'level' => 'Nasional',
                'rank' => 'Juara 1 Kitab Fiqih',
                'winner_name' => 'Muhammad Zaki Al-Farisi',
                'year' => '2024',
            ],
            [
                'title' => 'Lomba Pidato Bahasa Arab & Inggris Internasional',
                'level' => 'Internasional',
                'rank' => 'Juara 2',
                'winner_name' => 'Fatimah Az-Zahra',
                'year' => '2023',
            ],
            [
                'title' => 'Kompetisi Robotik & IoT Islami',
                'level' => 'Nasional',
                'rank' => 'Best Innovation Award',
                'winner_name' => 'Tim Cyber Santri Ulul Albab',
                'year' => '2023',
            ],
            [
                'title' => 'Musabaqah Hifdzil Quran (MHQ) 30 Juz',
                'level' => 'Provinsi',
                'rank' => 'Juara 1',
                'winner_name' => 'Umar Abdul Aziz',
                'year' => '2023',
            ],
            [
                'title' => 'Lomba Karya Tulis Ilmiah Al-Quran (LKTI-Q)',
                'level' => 'Nasional',
                'rank' => 'Juara 1',
                'winner_name' => 'Aisyah Nurul Hikmah',
                'year' => '2022',
            ],
        ];

        foreach ($prestasiData as $item) {
            Prestasi::create($item);
        }
    }
}
