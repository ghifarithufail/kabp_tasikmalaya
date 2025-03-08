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

class KorlurGeneralExport implements FromView, ShouldAutoSize
{

    public function __construct()
    {

    }

    public function view(): View
    {
        $korlur = Korlur::where('deleted','0')
        ->where('status','1')
        ->whereHas('korcams', function ($q){
            $q->where('deleted', '0');
        })
        ->withCount('agent_tps_data')->orderBy('created_at', 'desc')->get();


        return view('layouts.korlur.general_excel', [
            'korlur' => $korlur,
        ]);
    }
}

