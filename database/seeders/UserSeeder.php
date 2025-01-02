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
            'tanggal_lahir' => fake()->date('Y-m-d', '1990-01-01'),
            'tanggal_masuk' => fake()->date('Y-m-d', '1990-01-01'),
            'jenis_kelamin' => 'Laki-laki',
            'email'         => $user->email,
            'user_id'       => $user->id
        ]);

        // Generate 3 kasir        
        for ($i = 1; $i <= 3; $i++) {
            $user = User::create([
                'name'              => 'kasir' . $i,
                'email'             => 'kasir' . $i . '@example.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('12345678'),
                'type'              => 'pegawai',
                'can_login'         => true,
                'remember_token'    => Str::random(10),
            ]);
            $user->assignRole(['kasir', 'pegawai']);
            Pegawai::create([
                'nip'           => '111111110' . $i,
                'nama'          => 'kasir' . $i,
                'status'        => 'Aktif',
                'tempat_lahir'  => fake()->city(),
                'tanggal_lahir' => fake()->date('Y-m-d', '1990-01-01'),
                'tanggal_masuk' => fake()->date('Y-m-d', '1990-01-01'),
                'jenis_kelamin' => fake()->randomElement(['Laki-laki', 'Perempuan']),
                'email'         => $user->email,
                'user_id'       => $user->id
            ]);
        }
    }
}
