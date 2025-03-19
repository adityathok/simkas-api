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
                'id'            => 'hutang',
                'nama'          => 'Hutang',
                'kas'           => 'neraca',
                'neraca'        => true,
                'jurnal_khusus' => true,
                'likuiditas'    => false
            ],
            [
                'id'            => 'bos',
                'nama'          => 'Dana Bantuan Negara BOS',
                'kas'           => 'neraca',
                'neraca'        => true,
                'jurnal_khusus' => true,
                'likuiditas'    => false
            ],
            [
                'id'            => 'tabungan',
                'nama'          => 'Tabungan',
                'kas'           => 'neraca',
                'neraca'        => true,
                'jurnal_khusus' => true,
                'likuiditas'    => false
            ],
            [
                'id'            => 'buku',
                'nama'          => 'Buku',
                'kas'           => 'jurnal',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'likuiditas'    => true
            ],
            [
                'id'            => 'seragam',
                'nama'          => 'Seragam',
                'kas'           => 'jurnal',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'likuiditas'    => true
            ],
            [
                'id'            => 'sarpras',
                'nama'          => 'Sarpras',
                'kas'           => 'jurnal',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'likuiditas'    => true
            ],
            [
                'id'            => 'gedung',
                'nama'          => 'Gedung',
                'kas'           => 'jurnal',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'likuiditas'    => true
            ],
            [
                'id'            => 'infaqdakwah',
                'nama'          => 'Donasi infaq dakwah dan sosial',
                'kas'           => 'jurnal',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'likuiditas'    => true
            ],
            [
                'id'            => 'piutangp',
                'nama'          => 'Piutang Pegawai',
                'kas'           => 'jurnal',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'likuiditas'    => true
            ],
            [
                'id'            => 'konsumsi',
                'nama'          => 'Konsumsi',
                'kas'           => 'jurnal',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'likuiditas'    => true
            ],
            [
                'id'            => 'bkoperasi',
                'nama'          => 'Bisnis Koperasi',
                'kas'           => 'jurnal',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'likuiditas'    => true
            ],
            [
                'id'            => 'bhexaair',
                'nama'          => 'Bisnis Hexa Air',
                'kas'           => 'jurnal',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'likuiditas'    => true
            ],
            [
                'id'            => 'kasxpondok',
                'nama'          => 'Kas Ekskul Pondok',
                'kas'           => 'jurnal',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'likuiditas'    => true
            ],
            [
                'id'            => 'kaskurbantu',
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
