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
        Schema::create('akun_rekenings', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('tipe', ['tunai', 'bank'])->nullable();
            $table->text('keterangan')->nullable();
            $table->decimal('saldo', 18, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akun_rekenings');
    }
};
