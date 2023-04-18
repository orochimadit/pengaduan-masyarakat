<?php

namespace Database\Factories;

use App\Models\Kecamatan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pengaduan>
 */
class PengaduanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user_nik = User::inRandomOrder()->first();

        return [
            'tgl_pengaduan' => fake()->date(),
            'masyarakat_nik' => $user_nik->nik,
            'kecamatan_id' => fake()->randomElement(Kecamatan::all('id')),
            'judul' => fake()->sentence(3),
            'isi_laporan' => fake()->sentence(10),
            'foto' => fake()->randomElement(['erupsi.jpg', 'kebakaran.jpg', 'longsor.jpg', 'bencana.jpg']),
            'status' => fake()->randomElement(['0', '1', '2']),
        ];
    }
}
