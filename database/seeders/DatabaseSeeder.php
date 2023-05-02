<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Kecamatan;
use App\Models\Pengaduan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        
        User::create([
            'nik' => fake('id_ID')->nik(),
            'name' => 'Budi Setia',
            'username' => 'budi',
            'email' => 'budisetia@example.com',
            'password' => Hash::make('12345678'),
            'telp' => '111222211',
            'lvl' => 'masyarakat',
        ]);

        User::factory(20)->create();

        Kecamatan::create([
            'kecamatan' => 'Ciputat',
        ]);

        Kecamatan::create([
            'kecamatan' => 'Ciputat Timur',
        ]);

        Kecamatan::create([
            'kecamatan' => 'Pamulang',
        ]);

        Kecamatan::create([
            'kecamatan' => 'Pondok Aren',
        ]);

        Kecamatan::create([
            'kecamatan' => 'Serpong',
        ]);

        Kecamatan::create([
            'kecamatan' => 'Serpong Utara',
        ]);

        Kecamatan::create([
            'kecamatan' => 'Setu',
        ]);

        // Pengaduan::factory(50)->create();

        $users = User::all();
        foreach ($users as $index => $user) {
            for ($i = 1; $i <= 20; $i++) {
                $user_nik = User::inRandomOrder()->first();
                $kecamatan_id = Kecamatan::inRandomOrder()->first();
                Pengaduan::create([
                    'tgl_pengaduan' => fake()->date(),
                    'masyarakat_nik' => $user->nik,
                    'kecamatan_id' => $kecamatan_id->id,
                    'judul' => fake()->sentence(3),
                    'isi_laporan' => fake()->sentence(10),
                    'foto' => fake()->randomElement(['erupsi.jpg', 'kebakaran.jpg', 'longsor.jpg', 'bencana.jpg', 'berlubang.jpeg']),
                    'status' => fake()->randomElement(['0', '1', '2']),
                ]);
            }
        }
    }
}
