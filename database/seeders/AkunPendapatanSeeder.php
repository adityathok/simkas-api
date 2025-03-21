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
                'id'            => 'INKASNERACA',
                'nama'          => 'Kas Neraca',
                'neraca'        => true,
                'jurnal_khusus' => false,
                'jurnalkas_id'  => null,
                'admin_id'      => null
            ],
            [
                'id'            => 'INTLAMPAU',
                'nama'          => 'Tunggakan Lampau',
                'neraca'        => false,
                'jurnal_khusus' => false,
                'jurnalkas_id'  => null,
                'admin_id'      => null
            ],
            [
                'id'            => 'INOPRSNL',
                'nama'          => 'Operasional',
                'neraca'        => false,
                'jurnal_khusus' => false,
                'jurnalkas_id'  => null,
                'admin_id'      => null
            ],
            [
                'id'            => 'INSPPEN',
                'nama'          => 'SPP Pendidikan',
                'neraca'        => false,
                'jurnal_khusus' => false,
                'jurnalkas_id'  => null,
                'admin_id'      => null
            ],
            [
                'id'            => 'INDAFTAR',
                'nama'          => 'Pendaftaran',
                'neraca'        => false,
                'jurnal_khusus' => false,
                'jurnalkas_id'  => null,
                'admin_id'      => null
            ],
            [
                'id'            => 'INHUTANG',
                'nama'          => 'Hutang',
                'neraca'        => true,
                'jurnal_khusus' => true,
                'jurnalkas_id'  => 'JHUTANG',
                'admin_id'      => null
            ],
            [
                'id'            => 'INBOS',
                'nama'          => 'Dana Bantuan Negara BOS',
                'neraca'        => true,
                'jurnal_khusus' => true,
                'jurnalkas_id'  => 'JBOS',
                'admin_id'      => null
            ],
            [
                'id'            => 'INTABUNGAN',
                'nama'          => 'Tabungan',
                'neraca'        => true,
                'jurnal_khusus' => true,
                'jurnalkas_id'  => 'JTABUNGAN',
                'admin_id'      => null
            ],
            [
                'id'            => 'INBUKU',
                'nama'          => 'Buku',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'jurnalkas_id'  => 'JBUKU',
                'admin_id'      => null
            ],
            [
                'id'            => 'INSERAGAM',
                'nama'          => 'Seragam',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'jurnalkas_id'  => 'JSERAGAM',
                'admin_id'      => null
            ],
            [
                'id'            => 'INSARPRAS',
                'nama'          => 'Sarpras',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'jurnalkas_id'  => 'JSARPRAS',
                'admin_id'      => null
            ],
            [
                'id'            => 'INGEDUNG',
                'nama'          => 'Gedung',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'jurnalkas_id'  => 'JGEDUNG',
                'admin_id'      => null
            ],
            [
                'id'            => 'ININFAQ',
                'nama'          => 'Donasi Infaq Dakwah Sosial',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'jurnalkas_id'  => 'JINFAQ',
                'admin_id'      => null
            ],
            [
                'id'            => 'INPIUTANGP',
                'nama'          => 'Piutang Pegawai',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'jurnalkas_id'  => 'JPIUTANGP',
                'admin_id'      => null
            ],
            [
                'id'            => 'INKONSUMSI',
                'nama'          => 'Konsumsi',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'jurnalkas_id'  => 'JKONSUMSI',
                'admin_id'      => null
            ],
            [
                'id'            => 'INBKOPERASI',
                'nama'          => 'Bisnis Koperasi',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'jurnalkas_id'  => 'JBKOPERASI',
                'admin_id'      => null
            ],
            [
                'id'            => 'INBHEXAAIR',
                'nama'          => 'Bisnis Hexa Air',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'jurnalkas_id'  => 'JBHEXAAIR',
                'admin_id'      => null
            ],
            [
                'id'            => 'INKASXPONDOK',
                'nama'          => 'Kas Ekskul Pondok',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'jurnalkas_id'  => 'JKASXPONDOK',
                'admin_id'      => null
            ],
            [
                'id'            => 'INKASKURBANTU',
                'nama'          => 'Kas Kurban TU',
                'neraca'        => false,
                'jurnal_khusus' => true,
                'jurnalkas_id'  => 'JKASKURBANTU',
                'admin_id'      => null
            ],
        ];

        //masukkan data ke database
        foreach ($akuns as $pendapatan) {
            AkunPendapatan::create($pendapatan);
        }
    }
}
