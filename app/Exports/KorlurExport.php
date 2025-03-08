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

class KorlurExport implements FromView, ShouldAutoSize
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        $id = $this->id;

        $agent = AgentTps::where('korlur_id', $id)
            ->where('deleted', '0')
            ->withCount('anggotas')
            ->get();

        $korlur = Korlur::find($id);


        return view('layouts.korlur.excel', [
            'agent' => $agent,
            'korlur' => $korlur,
        ]);
    }
}

