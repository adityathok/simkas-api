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
        Schema::create('jurnal_kas', function (Blueprint $table) {
            $table->char('id', 26)->primary();
            $table->string('nama');
            $table->enum('kas', ['neraca', 'jurnal']);
            $table->boolean('neraca')->default(false);
            $table->boolean('jurnal_khusus')->default(false);
            $table->boolean('likuiditas')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal_kas');
    }
};
