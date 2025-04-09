<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyakit extends Model
{
    use HasFactory;
    protected $table = 'penyakits';
    protected $fillable = [
        'id',
        'nama_penyakit',
        'link_metabase',
        'pengertian',
        'penyebab',
        'gejala',
        'diagnosis',
        'komplikasi',
        'pengobatan',
        'pencegahan',
        'gambar',
        'sumber_informasi',
    ];
    public function namaPenyakit() {
        return $this->hasMany(KasusPenyakit::class, 'penyakit_id', 'id');
    }
}
