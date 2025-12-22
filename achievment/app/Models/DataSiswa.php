<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class DataSiswa extends Model
{

     protected $table = 'data_siswa'; // ← SESUAI NAMA TABEL ASLI

    protected $fillable = [
        'user_id',
        'nama',
        'nis',
        'kelas',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function PenilaianSiswa()
{
    return $this->hasMany(PenilaianSiswa::class, 'siswa_id');
}


    /* ================= ACCESSOR ================= */

    public function getTotalPoinAttribute()
    {
        return $this->penilaianSiswa->sum('poin');
    }
}
