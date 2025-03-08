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

class KorcamGeneralExport implements FromView, ShouldAutoSize
{

    public function __construct()
    {

    }

    public function view(): View
    {
        $korcam = Korcam::withCount('korlurs')->get();


        return view('layouts.korcam.generalExcel', [
            'korcam' => $korcam,
        ]);
    }
}

