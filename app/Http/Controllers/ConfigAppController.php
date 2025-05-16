<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\TahunAjaran;
use App\Models\JurnalKas;
use App\Models\AkunRekening;

class ConfigAppController extends Controller
{
    public function index(Request $request)
    {
        $response = [
            'app_name'          => Setting::get('app_name', 'Sistem Informasi Akademik Keuangan Sekolah'),
            'app_description'   => Setting::get('app_description', 'Sistem Informasi Akademik Keuangan Sekolah'),
            'app_logo'          => Setting::get('app_logo', ''),
            'app_favicon'       => Setting::get('app_favicon', ''),
            'app_menus'         => Setting::get('app_menus', ''),
            'lembaga'           => Setting::get('nama_lembaga', 'SIMKAS'),
            'logo'              => Setting::get('logo_lembaga', ''),
            'tahun_ajaran'      => TahunAjaran::getActive()->nama,
            "version"           => "1.0.0",
            "year"              => date('Y'),
            "date"              => date('Y/m/d'),
            "user"              => '',
            "permissions"       => '',
            'role'              => '',
        ];

        $user = $request->user()->makeHidden(['roles']);
        if ($user) {
            $response['user'] = $user;

            // Dapatkan semua permissions
            $permissons = $request->user()->getPermissionsViaRoles();

            //collection permissions
            $response['permissions'] = collect($permissons)->pluck('name');

            //dapatkan semua roles
            $roles = $request->user()->roles;
            $response['roles'] = collect($roles)->pluck('name');

            //get user role
            $role = $request->user()->roles()->first();
            $role = $role ? $role->name : null;
            $response['role'] = $role;

            //get menus by role
            $path = resource_path("menus/{$role}.json");
            if (file_exists($path)) {
                $response['app_menus'] = json_decode(file_get_contents($path));
            } else {
                $path = resource_path("menus/user.json");
                $response['app_menus'] = json_decode(file_get_contents($path));
            }

            //jika role admin
            if ($role == 'admin') {
                //get all jurnal kas
                $jurnal_kas = JurnalKas::all();
                foreach ($jurnal_kas as $key => $value) {
                    $response['app_menus'][3]->items[] = [
                        'key'       => 'laporan_jurnal_' . $value->id,
                        'label'     => $value->nama,
                        'route'     => '/laporan/jurnal/?id=' . $value->id,
                    ];
                };
                //get all akun rekening
                $akun_rekening = AkunRekening::all();
                foreach ($akun_rekening as $key => $value) {
                    $response['app_menus'][2]->items[] = [
                        'key'       => 'laporan_likuiditas_' . $value->id,
                        'label'     => 'Likuiditas ' . $value->nama,
                        'route'     => '/laporan/likuiditas/?id=' . $value->id,
                    ];
                };
            }
        }



        return response()->json($response);
    }
}
