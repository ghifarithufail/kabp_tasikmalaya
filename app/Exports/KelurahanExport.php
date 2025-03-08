<?php

namespace App\Exports;

use App\Models\AgentTps;
use App\Models\Anggota;
use App\Models\Kelurahan;
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

class KelurahanExport implements FromView, ShouldAutoSize
{

    public function __construct() {}

    public function view(): View
    {
        
        $partais = DB::table('partais')->select('id', 'nama')->where('deleted', '=', '0')->orderBy('id', 'asc')->get();
        $tim = Partai::orderBy('id', 'asc')->get();

        $kelurahans = Kelurahan::leftJoin('kecamatans', 'kecamatans.id', '=', 'kelurahans.kecamatan_id')
            ->leftJoin('tps', 'tps.kelurahan_id', '=', 'kelurahans.id')
            ->leftJoin('anggotas', 'anggotas.tps_id', '=', 'tps.id')
            ->leftJoin('agent_tps', 'agent_tps.id', '=', 'anggotas.agent_id')
            ->leftJoin('kabkotas', 'kabkotas.id', '=', 'kecamatans.kotakab_id')
            ->select(
                'kelurahans.id',
                'kelurahans.nama_kelurahan',
                'kecamatans.nama as kecamatan',
                'kabkotas.nama as kabkota', // Mendapatkan kabkota
                'kelurahans.dapil',
                DB::raw('SUM(CASE WHEN tps.totdpt > 0 THEN tps.totdpt ELSE 0 END) as total_dpt'),
                DB::raw('SUM(CASE WHEN tps.target > 0 THEN tps.target ELSE 0 END) as total_target'),
                DB::raw('COUNT(anggotas.id) as total_data_anggota'),
                DB::raw('COUNT(CASE WHEN anggotas.verified = "1" THEN anggotas.id END) as total_verified_anggota')
            );

        foreach ($partais as $partai) {
            $kelurahans->addSelect(DB::raw(
                "COUNT(CASE WHEN agent_tps.partai_id = {$partai->id} THEN anggotas.id END) as anggota_partai_{$partai->id}"
            ));
        }
        
        // Mengelompokkan data berdasarkan id kelurahan, nama kelurahan, dapil, dan kabkota
        $kelurahan = $kelurahans->groupBy('kelurahans.id', 'kelurahans.nama_kelurahan', 'kelurahans.dapil', 'kabkotas.nama')->get();

        // Mengelompokkan hasil berdasarkan dapil dan kabupaten
        $kelurahansByDapil = $kelurahan->groupBy(function ($item) {
            return $item->dapil . ' - ' . $item->kabkota;
        });

        return view('layouts.kelurahan.excel', [
            'kelurahansByDapil' => $kelurahansByDapil,
            'tim' => $tim,
        ]);
    }
}
