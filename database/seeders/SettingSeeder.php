<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::set('app_name', 'Sistem Informasi Akademik Keuangan Sekolah');
        Setting::set('nama_lembaga', 'Yayasan Pendidikan Bangsa');
        Setting::set('alamat_lembaga', 'Jl. Kemerdekaan No. 45, Weru, Sukoharjo');
        Setting::set('kota_lembaga', 'Sukoharjo');
        Setting::set('pimpinan_lembaga', 'Fulan Fulana S.Pd');
        Setting::set('jenjang', ['KB', 'TK', 'SD', 'SMP', 'SMA', 'Pondok']);
        Setting::set('jabatan', [
            'Guru',
            'Kepala Sekolah',
            'Wakil Kepala Sekolah',
            'Tata Usaha',
            'Kesiswaan',
            'Sarpras',
            'Humas'
        ]);
    }
}
