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
        Schema::create('pegawai_metas', function (Blueprint $table) {
            $table->id(); 
            $table->char('pegawai_id', 26)->nullable(); // ULID has 26 characters
            $table->foreign('pegawai_id')->references('id')->on('pegawais')->onDelete('cascade');
            $table->string('key');
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawai_metas');
    }
};
