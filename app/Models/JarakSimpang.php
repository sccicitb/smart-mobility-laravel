<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JarakSimpang extends Model
{
    protected $table = 'jarak_simpang';

    protected $fillable = [
        'ID_Simpang',
        'dari_arah',
        'ke_arah',
        'jarak_km',
        'lebar_jalan',
        'nama_ruas',
        'keterangan',
        'status'
    ];

    // Relasi ke Simpang
    public function simpang()
    {
        return $this->belongsTo(Simpang::class, 'ID_Simpang', 'id');
    }
}
