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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AnggotaGeneralExport implements FromView, ShouldAutoSize
{

    public function __construct()
    {

    }

    public function view(): View
    {
            $anggota = Anggota::orderBy('created_at', 'desc')->where('deleted', '0')->get();
            

            return view('layouts.anggota.excel', [
                'anggota' => $anggota
            ]);
    }
}

