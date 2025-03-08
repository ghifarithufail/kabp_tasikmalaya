<?php

namespace App\Exports;

use App\Models\AgentTps;
use App\Models\Anggota;
use App\Models\Korhan;
use App\Models\Korlur;
use App\Models\KorTps;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Reservation;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AgentExport implements FromView, ShouldAutoSize
{
    protected $id;
    protected $tps;

    public function __construct($id, $tps)
    {
        $this->id = $id;
        $this->tps = $tps;
    }

    public function view(): View
    {
        $id = $this->id;
        $tps = $this->tps;

        $anggota = Anggota::where('agent_id', $id)
            ->where('tps_id', $tps)
            ->where('verified', '1')
            // ->with('kabkotas', 'tps', 'koordinators')
            ->where('deleted', '0')->get();
            
             $anggota_gagal = Anggota::where('agent_id', $id)
            ->where('tps_id', $tps)
            ->where('verified', '2')
            // ->with('kabkotas', 'tps', 'koordinators')
            ->where('deleted', '0')->get();

        // dd($anggota);

        $agent = AgentTps::find($id);

        $lolos = Anggota::where('agent_id', $id)
            ->where('tps_id', $tps)
            ->where('verified', '1')

            // ->with('kabkotas', 'tps', 'koordinators')
            ->where('deleted', '0')->count();

        $gagal = Anggota::where('agent_id', $id)
        ->where('tps_id', $tps)
        ->where('verified', '2')

        // ->with('kabkotas', 'tps', 'koordinators')
        ->where('deleted', '0')->count();


        return view('layouts.agent_tps.excel', [
            'agent' => $agent,
            'lolos' => $lolos,
            'gagal' => $gagal,
            'anggota' => $anggota,
            'anggota_gagal' => $anggota_gagal,
        ]);
    }
}

