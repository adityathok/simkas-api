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
        Schema::create('unit_sekolah_pegawais', function (Blueprint $table) {
            $table->id();
            $table->char('unit_sekolah_id', 26)->nullable();
            $table->foreign('unit_sekolah_id')->references('id')->on('unit_sekolahs')->onDelete('cascade');
            $table->char('user_id', 26)->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('jabatan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_sekolah_pegawais');
    }
};
