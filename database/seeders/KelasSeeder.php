<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UnitSekolah;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Ambil semua Unit
        $unitSekolahList = UnitSekolah::all();

        foreach ($unitSekolahList as $unitSekolah) {

            //ambil jenjang
            switch ($unitSekolah->jenjang) {
                case 'TK':
                    $jumlahKelas = 2;
                    $tingkatKelas = ['A', 'B'];
                    $subkelas = ['Kecil', 'Besar'];
                    break;
                case 'KB':
                    $jumlahKelas = 2;
                    $tingkatKelas = ['A', 'B'];
                    $subkelas = ['Kecil', 'Besar'];
                    break;
                case 'SD':
                    $jumlahKelas = 6;
                    $tingkatKelas = [1, 2, 3, 4, 5, 6];
                    $subkelas = ['A', 'B', 'C'];
                    break;
                case 'SMP':
                    $jumlahKelas = 3;
                    $tingkatKelas = [7, 8, 9];
                    $subkelas = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
                    break;
                case 'SMA':
                    $jumlahKelas = 3;
                    $tingkatKelas = [10, 11, 12];
                    $subkelas = ['A', 'B', 'C', 'D', 'E', 'F'];
                    break;
                default:
                    $jumlahKelas = 3;
                    $tingkatKelas = [1, 2, 3];
                    $subkelas = ['A'];
                    break;
            }

            for ($i = 0; $i < $jumlahKelas; $i++) {
                foreach ($subkelas as $sub) {
                    Kelas::create([
                        'unit_sekolah_id'   => $unitSekolah->id,
                        'tingkat'           => $tingkatKelas[$i],
                        'tahun_ajaran'      => date('Y', strtotime('-1 year')) . '_' . date('Y'),
                        'nama'              => $tingkatKelas[$i] . ' ' . $sub
                    ]);
                }
            }
        }
    }
}
