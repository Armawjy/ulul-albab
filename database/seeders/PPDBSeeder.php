<?php

namespace Database\Seeders;

use App\Models\Ppdb;
use Illuminate\Database\Seeder;

class PPDBSeeder extends Seeder
{
    public function run(): void
    {
        Ppdb::create([
            'batch_name' => 'Tahun Ajaran 2026/2027',
            'start_date' => '2026-01-01',
            'end_date' => '2026-03-30',
            'requirements' => '1. Fotocopy KK, 2. Pas Foto',
            'fee' => 300000,
            'status' => true,
        ]);
    }
}
