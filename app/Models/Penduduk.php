<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penduduk extends Model
{
    use HasFactory;
    protected $table = 'penduduks';
    protected $fillable = [
        'tahun_id',
        'kecamatan_id',
        'jumlah_penduduk',
    ];
    public function tahun() {
        return $this->belongsTo(Tahun::class, 'tahun_id', 'id');
    }
    public function kecamatan() {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id', 'id');
    }
    public function scopeOrderByKecamatan($query)
    {
        return $query->join('kecamatans', 'penduduks.kecamatan_id', '=', 'kecamatans.id')
                     ->select('penduduks.*')
                     ->orderBy('kecamatans.id');
    }
}
