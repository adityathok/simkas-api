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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->decimal('nominal', 15, 2);
            $table->enum('arus', ['masuk', 'keluar']);
            $table->char('pendapatan_id', 26)->nullable();
            $table->char('pengeluaran_id', 26)->nullable();
            $table->char('rekening_id', 26)->nullable();
            $table->char('sumber_rekening_id', 26)->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('keterangan')->nullable();
            $table->foreignId('ref_id')->nullable()->constrained('transaksis')->onDelete('set null');
            $table->timestamp('tanggal');
            $table->string('metode_pembayaran');
            $table->enum('status', ['pending', 'sukses', 'gagal', 'batal']);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('pendapatan_id')->references('id')->on('akun_pendapatans')->onDelete('set null');
            $table->foreign('pengeluaran_id')->references('id')->on('akun_pengeluarans')->onDelete('set null');
            $table->foreign('rekening_id')->references('id')->on('akun_rekenings')->onDelete('set null');
            $table->foreign('sumber_rekening_id')->references('id')->on('akun_rekenings')->onDelete('set null');
        });

        DB::statement('ALTER TABLE tagihan_masters AUTO_INCREMENT = 700000');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
