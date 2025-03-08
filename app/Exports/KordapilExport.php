<?php

namespace App\Exports;

use App\Models\Anggota;
use App\Models\KorTps;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class KordapilExport implements FromView, ShouldAutoSize
{
    protected $request;

    public function __construct($request)
    {

        $this->request = $request;
    
    }

    public function view(): View
    {
        $request = $this->request;

        $date_start = $request->input('date_start');
        $date_end = $request->input('date_end');
        $dapil = $request->input('dapil');
        $kota = $request->input('kota');

        $datas = DB::table('kelurahans as k')
        ->leftJoin('tpsrws as t', 'k.id', '=', 't.kelurahan_id')
        ->leftJoin('anggotas as a', 'a.tpsrw_id', '=', 't.id')
        ->select('k.kabkota', 'k.dapil', 'k.kecamatan', 'k.nama_kelurahan', 't.tps', DB::raw('COUNT(a.nik) as jumlah_anggota'))
        ->groupBy('k.kabkota', 'k.dapil', 'k.kecamatan', 'k.nama_kelurahan', 't.tps')
        ->havingRaw('COUNT(a.nik) > 0')
        ->orderBy('k.kabkota','asc')
        ->orderBy('t.tps','asc');

        if ($request['kota']) {
            $datas->where('k.kabkota','like', '%'.$request['kota'].'%');
        }

        if ($request['dapil']) {
            $datas->where('k.dapil','like', '%'.$request['dapil'].'%');
        }

        if ($request['date_start']) {
            $datas->whereDate('a.created_at', '>=', $request['date_start']);
        }
        
        if ($request['date_end']) {
            $datas->whereDate('a.created_at', '<=', $request['date_end']);
        }

        $data = $datas->get();

        
        return view('kordapil.excel', [
            'request' => [
                'date_start' => $date_start,
                'date_end' => $date_end,
                'dapil' => $dapil,
                'kota' => $kota,
            ],
            'data' => $data
        ]);
    }
}

