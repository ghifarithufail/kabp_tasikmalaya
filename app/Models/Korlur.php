<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Korlur extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function partais()
    {
        return $this->belongsTo(Partai::class, 'partai_id', 'id');
    }

    public function korcams()
    {
        return $this->belongsTo(Korcam::class, 'korcam_id', 'id');
    }

    public function kecamatans()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id', 'id');
    }

    public function kelurahans()
    {
        return $this->belongsTo(Kelurahan::class, 'kelurahan_id', 'id');
    }

    public function kabkotas()
    {
        return $this->belongsTo(Kabkota::class, 'kabkota_id', 'id');
    }

    public function agent_tps()
    {
        return $this->belongsTo(AgentTps::class, 'id', 'korlur_id');
    }

    public function agent_tps_data()
    {
        return $this->hasMany(AgentTps::class, 'korlur_id', 'id')
            ->where('deleted', '0')
            ->selectRaw('COUNT(*) as aggregate')
            ->havingRaw('COUNT(*) > 0');
    }

    public function tps(){
        return $this->belongsTo(Tps::class, 'tps_id', 'id');
    }

    public function agent_tps_total()
    {
        return $this->agent_tps()
            ->whereHas('anggotas', function ($query) {
                $query->where('deleted', 0);
            })
            ->withCount(['anggotas' => function ($query) {
                $query->where('deleted', 0);
            }]);
    }
}
