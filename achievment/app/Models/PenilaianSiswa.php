<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianSiswa extends Model
{
    use HasFactory;

    protected $table = 'penilaian_siswa';

    protected $fillable = [
        'siswa_id',
        'jenis',
        'kategori',
        'tanggal',
        'keterangan',
        'poin',
    ];

    public function siswa()
    {
        return $this->belongsTo(DataSiswa::class, 'siswa_id');
    }
}
