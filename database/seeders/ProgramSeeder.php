<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        Program::insert([
            ['name' => 'Ilmu Pengetahuan Alam (IPA)', 'icon' => 'Microscope', 'description' => 'Program unggulan dengan pendalaman sains', 'order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tahfidz Al-Qur\'an', 'icon' => 'Book', 'description' => 'Program tahfidz intensif 30 Juz', 'order' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
