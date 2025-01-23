<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('nama');
            $table->string('tingkat');
            $table->string('tahun_ajaran');
            $table->char('unit_sekolah_id', 26)->nullable();
            $table->foreign('unit_sekolah_id')->references('id')->on('unit_sekolahs')->onDelete('set null');
            $table->char('wali_id', 26)->nullable();
            $table->foreign('wali_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
