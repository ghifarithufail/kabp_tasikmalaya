<?php

namespace App\Http\Controllers;

use App\Exports\KelurahanExport;
use App\Models\Tps;
use App\Models\Korlur;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Partai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Excel;

class KelurahanController extends Controller
{
    public function report(Request $request)
    {
        $lurah = $request->input('lurah');
        $kecamatan = $request->input('kecamatan');
        $dapil = $request->input('dapil');
        $kabupaten = $request->input('kabupaten');

        $userPermissions = auth()->user()->getAllPermissions()->pluck('name')->toArray();

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

        if ($request['lurah']) {
            $kelurahans->where('kelurahans.nama_kelurahan', 'like', '%' . $request['lurah'] . '%');
        }

        if ($request['kecamatan']) {
            $kelurahans->where('kecamatans.nama', 'like', '%' . $request['kecamatan'] . '%');
        }

        if ($request['dapil']) {
            $kelurahans->where('kelurahans.dapil', $request['dapil']);
        }

        if ($request['kabupaten']) {
            $kelurahans->where('kabkotas.nama', 'like', '%' . $request['kabupaten'] . '%');
        }

        $allowedRegions = [];
        if (in_array('kota-tasikmalaya report/kelurahan', $userPermissions)) {
            $allowedRegions[] = 'TASIKMALAYA';
        }
        if (in_array('kabupaten-tasikmalaya report/kelurahan', $userPermissions)) {
            $allowedRegions[] = 'KABUPATEN TASIKMALAYA';
        }
        if (in_array('kabupaten-garut report/kelurahan', $userPermissions)) {
            $allowedRegions[] = 'GARUT';
        }
        
        if (!empty($allowedRegions)) {
            $kelurahans->whereIn('kabkotas.nama', $allowedRegions);    
        }
        
        // Mengelompokkan data berdasarkan id kelurahan, nama kelurahan, dapil, dan kabkota
        $kelurahan = $kelurahans->groupBy('kelurahans.id', 'kelurahans.nama_kelurahan', 'kelurahans.dapil', 'kabkotas.nama')->get();

        // Mengelompokkan hasil berdasarkan dapil dan kabupaten
        $kelurahansByDapil = $kelurahan->groupBy(function ($item) {
            return $item->dapil . ' - ' . $item->kabkota;
        });

        return view('layouts.kelurahan.report', [
            'kelurahansByDapil' => $kelurahansByDapil,
            'tim' => $tim,
            'request' => [
                'kabupaten' => $kabupaten,
                'lurah' => $lurah,
                'kecamatan' => $kecamatan,
                'dapil' => $dapil,
            ],
        ]);
    }


public function excel_report(){
    return Excel::download(new KelurahanExport(), 'kelurahan.xlsx');
}


    public function reportDetail($id){
        return view('layouts.kelurahan.detail-report',[
            'korlur' => Korlur::where('kelurahan_id', '=', $id)->get(),
            'kelurahan' => Kelurahan::find($id)
        ]);
    }
}
