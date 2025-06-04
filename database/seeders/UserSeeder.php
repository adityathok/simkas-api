<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Pegawai;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //default admin users
        $user = User::create([
            'name'              => 'admin',
            'email'             => 'admin@example.com',
            'username'          => 'admin',
            'email_verified_at' => now(),
            'password'          => Hash::make('12345678'),
            'type'              => 'pegawai',
            'can_login'         => true,
            'remember_token'    => Str::random(10),
        ]);
        $user->assignRole(['admin', 'pegawai']);
        Pegawai::create([
            'nip'           => '1111111111',
            'nama'          => 'admin',
            'status'        => 'Aktif',
            'tempat_lahir'  => 'Sukoharjo',
            'tanggal_lahir' => '1990-01-01',
            'tanggal_masuk' => '1990-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'email'         => $user->email,
            'user_id'       => $user->id
        ]);

        //kasir
        $user = User::create([
            'name'              => 'kasir1',
            'email'             => 'kasir1@example.com',
            'email_verified_at' => now(),
            'password'          => Hash::make('12345678'),
            'type'              => 'pegawai',
            'can_login'         => true,
            'remember_token'    => Str::random(10),
        ]);
        $user->assignRole(['kasir', 'pegawai']);
        Pegawai::create([
            'nip'           => '111111110',
            'nama'          => 'Kasir',
            'status'        => 'Aktif',
            'tempat_lahir'  => 'Sukoharjo',
            'tanggal_lahir' => '1990-01-01',
            'tanggal_masuk' => '1990-01-01',
            'jenis_kelamin' => 'Perempuan',
            'email'         => $user->email,
            'user_id'       => $user->id
        ]);
    }
}
