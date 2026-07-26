<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppdbs', function (Blueprint $table) {
            $table->id();
            $table->string('batch_name');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('requirements')->nullable();
            $table->decimal('fee', 10, 2)->default(0);
            $table->boolean('status')->default(false); // true if active
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdbs');
    }
};
