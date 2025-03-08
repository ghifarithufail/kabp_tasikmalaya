<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentTps extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function partais()
    {
        return $this->belongsTo(Partai::class, 'partai_id', 'id');
    }

    public function kelurahans()
    {
        return $this->belongsTo(Kelurahan::class, 'kelurahan_id', 'id');
    }

    public function kabkotas()
    {
        return $this->belongsTo(Kabkota::class, 'kabkota_id', 'id');
    }

    public function korlurs()
    {
        return $this->belongsTo(Korlur::class, 'korlur_id', 'id');
    }

    public function anggotas()
    {
        return $this->belongsTo(Anggota::class, 'id', 'agent_id');
    }

    public function tps_pivot()
    {
        return $this->belongsToMany(Tps::class, 'pivot_agents', 'agent_tps_id', 'tps_id');
    }
}
