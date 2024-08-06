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
            'name' => 'candra',
            'username' => 'candra',
            'email' => 'candra@isb.com',
            'password' => Hash::make('12345678'),
            'telp' => '111222211',
            'lvl' => 'admin',
        ]);

        User::factory(20)->create();

        Kecamatan::create([
            'kecamatan' => 'Bengkayang',
        ]);

        Kecamatan::create([
            'kecamatan' => 'Ledo',
        ]);

        Kecamatan::create([
            'kecamatan' => 'Seluas',
        ]);

        Kecamatan::create([
            'kecamatan' => 'Jagoi Babang',
        ]);

        Kecamatan::create([
            'kecamatan' => 'Montrado',
        ]);

        Kecamatan::create([
            'kecamatan' => 'Sungai Raya',
        ]);

        Kecamatan::create([
            'kecamatan' => 'bumi Emas',
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
