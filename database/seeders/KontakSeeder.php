<?php

namespace Database\Seeders;

use App\Models\Kontak;
use Illuminate\Database\Seeder;

class KontakSeeder extends Seeder
{
    public function run(): void
    {
        Kontak::create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'subject' => 'Tanya Biaya',
            'message' => 'Berapa biaya masuk tahun ini?',
        ]);
    }
}
