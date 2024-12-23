<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UnitSekolah;

class UnitSekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id' => 'KB123456789',
                'nama' => 'KB Matahari Pagi',
                'jenjang' => 'KB',
                'alamat' => 'Jl. Pendidikan No.1, Sukoharjo',
                'desa' => 'Sukoharjo',
                'kecamatan' => 'Sukoharjo',
                'kota' => 'Sukoharjo',
                'provinsi' => 'Jawa Tengah',
                'kode_pos' => '12345',
                'status' => 'aktif',
                'tanggal_berdiri' => '2008-01-01',
                'kepala_sekolah' => 'Budi Santoso',
                'whatsapp' => '08123456789',
                'telepon' => '0271234567',
                'email' => 'kbmataharipagi@example.com',
            ],
            [
                'id' => 'TK123456789',
                'nama' => 'TK Matahari Pagi',
                'jenjang' => 'TK',
                'alamat' => 'Jl. Pendidikan No.2, Sukoharjo',
                'desa' => 'Sukoharjo',
                'kecamatan' => 'Sukoharjo',
                'kota' => 'Sukoharjo',
                'provinsi' => 'Jawa Tengah',
                'kode_pos' => '12345',
                'status' => 'aktif',
                'tanggal_berdiri' => '2010-01-01',
                'kepala_sekolah' => 'Arif Santoso',
                'whatsapp' => '08123456789',
                'telepon' => '0271234567',
                'email' => 'tkmataharipagi@example.com',
            ],
            [
                'id' => 'SD123456789',
                'nama' => 'SD Matahari Pagi',
                'jenjang' => 'SD',
                'alamat' => 'Jl. Pendidikan No.3, Sukoharjo',
                'desa' => 'Sukoharjo',
                'kecamatan' => 'Sukoharjo',
                'kota' => 'Sukoharjo',
                'provinsi' => 'Jawa Tengah',
                'kode_pos' => '12345',
                'status' => 'aktif',
                'tanggal_berdiri' => '2012-01-01',
                'kepala_sekolah' => 'Donnie Santoso',
                'whatsapp' => '08123456789',
                'telepon' => '0271234567',
                'email' => 'sdmataharipagi@example.com',
            ],
            [
                'id' => 'SMP123456789',
                'nama' => 'SMP Matahari Pagi',
                'jenjang' => 'SMP',
                'alamat' => 'Jl. Pendidikan No.15, Sukoharjo',
                'desa' => 'Sukoharjo',
                'kecamatan' => 'Sukoharjo',
                'kota' => 'Sukoharjo',
                'provinsi' => 'Jawa Tengah',
                'kode_pos' => '12345',
                'status' => 'aktif',
                'tanggal_berdiri' => '2016-01-01',
                'kepala_sekolah' => 'Rian Putra Santoso',
                'whatsapp' => '08123456789',
                'telepon' => '0271234567',
                'email' => 'smpmataharipagi@example.com',
            ],
            [
                'id' => 'SMA123456789',
                'nama' => 'SMA Matahari Pagi',
                'jenjang' => 'SMA',
                'alamat' => 'Jl. Pendidikan No.30, Sukoharjo',
                'desa' => 'Sukoharjo',
                'kecamatan' => 'Sukoharjo',
                'kota' => 'Sukoharjo',
                'provinsi' => 'Jawa Tengah',
                'kode_pos' => '12345',
                'status' => 'aktif',
                'tanggal_berdiri' => '2021-01-01',
                'kepala_sekolah' => 'Putra Santoso',
                'whatsapp' => '08123456789',
                'telepon' => '0271234567',
                'email' => 'smamataharipagi@example.com',
            ],
        ];

        foreach ($data as $unit) {
            UnitSekolah::create($unit);
        }
    }
}
