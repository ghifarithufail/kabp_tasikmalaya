<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tps extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function kelurahans()
    {
        return $this->belongsTo(Kelurahan::class, 'kelurahan_id', 'id');
    }

    public function kortps()
    {
        return $this->belongsToMany(AgentTps::class, 'pivot_tps', 'tps_id', 'kortps_id');
    }

    public function anggotas(){
        return $this->belongsTo(Anggota::class, 'id', 'tps_id');

    }
}
