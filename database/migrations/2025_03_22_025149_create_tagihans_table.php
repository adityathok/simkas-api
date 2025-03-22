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
            $table->char('batch_id', 26)->nullable();
            $table->decimal('nominal', 10, 2);
            $table->enum('status', ['belum', 'lunas', 'batal'])->default('belum');
            $table->char('user_id', 26)->nullable();
            $table->char('admin_id', 26)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('batch_id')->references('id')->on('tagihan_batches')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
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
