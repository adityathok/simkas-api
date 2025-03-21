<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AkunPengeluaran;

class AkunPengeluaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //array akun pengeluaran
        $akuns = [
            [
                'id'            => 'OUTHABISPK',
                'nama'          => 'Peralatan Habis Pakai',
                'sumber'        => 'kasneraca',
                'pendapatan_id' => 'INKASNERACA',
                'admin_id'      => null,
            ],
            [
                'id'            => 'OUTOPERASIONAL',
                'nama'          => 'Operasional lain-lain',
                'sumber'        => 'kasneraca',
                'pendapatan_id' => 'INKASNERACA',
                'admin_id'      => null,
            ],
            [
                'id'            => 'OUTMUTASIKONSUMSI',
                'nama'          => 'Mutasi Konsumsi',
                'sumber'        => 'kasneraca',
                'pendapatan_id' => 'INKASNERACA',
                'admin_id'      => null,
            ],
            [
                'id'            => 'OUTKULIAHGURU',
                'nama'          => 'Biaya Kuliah Guru',
                'sumber'        => 'kasneraca',
                'pendapatan_id' => 'INKASNERACA',
                'admin_id'      => null,
            ],
            [
                'id'            => 'OUTGAJI',
                'nama'          => 'Gaji',
                'sumber'        => 'kasneraca',
                'pendapatan_id' => 'INKASNERACA',
                'admin_id'      => null,
            ],
            [
                'id'            => 'OUTTRANS',
                'nama'          => 'Transport',
                'sumber'        => 'kasneraca',
                'pendapatan_id' => 'INKASNERACA',
                'admin_id'      => null,
            ],
            [
                'id'            => 'OUTASROBAT',
                'nama'          => 'Asuransi berobat',
                'sumber'        => 'kasneraca',
                'pendapatan_id' => 'INKASNERACA',
                'admin_id'      => null,
            ],
            [
                'id'            => 'OUTHUTANG',
                'nama'          => 'Pembayaran Hutang',
                'sumber'        => 'jurnalkas',
                'pendapatan_id' => 'INHUTANG',
                'admin_id'      => null,
            ],
            [
                'id'            => 'OUTBOS',
                'nama'          => 'Dana Bantuan Negara BOS',
                'sumber'        => 'jurnalkas',
                'pendapatan_id' => 'INBOS',
                'admin_id'      => null,
            ],
            [
                'id'            => 'OUTTABUNGAN',
                'nama'          => 'Pengambilan Tabungan',
                'sumber'        => 'jurnalkas',
                'pendapatan_id' => 'INTABUNGAN',
                'admin_id'      => null,
            ],
            [
                'id'            => 'OUTINFAQ',
                'nama'          => 'Penyaluran Infaq Dakwah dan Sosial',
                'sumber'        => 'jurnalkas',
                'pendapatan_id' => 'ININFAQ',
                'admin_id'      => null,
            ],
            [
                'id'            => 'OUTPIUTANGP',
                'nama'          => 'Pencairan piutang pegawai',
                'sumber'        => 'jurnalkas',
                'pendapatan_id' => 'INPIUTANGP',
                'admin_id'      => null,
            ],
            [
                'id'            => 'OUTKONSUMSI',
                'nama'          => 'Konsumsi',
                'sumber'        => 'jurnalkas',
                'pendapatan_id' => 'INKONSUMSI',
                'admin_id'      => null,
            ],
            [
                'id'            => 'OUTBUKU',
                'nama'          => 'Pembayaran Buku',
                'sumber'        => 'jurnalkas',
                'pendapatan_id' => 'INBUKU',
                'admin_id'      => null,
            ],
            [
                'id'            => 'OUTSERAGAM',
                'nama'          => 'Pembayaran Seragam',
                'sumber'        => 'jurnalkas',
                'pendapatan_id' => 'INSERAGAM',
                'admin_id'      => null,
            ],
            [
                'id'            => 'OUTSARPRAS',
                'nama'          => 'Sarpras',
                'sumber'        => 'jurnalkas',
                'pendapatan_id' => 'INSARPRAS',
                'admin_id'      => null,
            ],
            [
                'id'            => 'OUTGEDUNG',
                'nama'          => 'Gedung',
                'sumber'        => 'jurnalkas',
                'pendapatan_id' => 'INGEDUNG',
                'admin_id'      => null,
            ],
            [
                'id'            => 'OUTBKOPERASI',
                'nama'          => 'Bisnis Koperasi',
                'sumber'        => 'jurnalkas',
                'pendapatan_id' => 'INBKOPERASI',
                'admin_id'      => null,
            ],
            [
                'id'            => 'OUTBHEXAAIR',
                'nama'          => 'Bisnis Koperasi',
                'sumber'        => 'jurnalkas',
                'pendapatan_id' => 'INBHEXAAIR',
                'admin_id'      => null,
            ],
            [
                'id'            => 'OUTKASXPONDOK',
                'nama'          => 'Kas Ekskul Pondok',
                'sumber'        => 'jurnalkas',
                'pendapatan_id' => 'INKASXPONDOK',
                'admin_id'      => null,
            ],
            [
                'id'            => 'OUTKASKURBANTU',
                'nama'          => 'Kas Kurban TU',
                'sumber'        => 'jurnalkas',
                'pendapatan_id' => 'INKASKURBANTU',
                'admin_id'      => null,
            ],
        ];

        //masukkan data ke database
        foreach ($akuns as $akun) {
            AkunPengeluaran::create($akun);
        }
    }
}
