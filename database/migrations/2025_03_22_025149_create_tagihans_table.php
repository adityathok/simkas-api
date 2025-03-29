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
        Schema::create('tagihans', function (Blueprint $table) {
            $table->char('id', 26)->primary();
            $table->string('nama');
            $table->timestamp('tanggal');
            $table->char('tagihan_master_id', 26)->nullable();
            $table->enum('status', ['belum', 'lunas', 'batal'])->default('belum');
            $table->char('user_id', 26)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tagihan_master_id')->references('id')->on('tagihan_masters')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihans');
    }
};
