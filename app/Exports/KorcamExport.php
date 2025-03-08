<?php

namespace App\Exports;

use App\Models\AgentTps;
use App\Models\Anggota;
use App\Models\Korcam;
use App\Models\Korhan;
use App\Models\Korlur;
use App\Models\KorTps;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Reservation;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class KorcamExport implements FromView, ShouldAutoSize
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        $id = $this->id;

        $korlur = Korlur::where('korcam_id', $id)
            ->where('deleted', '0')
            ->with('agent_tps')
            ->withCount(['agent_tps as anggota_count' => function ($query) {
                $query->leftJoin('anggotas', 'agent_tps.id', '=', 'anggotas.agent_id')
                    ->where('anggotas.deleted', '0');
            }])
            ->get();

        $korcam = Korcam::find($id);


        return view('layouts.korcam.excel', [
            'korcam' => $korcam,
            'korlur' => $korlur,
        ]);
    }
}

