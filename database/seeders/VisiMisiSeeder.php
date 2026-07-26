<?php

namespace Database\Seeders;

use App\Models\VisiMisi;
use Illuminate\Database\Seeder;

class VisiMisiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // Visi
            ['type' => 'visi', 'content' => 'Menjadi lembaga pendidikan Islam unggul yang melahirkan generasi Qurani, berilmu, berprestasi, berkarakter, dan siap menjadi pemimpin masa depan yang membawa manfaat bagi umat dan bangsa.', 'created_at' => now(), 'updated_at' => now()],
            
            // Misi
            ['type' => 'misi', 'content' => 'Menyelenggarakan pendidikan yang mengintegrasikan ilmu agama, ilmu pengetahuan, teknologi, dan keterampilan.', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'misi', 'content' => 'Membina generasi Qurani yang memiliki pemahaman dan pengamalan Al-Qur\'an dalam kehidupan sehari-hari.', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'misi', 'content' => 'Mengembangkan potensi akademik dan non-akademik siswa secara optimal untuk meraih prestasi.', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'misi', 'content' => 'Membentuk karakter Islami yang berakhlak mulia, disiplin, mandiri, tanggung jawab, dan berjiwa kepemimpinan.', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'misi', 'content' => 'Menciptakan lingkungan pesantren yang kondusif, aman, dan nyaman untuk belajar, beribadah, dan berkarya.', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'misi', 'content' => 'Menjalin kerja sama dan sinergi dengan orang tua, masyarakat, dan berbagai pihak untuk kemajuan pendidikan.', 'created_at' => now(), 'updated_at' => now()],

            // Nilai
            ['type' => 'nilai', 'content' => 'Iman & Taqwa - Menanamkan keimanan sebagai landasan setiap langkah.', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'nilai', 'content' => 'Ilmu & Amal - Mengutamakan ilmu yang bermanfaat dan diamalkan.', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'nilai', 'content' => 'Akhlak Mulia - Membentuk pribadi berakhlak mulia dalam berperilaku.', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'nilai', 'content' => 'Kemandirian - Melatih kemandirian dan kedisiplinan santri.', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'nilai', 'content' => 'Inovasi & Kreativitas - Mendorong inovasi untuk menghadapi tantangan masa depan.', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'nilai', 'content' => 'Kepemimpinan - Membina jiwa kepemimpinan yang amanah.', 'created_at' => now(), 'updated_at' => now()],
        ];

        VisiMisi::insert($data);
    }
}
