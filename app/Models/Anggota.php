<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function agents()
    {
        return $this->belongsTo(AgentTps::class, 'agent_id', 'id');
    }

    public function tps()
    {
        return $this->belongsTo(Tps::class, 'tps_id', 'id');
    }

    public function kabkotas()
    {
        return $this->belongsTo(Kabkota::class, 'kabkota_id', 'id');
    }

    
}
