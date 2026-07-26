<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'app_name', 'value' => 'Pondok Pesantren Ulul Albab Makassar', 'type' => 'string'],
            ['key' => 'address', 'value' => 'Jl. Toddopuli Raya No. 123, Makassar', 'type' => 'string'],
            ['key' => 'email', 'value' => 'info@ululalbabmakassar.sch.id', 'type' => 'string'],
            ['key' => 'phone', 'value' => '+62 812-3456-7890', 'type' => 'string'],
            ['key' => 'whatsapp', 'value' => '6281234567890', 'type' => 'string'],
        ];
        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
