<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerolehanSuara extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    
      public function calons()
    {
        return $this->belongsTo(Calon::class, 'caleg_id', 'id');
    }

    public function tps(){
        return $this->belongsTo(Tps::class, 'tps_id', 'id');
    }
}
