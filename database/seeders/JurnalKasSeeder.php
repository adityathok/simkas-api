<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\JurnalKas;

class JurnalKasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //array seeder jurnal kas
        $jurnalKas = [
            [
                'id'            => 'JHUTANG',
                'nama'          => 'Hutang',
                'kas'           => 'neraca',
                'neraca'        => true,
                'jurnal_khusus' => true,
                'likuiditas'    => false
            ],
            [
                'id'            => 'JBOS',
                'nama'          => 'Dana Bantuan Negara BOS',
                'kas'           => 'neraca',
                'neraca'        => true,
                'jurnal_khusus' => true,
                'likuiditas'    => false
            ],
            [
                'id'            => 'JTABUNGAN',
                'nama'          => 'Tabungan',
                'kas'           => 'neraca',
                'neraca'        => true,
                'jurnal_khusus' => true,
                'likuiditas'    => false
            ],
            [
                'id'            => 'JBUKU',
                'nama'          => 'Buku',
                'kas'           => 'jurnal',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'likuiditas'    => true
            ],
            [
                'id'            => 'JSERAGAM',
                'nama'          => 'Seragam',
                'kas'           => 'jurnal',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'likuiditas'    => true
            ],
            [
                'id'            => 'JSARPRAS',
                'nama'          => 'Sarpras',
                'kas'           => 'jurnal',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'likuiditas'    => true
            ],
            [
                'id'            => 'JGEDUNG',
                'nama'          => 'Gedung',
                'kas'           => 'jurnal',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'likuiditas'    => true
            ],
            [
                'id'            => 'JINFAQ',
                'nama'          => 'Donasi infaq dakwah dan sosial',
                'kas'           => 'jurnal',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'likuiditas'    => true
            ],
            [
                'id'            => 'JPIUTANGP',
                'nama'          => 'Piutang Pegawai',
                'kas'           => 'jurnal',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'likuiditas'    => true
            ],
            [
                'id'            => 'JKONSUMSI',
                'nama'          => 'Konsumsi',
                'kas'           => 'jurnal',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'likuiditas'    => true
            ],
            [
                'id'            => 'JBKOPERASI',
                'nama'          => 'Bisnis Koperasi',
                'kas'           => 'jurnal',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'likuiditas'    => true
            ],
            [
                'id'            => 'JBHEXAAIR',
                'nama'          => 'Bisnis Hexa Air',
                'kas'           => 'jurnal',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'likuiditas'    => true
            ],
            [
                'id'            => 'JKASXPONDOK',
                'nama'          => 'Kas Ekskul Pondok',
                'kas'           => 'jurnal',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'likuiditas'    => true
            ],
            [
                'id'            => 'JKASKURBANTU',
                'nama'          => 'Kas Kurban TU',
                'kas'           => 'jurnal',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'likuiditas'    => true
            ]
        ];

        // Masukkan data ke database
        foreach ($jurnalKas as $jurnalKas) {
            JurnalKas::create([
                'id'            => $jurnalKas['id'],
                'nama'          => $jurnalKas['nama'],
                'kas'           => $jurnalKas['kas'],
                'neraca'        => $jurnalKas['neraca'],
                'jurnal_khusus' => $jurnalKas['jurnal_khusus'],
                'likuiditas'    => $jurnalKas['likuiditas']
            ]);
        }
    }
}
