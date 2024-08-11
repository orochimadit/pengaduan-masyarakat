<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kecamatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kecamatan';
    protected $guarded = ['id'];

    public function users()
{
    return $this->hasMany(User::class);
}

// Relasi: Satu Kecamatan memiliki banyak Pengaduan
public function pengaduan()
{
    return $this->hasMany(Pengaduan::class);
}
}
