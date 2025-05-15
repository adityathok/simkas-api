<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\TahunAjaran;

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

        $user = $request->user();
        if ($user) {
            $response['user'] = $request->user();

            // Dapatkan semua permissions
            $permissons = $request->user()->getPermissionsViaRoles();

            //collection permissions
            $response['permissions'] = collect($permissons)->pluck('name');

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
        }

        return response()->json($response);
    }
}
