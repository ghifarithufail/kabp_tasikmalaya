<?php

namespace App\Http\Controllers;

use App\Models\AgentTps;
use App\Models\Calon;
use App\Models\Dpt;
use App\Models\Kabkota;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Korcam;
use App\Models\Korlur;
use App\Models\PerolehanSuara;
use App\Models\Tps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DataController extends Controller
{
    public function getKecamatans(Request $request)
    {
        $kecamatan = [];
        // if (Auth::user()->role == '1') {
            $kecamatan = Kecamatan::where('nama', 'LIKE', "%$request->name%")
                ->get();
        // } else {
        //     $kecamatan = Kecamatan::where('nama', 'LIKE', "%$request->name%")
        //         ->whereHas('kelurahans', function ($kelurahans) {
        //             $kelurahans->where('id', Auth::user()->kelurahan_id);
        //         })
        //         ->get();
        // }
        return response()->json($kecamatan);
    }

    public function getKabkotas(Request $request)
    {
        $kabkota = [];
        if ($search = $request->name) {
            $kabkota = Kabkota::where('nama', 'LIKE', "%$search%")->get();
        }
        return response()->json($kabkota);
    }

    public function getKabkotaById($id)
{
    // Cari kabkota berdasarkan id
    $kabkota = Kabkota::find($id);

    // Jika kabkota tidak ditemukan, kembalikan respon kosong atau not found
    if (!$kabkota) {
        return response()->json(['error' => 'Kabkota not found'], 404);
    }

    // Kembalikan data kabkota dalam format JSON
    return response()->json($kabkota);
}


    public function getKorcams(Request $request)
    {
        $korcam = [];
        if ($search = $request->name) {
            $korcam = Korcam::with('kecamatans')->where('nama', 'LIKE', "%$search%")
                ->where('deleted', '0')
                ->where('status', 1)
                ->get();
        }
        return response()->json($korcam);
    }

    public function getKorlurs(Request $request)
    {
        $korlur = [];
        if ($search = $request->name) {
            $korlur = Korlur::with('kelurahans.tps')->where('nama', 'LIKE', "%$search%")
                ->where('deleted', '0')
                ->where('status', 1)
                ->get();
        }
        return response()->json($korlur);
    }

    public function getAgentTps(Request $request)
    {
        $korlur = [];
        $search = $request->name;

        // if ($search = $request->name) {
        // if (Auth::user()->role == '1') {
            if ($search = $request->name) {
                $korlur = AgentTps::with('kelurahans.kecamatans')
                    // ->whereHas('kelurahans', function ($query) {
                    //     $query->where('kabkota', 'like', '%' . 'kota tasikmalaya' . '%');
                    // })
                    ->where('nama', 'LIKE', "%$search%")
                    ->where('deleted', '0')
                    ->where('status', 1)
                    // ->where('partai_id', Auth::user()->partai)
                    ->get();
            }
        // } else {
        //     $korlur = AgentTps::with('kelurahans.kecamatans')
        //         ->whereHas('kelurahans', function ($query) use ($search) {
        //             $query->where('kabkota', 'like', '%' . 'kota tasikmalaya' . '%');
        //         })
        //         ->where('nama', 'LIKE', "%$search%")
        //         ->where('deleted', '0')
        //         ->where('partai_id', Auth::user()->partai)
        //         ->where('kelurahan_id', Auth::user()->kelurahan_id)
        //         ->where('status', 1)
        //         ->get();
        // }
        return response()->json($korlur);
    }

    public function getTps(Request $request)
    {
        $tps = [];
        $search = $request->name;

        // if (Auth::user()->role == '1') {
            if ($search = $request->name) {
                $tps = Tps::with('kelurahans.kecamatans')
                    ->whereHas('kelurahans', function ($query) use ($search) {
                        $query->where('nama_kelurahan', 'LIKE', "%$search%");
                    })
                    // ->where('kelurahan_id', Auth::user()->kelurahan_id)
                    ->get(['id', 'kelurahan_id', 'tps', 'totdpt', 'dptl', 'dptp', 'lokasi']);
            }
        // } else {
        //     $tps = Tps::with('kelurahans.kecamatans')
        //         ->whereHas('kelurahans', function ($query) use ($search) {
        //             $query->where('nama_kelurahan', 'LIKE', "%$search%")
        //                 ->where('kabkota', 'like', '%' . 'kota tasikmalaya' . '%');
        //         })
        //         ->where('kelurahan_id', Auth::user()->kelurahan_id)
        //         ->get(['id', 'kelurahan_id', 'tps', 'totdpt', 'dptl', 'dptp', 'lokasi']);
        // }

        return response()->json($tps);
    }

    public function getTpsById($id){
        // Cari kabkota berdasarkan id
        $tps = DB::table('tps')
        ->leftJoin('kelurahans', 'kelurahans.id', '=', 'tps.kelurahan_id')
        ->leftJoin('kecamatans', 'kecamatans.id', '=', 'kelurahans.kecamatan_id')
        ->where('tps.id', $id)
        ->select(DB::raw('tps.*, kelurahans.nama_kelurahan as kelurahan_nama, kecamatans.nama as kecamatan_nama'))
        ->first();

        // Jika kabkota tidak ditemukan, kembalikan respon kosong atau not found
        if (!$tps) {
            return response()->json(['error' => 'Tps not found'], 404);
        }

        // Kembalikan data kabkota dalam format JSON
        return response()->json($tps);
    }

    public function getTpsSatuan(Request $request)
    {
        $search = $request->idTps;
        // if ($search = $request->name) {
        $tps = Tps::with('kelurahans.kecamatans')
            ->where('id', '=', $search)
            // ->where('kelurahan_id', Auth::user()->kelurahan_id)
            ->get(['id', 'kelurahan_id', 'tps', 'totdpt', 'dptl', 'dptp', 'lokasi']);
        // }
        return response()->json($tps);
    }

    public function getAllKelurahans(){
        $kelurahan = DB::table('kelurahans')->get();
        return response()->json($kelurahan);
    }
    
    public function getKelurahans(Request $request)
    {
        $kelurahan = [];
        if ($search = $request->name) {
            $kelurahan = Kelurahan::with('kecamatans')->where('nama_kelurahan', 'LIKE', "%$search%")
                // ->where('kabkota', 'like', '%' . 'kota tasikmalaya' . '%')
                // ->where('id', Auth::user()->kelurahan_id)
                ->get();
        }

        return response()->json($kelurahan);
    }

    public function getWalkot(Request $request)
    {
        $walkot = Calon::where('id', '=', $request->idWalkot)->get();
        return response()->json($walkot);
    }

    public function getSuaraCalon(Request $request)
    {
        $suaraCalon = PerolehanSuara::where('caleg_id', '=', $request->calon)->where('tps_id', '=', $request->tpsId)->get();
        return response()->json($suaraCalon);
    }

    public function getDpt(Request $request){
        $dpt = Dpt::where('nik', $request->nik)->first();
        return response()->json($dpt);
    }
}
