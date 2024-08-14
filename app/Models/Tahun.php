<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tahun extends Model
{
    use HasFactory;
    protected $table = 'tahuns';
    protected $fillable = [
        'id',
        'tahun',
    ];
    public function tahunPenduduk() {
        return $this->hasMany(Penduduk::class, 'tahun_id', 'id');
    }
    public function tahunPenyakit() {
        return $this->hasMany(KasusPenyakit::class, 'tahun_id', 'id');
    }
}
