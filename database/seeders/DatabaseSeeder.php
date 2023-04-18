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
            'nik' => '3525015201880002',
            'name' => 'Muhammad Rajaswa Raihanu Bhamakerti',
            'username' => 'rajaswa',
            'email' => 'rajaswa@example.com',
            'password' => Hash::make('12345678'),
            'telp' => '081311888098',
            'lvl' => 'admin',
        ]);

        User::create([
            'nik' => '3525011506830001',
            'name' => 'Muhammad Hilmy',
            'username' => 'hilmy',
            'email' => 'muhil@example.com',
            'password' => Hash::make('12345678'),
            'telp' => '081311223344',
            'lvl' => 'admin',
        ]);

        User::create([
            'nik' => '3525010510930001',
            'name' => 'Ahmad Bambang Hermawan',
            'username' => 'abah',
            'email' => 'abah@example.com',
            'password' => Hash::make('12345678'),
            'telp' => '081317152014',
            'lvl' => 'petugas',
        ]);

        User::create([
            'nik' => fake('id_ID')->nik(),
            'name' => 'Muhammad Alif Febrianto',
            'username' => 'alif',
            'email' => 'alif@example.com',
            'password' => Hash::make('12345678'),
            'telp' => '081317152015',
            'lvl' => 'masyarakat',
        ]);

        User::create([
            'nik' => fake('id_ID')->nik(),
            'name' => 'Rido Dwi Sang Aji',
            'username' => 'rido',
            'email' => 'rido@example.com',
            'password' => Hash::make('12345678'),
            'telp' => '081317152016',
            'lvl' => 'masyarakat',
        ]);

        User::create([
            'nik' => fake('id_ID')->nik(),
            'name' => 'Budi Setia',
            'username' => 'budi',
            'email' => 'budisetia@example.com',
            'password' => Hash::make('12345678'),
            'telp' => '081317152017',
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
