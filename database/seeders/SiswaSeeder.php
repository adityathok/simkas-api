<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kelas;
use App\Models\Siswa;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //dapatkan daftar kelas
        $getkelas = Kelas::all();

        foreach ($getkelas as $kelas) {
            //factory 20 - 30 siswa
            Siswa::factory(rand(10, 20))->create();

            //loop 15 - 30 siswa
            for ($i = 0; $i < rand(15, 30); $i++) {
                $siswa = Siswa::factory()->create();
                $siswa->kelas()->attach($kelas->id);
            }
        }
    }
}
