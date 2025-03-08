<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    public function kecamatans()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id', 'id');
    }

    public function tps()
    {
        return $this->belongsTo(Tps::class, 'id', 'kelurahan_id');
    }

    public function kabkotas(){
        return $this->belongsTo(Kabkota::class, 'kabkota', 'id');

    }
}
