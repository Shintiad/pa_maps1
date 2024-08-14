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
    ];
    public function namaPenyakit() {
        return $this->hasMany(KasusPenyakit::class, 'penyakit_id', 'id');
    }
}
