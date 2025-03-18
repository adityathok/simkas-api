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
        Schema::create('tagihan_batches', function (Blueprint $table) {
            $table->char('id', 26)->primary();
            $table->string('nama');
            $table->string('status');
            $table->date('tanggal');
            $table->integer('jumlah');
            $table->decimal('total_nominal', 10, 2);
            $table->string('keterangan')->nullable();
            $table->date('expired')->nullable();
            $table->char('pendapatan_id', 26)->nullable();
            $table->string('tahun_ajaran')->nullable();
            $table->char('unit_sekolah_id', 26)->nullable();
            $table->char('kelas_id', 26)->nullable();
            $table->string('user_type');
            $table->char('admin_id', 26)->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('pendapatan_id')->references('id')->on('akun_pendapatans')->onDelete('set null');
            $table->foreign('unit_sekolah_id')->references('id')->on('unit_sekolahs')->onDelete('set null');
            $table->foreign('kelas_id')->references('id')->on('kelas')->onDelete('set null');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihan_batches');
    }
};
