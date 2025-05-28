<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //buat permission jika belum ada
        $permissions = [
            'akunpendapatan',
            'akunpengeluaran',
            'akunrekening',
            'jurnalkas',
            'kelas',
            'pegawai',
            'setting',
            'siswa',
            'tagihan',
            'tagihanmaster',
            'transaksi',
            'unitsekolah',
            'users',
            'jurnalkas',
            'kasir',
        ];
        $actions = ['create', 'read', 'update', 'delete'];

        foreach ($permissions as $permission) {
            foreach ($actions as $action) {
                $permission_name = $permission . '.' . $action;
                //check permission
                if (!Permission::where('name', $permission_name)->exists()) {
                    Permission::create(['name' => $permission_name]);
                    $this->command->info('Permission created: ' . $permission_name);
                }
            }
        }

        //buat permission view
        $permissions = [
            'neraca',
            'labarugi',
            'likuiditas',
            'aruskas',
            'perubahanmodal',
            'jurnal'
        ];
        foreach ($permissions as $permission) {
            $permission_name = $permission . '.view';
            //check permission
            if (!Permission::where('name', $permission_name)->exists()) {
                Permission::create(['name' => $permission_name]);
                $this->command->info('Permission created: ' . $permission_name);
            }
        }

        //buat default role
        $roles = [
            'admin',
            'pemilik',
            'kasir',
            'pegawai',
            'siswa',
            'guru',
            'walisiswa',
            'user',
        ];
        foreach ($roles as $role) {
            //check role
            if (!Role::where('name', $role)->exists()) {
                Role::create(['name' => $role]);
                $this->command->info('Role ' . $role . ' created');
            }

            if ($role == 'admin') {
                $role_admin = Role::where('name', $role)->first();
                $role_admin->givePermissionTo(Permission::all());
            } elseif ($role == 'pemilik') {
                $role_owner = Role::where('name', $role)->first();
                $role_owner->givePermissionTo([
                    'neraca.view',
                    'labarugi.view',
                    'likuiditas.view',
                    'aruskas.view',
                    'perubahanmodal.view',
                    'jurnal.view'
                ]);
            } elseif ($role == 'kasir') {
                $role_kasir = Role::where('name', $role)->first();
                $role_kasir->givePermissionTo([
                    'neraca.view',
                    'labarugi.view',
                    'likuiditas.view',
                    'aruskas.view',
                    'perubahanmodal.view',
                    'jurnal.view'
                ]);

                $permissions = [
                    'kelas',
                    'pegawai',
                    'siswa',
                    'tagihan',
                    'tagihanmaster',
                    'transaksi',
                    'users',
                    'kasir',
                ];
                $actions = ['create', 'read', 'update', 'delete'];

                $perms = [];
                foreach ($permissions as $permission) {
                    foreach ($actions as $action) {
                        $perms[] = $permission . '.' . $action;
                    }
                }
                $role_kasir->givePermissionTo($perms);
            } else {
                $role_user = Role::where('name', $role)->first();
            }
        }
    }
}
