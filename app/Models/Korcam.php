<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Korcam extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    const STATUS = [
        '0'=>'Non Aktif',
        '1'=>'Aktif', 
    ];

    public function partais()
    {
        return $this->belongsTo(Partai::class, 'partai_id', 'id');
    }

    public function kecamatans()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id', 'id');
    }

    public function kabkotas()
    {
        return $this->belongsTo(Kabkota::class, 'kabkota_id', 'id');
    }

    public function korlurs()
    {
        return $this->hasMany(Korlur::class, 'korcam_id', 'id');
    }
}
