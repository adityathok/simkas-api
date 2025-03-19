<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AkunPendapatan;

class AkunPendapatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //array akun pendapatan
        $akuns = [
            [
                'id'            => 'inkasneraca',
                'nama'          => 'Kas Neraca',
                'neraca'        => true,
                'jurnal_khusus' => false,
                'jurnalkas_id'  => null,
                'admin_id'      => null
            ],
            [
                'id'            => 'intlampau',
                'nama'          => 'Tunggakan Lampau',
                'neraca'        => false,
                'jurnal_khusus' => false,
                'jurnalkas_id'  => null,
                'admin_id'      => null
            ],
            [
                'id'            => 'inoprsnl',
                'nama'          => 'Operasional',
                'neraca'        => false,
                'jurnal_khusus' => false,
                'jurnalkas_id'  => null,
                'admin_id'      => null
            ],
            [
                'id'            => 'inspppen',
                'nama'          => 'SPP Pendidikan',
                'neraca'        => false,
                'jurnal_khusus' => false,
                'jurnalkas_id'  => null,
                'admin_id'      => null
            ],
            [
                'id'            => 'indaftar',
                'nama'          => 'Pendaftaran',
                'neraca'        => false,
                'jurnal_khusus' => false,
                'jurnalkas_id'  => null,
                'admin_id'      => null
            ],
            [
                'id'            => 'inhutang',
                'nama'          => 'Hutang',
                'neraca'        => true,
                'jurnal_khusus' => true,
                'jurnalkas_id'  => 'hutang',
                'admin_id'      => null
            ],
            [
                'id'            => 'inbos',
                'nama'          => 'Dana Bantuan Negara BOS',
                'neraca'        => true,
                'jurnal_khusus' => true,
                'jurnalkas_id'  => 'bos',
                'admin_id'      => null
            ],
            [
                'id'            => 'intabung',
                'nama'          => 'Tabungan',
                'neraca'        => true,
                'jurnal_khusus' => true,
                'jurnalkas_id'  => 'tabungan',
                'admin_id'      => null
            ],
            [
                'id'            => 'inbuku',
                'nama'          => 'Buku',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'jurnalkas_id'  => 'buku',
                'admin_id'      => null
            ],
            [
                'id'            => 'inseragam',
                'nama'          => 'Seragam',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'jurnalkas_id'  => 'seragam',
                'admin_id'      => null
            ],
            [
                'id'            => 'insarpras',
                'nama'          => 'Sarpras',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'jurnalkas_id'  => 'sarpras',
                'admin_id'      => null
            ],
            [
                'id'            => 'ingedung',
                'nama'          => 'Gedung',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'jurnalkas_id'  => 'gedung',
                'admin_id'      => null
            ],
            [
                'id'            => 'ininfaq',
                'nama'          => 'Donasi Infaq Dakwah Sosial',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'jurnalkas_id'  => 'infaqdakwah',
                'admin_id'      => null
            ],
            [
                'id'            => 'inpiutangp',
                'nama'          => 'Piutang Pegawai',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'jurnalkas_id'  => 'piutangp',
                'admin_id'      => null
            ],
            [
                'id'            => 'inkonsumsi',
                'nama'          => 'Konsumsi',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'jurnalkas_id'  => 'konsumsi',
                'admin_id'      => null
            ],
            [
                'id'            => 'inbkoperasi',
                'nama'          => 'Bisnis Koperasi',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'jurnalkas_id'  => 'bkoperasi',
                'admin_id'      => null
            ],
            [
                'id'            => 'inbhexaair',
                'nama'          => 'Bisnis Hexa Air',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'jurnalkas_id'  => 'bhexaair',
                'admin_id'      => null
            ],
            [
                'id'            => 'inkasxpondok',
                'nama'          => 'Kas Ekskul Pondok',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'jurnalkas_id'  => 'kasxpondok',
                'admin_id'      => null
            ],
            [
                'id'            => 'inkaskurbantu',
                'nama'          => 'Kas Kurban TU',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'jurnalkas_id'  => 'kaskurbantu',
                'admin_id'      => null
            ],
        ];

        //masukkan data ke database
        foreach ($akuns as $pendapatan) {
            AkunPendapatan::create($pendapatan);
        }
    }
}
