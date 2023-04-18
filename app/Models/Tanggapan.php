<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tanggapan extends Model
{
    use HasFactory;

    protected $table = 'tanggapan';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'petugas_id', 'nik');
    }
}
