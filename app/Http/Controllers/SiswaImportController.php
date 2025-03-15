<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Siswa;

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


        $tanggal_lahir  = $request->tanggal_lahir ?? null;
        $tanggal_lahir  = $tanggal_lahir ? $this->convertTanggalLahir($request->tanggal_lahir) : null;

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

        return response()->json($siswa);
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
