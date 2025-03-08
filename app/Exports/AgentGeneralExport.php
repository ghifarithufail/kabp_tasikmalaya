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

class AgentGeneralExport implements FromView, ShouldAutoSize
{

    public function __construct() {}

    public function view(): View
    {
        $agents = DB::table('agent_tps AS at')
            ->select(
                'at.nama AS nama_koordinator',
                'at.phone',
                't.id AS id_tps',
                'at.id',
                'at.nik',
                'kk.nama',
                'at.tgl_lahir',
                'at.alamat',
                'at.rt',
                'at.rw',
                'k.nama_kelurahan',
                'k.kabkota',
                'kh.nama AS nama_korhan',
                'at.keterangan',
                't.tps AS tps',
                'at.status',
                'at.partai_id',
                't.target AS target',
                DB::raw('COUNT(a.id) AS belum'),
                DB::raw('COUNT(CASE WHEN a.verified = "1" THEN 1 ELSE NULL END) AS berhasil')
            )
            ->leftJoin('kelurahans AS k', 'at.kelurahan_id', '=', 'k.id')
            ->leftJoin('korlurs AS kh', 'kh.id', '=', 'at.korlur_id')
            ->leftJoin('anggotas AS a', 'at.id', '=', 'a.agent_id')
            ->leftJoin('pivot_agents AS pa', 'at.id', '=', 'pa.agent_tps_id')
            ->leftJoin('tps AS t', 'pa.tps_id', '=', 't.id')
            ->leftJoin('kabkotas AS kk', 'at.kabkota_id', '=', 'kk.id')
            ->where('at.deleted', '0')
            ->groupBy(
                'at.nama',
                'at.phone',
                't.id',
            );

            if(Auth::user()->role != '1'){
                $agents->where('at.partai_id',Auth::user()->partai);
            }

            $agent = $agents->get();

        return view('layouts.agent_tps.general_excel', [
            'agent' => $agent
        ]);
    }
}
