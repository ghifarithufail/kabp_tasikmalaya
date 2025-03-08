<?php

namespace App\Http\Controllers;

use App\Exports\KorcamExport;
use App\Exports\TpsExport;
use App\Models\Tps;
use App\Models\AgentTps;
use App\Models\Partai;
use App\Models\PivotAgent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Excel;
use Illuminate\Support\Facades\Cache;

class TpsController extends Controller
{
    public function report(Request $request)
    {
        // $partais = DB::table('partais')->select('id', 'nama')->where('deleted', '=', '0')->orderBy('id', 'asc')->get();
        $partais = Cache::remember('partais', 3600, function () {
            return DB::table('partais')
                ->select('id', 'nama')
                ->where('deleted', '=', '0')
                ->orderBy('id', 'asc')
                ->get();
        });

        // $tim = Partai::orderBy('id', 'asc')->get();
        $tim = Cache::remember('tim', 3600, function () {
            return Partai::orderBy('id', 'asc')->get();
        });

        $kelurahan = $request->input('kelurahan');
        $kecamatan = $request->input('kecamatan');
        $filter_tps = $request->input('filter_tps');

        $userPermissions = auth()->user()->getAllPermissions()->pluck('name')->toArray();

        $data = Tps::with(['kelurahans', 'kelurahans.kecamatans', 'kelurahans.kabkotas' , 'anggotas'])->leftJoin('kelurahans', 'kelurahans.id', '=', 'tps.kelurahan_id')
            ->leftJoin('kecamatans', 'kecamatans.id', '=', 'kelurahans.kecamatan_id')
            ->leftJoin('anggotas', 'anggotas.tps_id', '=', 'tps.id')
            ->leftJoin('kabkotas', 'kelurahans.kabkota', '=', 'kabkotas.id')
            ->leftJoin('agent_tps', 'agent_tps.id', '=', 'anggotas.agent_id')
            ->select(
                'tps.id',
                'tps.tps',
                'kelurahans.nama_kelurahan',
                'kecamatans.nama as kecamatan',
                'kelurahans.kabkota',
                'tps.totdpt',
                'tps.target',
                'kabkotas.nama as kota',
                'kelurahans.dapil',  // Assuming dapil is derived or present in the kecamatan
                DB::raw('COUNT(anggotas.id) as total_data_anggota'),
                DB::raw('COUNT(CASE WHEN anggotas.verified = "1" THEN anggotas.id END) as total_verified_anggota')
            )
            ->groupBy('tps.id', 'tps.tps', 'kelurahans.dapil', 'kecamatans.nama');

        foreach ($partais as $partai) {
            $data->addSelect(DB::raw(
                "COUNT(CASE WHEN agent_tps.partai_id = {$partai->id} THEN anggotas.id END) as anggota_partai_{$partai->id}"
            ));
        }


        $data->addSelect(DB::raw('SUM(tps.totdpt) as subtotal_dpt'))
            ->addSelect(DB::raw('SUM(tps.target) as subtotal_target'));


        if ($request['kecamatan']) {
            $data = $data->where('kecamatans.nama', 'like', '%' . $request['kecamatan'] . '%');
        }

        if ($request['kelurahan']) {
            $data = $data->where('kelurahans.nama_kelurahan', 'like', '%' . $request['kelurahan'] . '%');
        }

        if ($request['filter_tps']) {
            $data = $data->where('tps', $request['filter_tps']);
        }

        $allowedRegions = [];
        if (in_array('kota-tasikmalaya report/tps', $userPermissions)) {
            $allowedRegions[] = 'TASIKMALAYA';
        }
        if (in_array('kabupaten-tasikmalaya report/tps', $userPermissions)) {
            $allowedRegions[] = 'KABUPATEN TASIKMALAYA';
        }
        if (in_array('kabupaten-garut report/tps', $userPermissions)) {
            $allowedRegions[] = 'GARUT';
        }
        
        if (!empty($allowedRegions)) {
            $data->whereIn('kabkotas.nama', $allowedRegions);    
        }

        $tps = $data->paginate(20)->appends($request->all());

        return view('layouts.tps.report', [
            'tps' => $tps,
            'tim' => $tim,
            'request' => [
                'kelurahan' => $kelurahan,
                'kecamatan' => $kecamatan,
                'filter_tps' => $filter_tps,
            ],
        ]);
    }
    
    public function excel_report(){
        
        return Excel::download(new TpsExport(), 'tps.xlsx');

    }



    public function reportDetail($id)
    {
        $kortps = PivotAgent::where('tps_id', '=', $id)
            ->leftJoin('agent_tps', 'agent_tps.id', '=', 'pivot_agents.agent_tps_id')
            ->leftJoin('partais', 'partais.id', '=', 'agent_tps.partai_id')
            ->select(
                'pivot_agents.agent_tps_id',
                'pivot_agents.tps_id',
                'agent_tps.nama AS nama_agent',
                'agent_tps.nik',
                'agent_tps.phone',
                'agent_tps.tgl_lahir',
                'agent_tps.rt',
                'agent_tps.rw',
                'agent_tps.alamat',
                'agent_tps.status',
                'partais.nama AS nama_partai'
            )
            ->get();

        $tps = Tps::where('tps.id', '=', $id)->leftJoin('kelurahans', 'kelurahans.id', '=', 'tps.kelurahan_id')->select('tps.tps AS nama_tps', 'kelurahans.nama_kelurahan')->get();

        return view('layouts.tps.detail-report', [
            'kortps' => $kortps,
            'tps' => $tps
        ]);
    }
}
