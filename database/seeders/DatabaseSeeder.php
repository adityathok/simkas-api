<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleAndPermissionsSeeder::class,
            TahunAjaranSeeder::class,
            SettingSeeder::class,
            UserSeeder::class,
            UnitSekolahSeeder::class,
            PegawaiSeeder::class,
            UnitSekolahPegawaiSeeder::class,
            KelasSeeder::class,
            JurnalKasSeeder::class,
            AkunPendapatanSeeder::class,
            AkunPengeluaranSeeder::class,
            AkunRekeningSeeder::class,
            SiswaSeeder::class,
            TagihanSeeder::class,
        ]);
    }
}
