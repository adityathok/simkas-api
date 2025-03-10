<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'pemilik']);
        Role::create(['name' => 'kasir']);
        Role::create(['name' => 'pegawai']);
        Role::create(['name' => 'siswa']);
        Role::create(['name' => 'guru']);
        Role::create(['name' => 'walisiswa']);
    }
}
