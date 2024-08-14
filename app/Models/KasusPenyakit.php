<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasusPenyakit extends Model
{
    use HasFactory;
    protected $table = 'kasus_penyakits';
    protected $fillable = [
        'tahun_id',
        'kecamatan_id',
        'penyakit_id',
        'terjangkit',
    ];
    public function tahunKasus() {
        return $this->belongsTo(Tahun::class, 'tahun_id', 'id');
    }
    public function kecamatanKasus() {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id', 'id');
    }
    public function penyakitKasus() {
        return $this->belongsTo(Penyakit::class, 'penyakit_id', 'id');
    }
    public function scopeOrderByKecamatan($query)
    {
        return $query->join('kecamatans', 'penduduks.kecamatan_id', '=', 'kecamatans.id')
                     ->select('penduduks.*')
                     ->orderBy('kecamatans.id');
    }
    public function scopeOrderByPenyakit($query)
    {
        return $query->join('penyakits', 'kasus_penyakits.penyakit_id', '=', 'penyakits.id')
                     ->select('kasus_penyakits.*')
                     ->orderBy('penyakits.id');
    }
}
