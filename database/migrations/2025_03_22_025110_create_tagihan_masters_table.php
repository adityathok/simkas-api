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
        Schema::create('tagihan_masters', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->decimal('nominal', 15, 2);
            $table->string('type');
            $table->integer('total_tagihan');
            $table->decimal('total_nominal', 18, 2);
            $table->string('keterangan')->nullable();
            $table->date('due_date')->nullable();
            $table->string('periode_start')->nullable();
            $table->string('periode_end')->nullable();
            $table->char('akun_pendapatan_id', 26)->nullable();
            $table->string('tahun_ajaran')->nullable();
            $table->unsignedBigInteger('unit_sekolah_id')->nullable();
            $table->unsignedBigInteger('kelas_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->string('user_type')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('akun_pendapatan_id')->references('id')->on('akun_pendapatans')->onDelete('set null');
            $table->foreign('unit_sekolah_id')->references('id')->on('unit_sekolahs')->onDelete('set null');
            $table->foreign('kelas_id')->references('id')->on('kelas')->onDelete('set null');
        });

        DB::statement('ALTER TABLE tagihan_masters AUTO_INCREMENT = 50000');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihan_masters');
    }
};
