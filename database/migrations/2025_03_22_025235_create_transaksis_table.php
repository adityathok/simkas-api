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
            $table->string('nomor');
            $table->decimal('nominal', 15, 2);
            $table->enum('jenis', ['pendapatan', 'pengeluaran', 'transfer']);
            $table->timestamp('tanggal');
            $table->foreignId('akun_rekening_id');
            $table->foreignId('akun_rekening_tujuan_id')->nullable()->constrained('akun_rekenings');
            $table->foreignId('user_id')->nullable();
            $table->string('metode_pembayaran')->nullable();
            $table->enum('status', ['pending', 'sukses', 'gagal', 'batal']);
            $table->text('catatan')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('ref_id')->nullable()->constrained('transaksis')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE tagihan_masters AUTO_INCREMENT = 1000');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
