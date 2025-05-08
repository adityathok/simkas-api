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
            $table->foreignId('tagihan_id')->constrained()->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('keterangan')->nullable();
            $table->timestamp('tanggal');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('pendapatan_id')->references('id')->on('akun_pendapatans')->onDelete('set null');
            $table->foreign('pengeluaran_id')->references('id')->on('akun_pengeluarans')->onDelete('set null');
            $table->foreign('rekening_id')->references('id')->on('akun_rekenings')->onDelete('set null');
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
