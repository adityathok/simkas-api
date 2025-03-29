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
        Schema::create('tagihan_masters', function (Blueprint $table) {
            $table->char('id', 26)->primary();
            $table->string('nama');
            $table->decimal('nominal', 10, 2);
            $table->string('type');
            $table->integer('total_tagihan');
            $table->decimal('total_nominal', 10, 2);
            $table->string('keterangan')->nullable();
            $table->date('due_date')->nullable();
            $table->string('periode_start')->nullable();
            $table->string('periode_end')->nullable();
            $table->char('akun_pendapatan_id', 26)->nullable();
            $table->string('tahun_ajaran')->nullable();
            $table->char('unit_sekolah_id', 26)->nullable();
            $table->char('kelas_id', 26)->nullable();
            $table->string('user_type')->nullable();
            $table->char('admin_id', 26)->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('akun_pendapatan_id')->references('id')->on('akun_pendapatans')->onDelete('set null');
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
        Schema::dropIfExists('tagihan_masters');
    }
};
