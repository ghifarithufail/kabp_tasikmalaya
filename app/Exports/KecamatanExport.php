<?php

namespace App\Exports;

use App\Models\AgentTps;
use App\Models\Anggota;
use App\Models\Kecamatan;
use App\Models\Korcam;
use App\Models\Korhan;
use App\Models\Korlur;
use App\Models\KorTps;
use App\Models\Partai;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Reservation;
use App\Models\Tps;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class KecamatanExport implements FromView, ShouldAutoSize
{

    public function __construct()
    {

    }

    public function view(): View
    {
      $partais = DB::table('partais')->select('id', 'nama')->OrderBy('id','asc')->get();


        $tim = Partai::orderBy('id','asc')->get();

        // Inisialisasi query dasar
        $kecamatans = Kecamatan::leftJoin('kelurahans', 'kelurahans.kecamatan_id', '=', 'kecamatans.id')
            ->leftJoin('tps', 'tps.kelurahan_id', '=', 'kelurahans.id')
            ->leftJoin('anggotas', 'anggotas.tps_id', '=', 'tps.id')
            ->leftJoin('agent_tps', 'agent_tps.id', '=', 'anggotas.agent_id')
            ->leftJoin('kabkotas', 'kabkotas.id', '=', 'kecamatans.kotakab_id')
            ->select(
                'kecamatans.id',
                'kecamatans.nama',
                'kabkotas.nama as kabkota',
                DB::raw('SUM(CASE WHEN tps.totdpt > 0 THEN tps.totdpt ELSE 0 END) as total_dpt'),
                DB::raw('SUM(CASE WHEN tps.target > 0 THEN tps.target ELSE 0 END) as total_target'),
                DB::raw('COUNT(anggotas.id) as total_data_anggota'),
                DB::raw('COUNT(CASE WHEN anggotas.verified = "1" THEN anggotas.id END) as total_verified_anggota'),
                'kelurahans.dapil'
            );


        // Tambahkan COUNT untuk setiap partai secara dinamis
        foreach ($partais as $partai) {
            $kecamatans->addSelect(DB::raw(
                "COUNT(CASE WHEN agent_tps.partai_id = {$partai->id} THEN anggotas.id END) as anggota_partai_{$partai->id}"
            ));
        }

        // Ambil data kecamatan dari query
        $kecamatan = $kecamatans->groupBy('kecamatans.id', 'kecamatans.nama', 'kelurahans.dapil')
        ->get();


        return view('layouts.kecamatan.excel', [
            'kecamatan' => $kecamatan,
            'tim' => $tim,
        ]);
    }
}

