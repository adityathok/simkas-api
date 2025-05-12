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
            $table->timestamp('tanggal');
            $table->char('akun_rekening_id', 26)->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('ref_id')->nullable()->constrained('transaksis')->onDelete('set null');
            $table->string('metode_pembayaran')->nullable();
            $table->enum('status', ['pending', 'sukses', 'gagal', 'batal']);
            $table->text('catatan')->nullable();
            $table->string('nomor');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('akun_rekening_id')->references('id')->on('akun_rekenings')->onDelete('set null');
        });

        DB::statement('ALTER TABLE tagihan_masters AUTO_INCREMENT = 7000');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
