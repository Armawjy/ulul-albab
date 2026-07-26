<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppdb_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique();
            $table->string('nama_lengkap');
            $table->string('jenis_kelamin');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat_lengkap');
            $table->string('asal_sekolah');
            $table->string('nama_orang_tua');
            $table->string('nomor_whatsapp_ortu');
            $table->text('catatan')->nullable();
            
            // Berkas Unggahan (Storage Paths)
            $table->string('file_kk')->nullable();
            $table->string('file_akta')->nullable();
            $table->string('file_ijazah')->nullable();
            $table->string('file_rapor')->nullable();
            $table->string('file_pas_foto')->nullable();
            $table->string('file_ktp_ortu')->nullable();
            
            // Status: Menunggu, Diverifikasi, Diterima, Ditolak
            $table->string('status')->default('Menunggu');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_registrations');
    }
};
