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
        Schema::create('transaksi_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained()->onDelete('cascade');
            $table->string('nama');
            $table->integer('qty');
            $table->decimal('nominal_item', 15, 2);
            $table->decimal('nominal', 15, 2);
            $table->foreignId('tagihan_id')->nullable()->constrained('tagihans')->onDelete('set null');
            $table->char('akun_pendapatan_id', 26)->nullable();
            $table->char('akun_pengeluaran_id', 26)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('akun_pendapatan_id')->references('id')->on('akun_pendapatans')->onDelete('set null');
            $table->foreign('akun_pengeluaran_id')->references('id')->on('akun_pengeluarans')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_items');
    }
};
