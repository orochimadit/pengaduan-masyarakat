<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    use HasFactory;

    protected $table = 'pengaduan';

    protected $guarded = ['id'];

    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'masyarakat_nik', 'nik');
    // }

    public function tanggapan()
    {
        return $this->hasMany(Tanggapan::class, 'id', 'pengaduan_id');
    }

    public function kecamatan()
    {
        return $this->hasMany(Kecamatan::class, 'id', 'kecamatan_id')->withTrashed();
    }
}
