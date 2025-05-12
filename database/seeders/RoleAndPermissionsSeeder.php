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
            'page-dashboard',
            'page-users',
            'page-settings',
            'page-pengaturan',
            'page-pegawai',
            'page-siswa',
            'page-akunrekening',
            'page-jurnalkas',
            'page-akunpengeluaran',
            'page-akunpendapatan',
            'page-tagihan',
            'page-tagihanmaster',
            'page-transaksi',
            'page-kasir',
            'page-jurnalkas',
            'page-neraca',
            'view-other-user',
            'edit-other-user',
            'create-other-user',
            'edit-user',
            'delete-user',
            'edit-settings',
        ];
        foreach ($permissions as $permission) {
            //check permission
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission]);
                $this->command->info('Permission created: ' . $permission);
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
                    'page-dashboard',
                    'page-users',
                    'page-settings',
                    'page-pengaturan',
                    'page-pegawai',
                    'page-siswa',
                    'page-akunrekening',
                    'page-jurnalkas',
                    'page-akunpengeluaran',
                    'page-akunpendapatan',
                    'page-tagihan',
                    'page-tagihanmaster',
                    'page-transaksi',
                    'page-kasir',
                    'edit-user',
                    'delete-user',
                    'page-jurnalkas',
                    'page-neraca',
                ]);
            } elseif ($role == 'kasir') {
                $role_kasir = Role::where('name', $role)->first();
                $role_kasir->givePermissionTo([
                    'page-dashboard',
                    'page-kasir',
                    'page-transaksi',
                    'page-jurnalkas',
                    'page-neraca',
                    'edit-user',
                    'delete-user',
                ]);
            } else {
                $role_user = Role::where('name', $role)->first();
                $role_user->givePermissionTo([
                    'page-dashboard',
                    'edit-user',
                    'delete-user',
                ]);
            }
        }
    }
}
