<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Siswa;
use App\Models\UserMeta;
use App\Models\UserAlamat;

class SiswaImportController extends Controller
{

    //import data siswa by NIS
    public function import(Request $request)
    {
        $request->validate([
            'nis' => 'required|min:4',
        ]);

        $nis            = $request->nis;
        $nisn           = $request->nisn ?? null;
        $nama           = $request->nama_lengkap ?? null;
        $nama_panggilan = $request->nama_panggilan ?? null;
        $status         = $request->status ?? 'aktif';
        $tempat_lahir   = $request->tempat_lahir ?? null;
        $tanggal_masuk  = $request->tanggal_masuk ?? null;
        $jenis_kelamin  = $request->jenis_kelamin ?? null;
        $email          = $request->email ?? 'siswa' . $nis . '@example.com';

        //penyesuaian kolom
        $tanggal_lahir  = $request->tanggal_lahir ?? null;
        $tanggal_lahir  = $tanggal_lahir ? $this->convertTanggalLahir($request->tanggal_lahir) : null; //convert tanggal lahir
        $tempat_lahir   = $request->kota_kelahiran ?? $tempat_lahir;

        $siswa = Siswa::where('nis', $nis)->first();

        //jika siswa tidak ditemukan, buat siswa baru
        if (!$siswa) {
            $siswa = Siswa::create([
                'nis'               => $request->nis,
                'nisn'              => $nisn,
                'nama'              => $nama,
                'nama_panggilan'    => $nama_panggilan,
                'status'            => $status,
                'tempat_lahir'      => $tempat_lahir,
                'tanggal_lahir'     => $tanggal_lahir,
                'tanggal_masuk'     => $tanggal_masuk,
                'jenis_kelamin'     => $jenis_kelamin,
                'email'             => $email
            ]);
        } else {
            $siswa->update([
                'nisn'              => $nisn,
                'nama'              => $nama,
                'nama_panggilan'    => $nama_panggilan,
                'status'            => $status,
                'tempat_lahir'      => $tempat_lahir,
                'tanggal_lahir'     => $tanggal_lahir,
                'tanggal_masuk'     => $tanggal_masuk,
                'jenis_kelamin'     => $jenis_kelamin,
                'email'             => $email
            ]);
        };

        $user_id = $siswa->user_id;

        $respon = [
            'siswa_id'  => $siswa->id,
            'user_id'   => $user_id,
            'siswa'     => $siswa
        ];

        //proses alamat
        $alamat         = $request->alamat ?? null;
        $rt             = $request->rt ?? null;
        $rw             = $request->rw ?? null;
        $dusun          = $request->dusun ?? null;
        $kelurahan      = $request->kelurahan ?? null;
        $kecamatan      = $request->kecamatan ?? null;
        $kota           = $request->kota ?? null;
        $provinsi       = $request->provinsi ?? null;
        $kode_pos       = $request->kode_pos ?? null;
        $jenis_tinggal  = $request->jenis_tinggal ?? null;
        $transportasi   = $request->transportasi ?? null;
        $jarak          = $request->jarak ?? null;

        //penyesuaian kolom
        $kota           = $request->kota_asal ?? $kota;
        $transportasi   = $request->alat_trasportasi ?? null;

        //simpan user alamat
        $userAlamat = UserAlamat::where('user_id', $user_id)->first();
        if (!$userAlamat) {
            UserAlamat::create([
                'user_id'           => $user_id,
                'alamat'            => $alamat,
                'rt'                => $rt,
                'rw'                => $rw,
                'dusun'             => $dusun,
                'kelurahan'         => $kelurahan,
                'kecamatan'         => $kecamatan,
                'kota'              => $kota,
                'provinsi'          => $provinsi,
                'kode_pos'          => $kode_pos,
                'jenis_tinggal'     => $jenis_tinggal,
                'transportasi'      => $transportasi,
                'jarak'             => $jarak
            ]);
        } else {
            $userAlamat->update([
                'alamat'            => $alamat,
                'rt'                => $rt,
                'rw'                => $rw,
                'dusun'             => $dusun,
                'kelurahan'         => $kelurahan,
                'kecamatan'         => $kecamatan,
                'kota'              => $kota,
                'provinsi'          => $provinsi,
                'kode_pos'          => $kode_pos,
                'jenis_tinggal'     => $jenis_tinggal,
                'transportasi'      => $transportasi,
                'jarak'             => $jarak
            ]);
        };
        $respon['alamat'] = $userAlamat;

        //proses meta siswa
        foreach ($request->all() as $key => $value) {

            //skip jika value kosong
            if (empty($value) || $value === 'null') {
                continue;
            };

            //array key yang diskip
            $skipKeys = [
                'nis',
                'nisn',
                'nama_lengkap',
                'nama_panggilan',
                'status',
                'tempat_lahir',
                'tanggal_lahir',
                'tanggal_masuk',
                'jenis_kelamin',
                'email',
                'no',
                'statusrow',
                'alamat',
                'rt',
                'rw',
                'dusun',
                'kelurahan',
                'kecamatan',
                'kota',
                'provinsi',
                'kode_pos',
                'jenis_tinggal',
                'transportasi',
                'jarak',
                'alat_trasportasi',
                'kota_asal',
                'kota_kelahiran'
            ];

            //skip jika key ada di array skipKeys
            if (in_array($key, $skipKeys)) {
                continue;
            };

            //array key yang tidak sesuai dan perbaikannya
            $arrayKeyFix = [
                'ayah_pendikan'     => 'ayah_pendidikan',
                'ibu_pendikan'      => 'ibu_pendidikan',
                'wali_pendikan'     => 'wali_pendidikan',
                'nomor_registasi'   => 'nomor_registrasi',
            ];
            //perbaiki key
            if (isset($arrayKeyFix[$key])) {
                $key = $arrayKeyFix[$key];
            };

            //simpan / update user meta
            UserMeta::updateOrCreate(
                ['user_id' => $user_id, 'meta_key' => $key],
                ['meta_value' => $value]
            );

            $respon['meta'][$key] = $value;
        }

        return response()->json($respon);
    }

    //konversi tanggal lahir
    public function convertTanggalLahir($dateString)
    {
        // Array untuk mapping bulan dalam bahasa Indonesia
        $indonesianMonths = [
            'januari' => 'January',
            'februari' => 'February',
            'maret' => 'March',
            'april' => 'April',
            'mei' => 'May',
            'juni' => 'June',
            'juli' => 'July',
            'agustus' => 'August',
            'september' => 'September',
            'oktober' => 'October',
            'november' => 'November',
            'desember' => 'December',
        ];

        try {
            // Cek apakah tanggal sudah dalam format standar Y-m-d
            if (Carbon::createFromFormat('Y-m-d', $dateString)) {
                return $dateString;
            }
        } catch (\Exception $e) {
            // Jika tidak, lanjutkan ke proses konversi
        }

        try {
            // Ganti bulan dalam bahasa Indonesia dengan bahasa Inggris (jika ada)
            $dateString = strtolower($dateString); // Ubah ke lowercase untuk mempermudah pencocokan
            foreach ($indonesianMonths as $idMonth => $enMonth) {
                $dateString = str_replace($idMonth, $enMonth, $dateString);
            }

            // Coba parsing dengan beberapa format umum
            $formats = [
                'd F Y',    // Contoh: 23 January 2017
                'd-M-y',    // Contoh: 03-Apr-16
                'd/m/Y',    // Contoh: 23/01/2017
                'd-m-Y',    // Contoh: 23-01-2017
                'd M Y',    // Contoh: 23 Jan 2017
            ];

            foreach ($formats as $format) {
                try {
                    $date = Carbon::createFromFormat($format, $dateString);
                    if ($date) {
                        return $date->format('Y-m-d'); // Kembalikan dalam format standar
                    }
                } catch (\Exception $e) {
                    // Lanjutkan ke format berikutnya jika gagal
                    continue;
                }
            }
        } catch (\Exception $e) {
            // Jika semua format gagal, kembalikan null atau pesan error
            return null;
        }

        // Jika tidak ada format yang cocok
        return null;
    }
}
