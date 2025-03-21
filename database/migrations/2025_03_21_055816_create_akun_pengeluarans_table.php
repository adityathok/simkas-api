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
        Schema::create('akun_pengeluarans', function (Blueprint $table) {
            $table->char('id', 26)->primary();
            $table->string('nama');
            $table->string('sumber');
            $table->char('pendapatan_id', 26)->nullable();
            $table->char('admin_id', 26)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('pendapatan_id')->references('id')->on('akun_pendapatans')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akun_pengeluarans');
    }
};
