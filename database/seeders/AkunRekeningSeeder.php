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
                'nama'          => 'Cash',
                'tipe'          => 'tunai',
                'keterangan'    => 'Uang cash yang dibawa',
            ],
            [
                'nama'          => 'BMT Ahmad Dahlan',
                'tipe'          => 'bank',
                'keterangan'    => 'Uang cash di Rekening BMT Ahmad Dahlan',
            ],
            [
                'nama'          => 'BNI',
                'tipe'          => 'bank',
                'keterangan'    => 'Uang di Rekening BNI',
            ],
        ];

        //simpan
        foreach ($akuns as $akun) {
            AkunRekening::create($akun);
        }
    }
}
