<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('unit_sekolahs', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jenjang');
            $table->string('alamat')->nullable();
            $table->string('desa')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kota')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kode_pos')->nullable();
            $table->string('status')->nullable()->default('aktif');
            $table->date('tanggal_berdiri')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->foreignId('logo')->nullable()->index();
            $table->string('tingkat')->nullable();
            $table->string('rombel')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        DB::statement('ALTER TABLE unit_sekolahs AUTO_INCREMENT = 200');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_sekolahs');
    }
};
