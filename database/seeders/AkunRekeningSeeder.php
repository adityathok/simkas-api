<?php

namespace Database\Seeders;

use App\Models\AkunRekening;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AkunRekeningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //array akun rekening
        $akuns = [
            [
                'id'            => 'CASH',
                'nama'          => 'Cash',
                'keterangan'    => 'Uang cash yang dibawa',
            ],
            [
                'id'            => 'BMT',
                'nama'          => 'BMT Ahmad Dahlan',
                'keterangan'    => 'Uang cash di Rekening BMT Ahmad Dahlan',
            ],
            [
                'id'            => 'BNI',
                'nama'          => 'BNI',
                'keterangan'    => 'Uang di Rekening BNI',
            ],
        ];

        //simpan
        foreach ($akuns as $akun) {
            AkunRekening::create($akun);
        }
    }
}
