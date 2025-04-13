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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->char('id', 26)->primary();
            $table->string('nama');
            $table->decimal('nominal', 15, 2);
            $table->enum('arus', ['masuk', 'keluar']);
            $table->char('pendapatan_id', 26)->nullable();
            $table->char('pengeluaran_id', 26)->nullable();
            $table->char('rekening_id', 26)->nullable();
            $table->char('tagihan_id', 26)->nullable();
            $table->char('user_id', 26)->nullable();
            $table->char('admin_id', 26)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamp('tanggal');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('pendapatan_id')->references('id')->on('akun_pendapatans')->onDelete('set null');
            $table->foreign('pengeluaran_id')->references('id')->on('akun_pengeluarans')->onDelete('set null');
            $table->foreign('rekening_id')->references('id')->on('akun_rekenings')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('tagihan_id')->references('id')->on('tagihans')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
