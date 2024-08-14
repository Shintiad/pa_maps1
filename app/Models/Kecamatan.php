<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;
    protected $table = 'kecamatans';
    protected $fillable = [
        'id',
        'nama_kecamatan',
    ];
    public function kecamatanPenduduk() {
        return $this->hasMany(Penduduk::class, 'tahun_id', 'id');
    }
    public function kecamatanPenyakit() {
        return $this->hasMany(KasusPenyakit::class, 'penyakit_id', 'id');
    }
}
