<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function kelurahans()
    {
        return $this->belongsTo(Kelurahan::class, 'id', 'kecamatan_id');
    }

}
