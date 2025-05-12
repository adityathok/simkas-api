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
        Schema::create('akun_pendapatans', function (Blueprint $table) {
            $table->char('id', 26)->primary();
            $table->string('nama');
            $table->boolean('neraca')->default(false);
            $table->boolean('jurnal_khusus')->default(false);
            $table->char('jurnalkas_id', 26)->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('jurnalkas_id')->references('id')->on('jurnal_kas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akun_pendapatans');
    }
};
