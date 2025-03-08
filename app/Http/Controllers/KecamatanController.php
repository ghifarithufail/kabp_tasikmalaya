<?php

namespace App\Http\Controllers;

use App\Exports\KecamatanExport;
use App\Exports\TpsExport;
use App\Models\Tps;
use App\Models\Korcam;
use App\Models\Kecamatan;
use App\Models\Partai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Excel;

class KecamatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function report()
    {

        // Ambil semua partai
        $partais = DB::table('partais')->select('id', 'nama')->where('deleted', '=', '0')->OrderBy('id', 'asc')->get();

        $tim = Partai::orderBy('id', 'asc')->get();
       


        return view('layouts.kecamatan.report', [
            'tim' => $tim
        ]);
    }

    public function data_report_kecamatan(Request $request)
    {

        $userPermissions = auth()->user()->getAllPermissions()->pluck('name')->toArray();

        $partais = DB::table('partais')->select('id', 'nama')->OrderBy('id', 'asc')->get();

        $tim = Partai::orderBy('id', 'asc')->get();

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
            
        $allowedRegions = [];
        if (in_array('kota-tasikmalaya report/kecamatan', $userPermissions)) {
            $allowedRegions[] = 'TASIKMALAYA';
        }
        if (in_array('kabupaten-tasikmalaya report/kecamatan', $userPermissions)) {
            $allowedRegions[] = 'KABUPATEN TASIKMALAYA';
        }
        if (in_array('kabupaten-garut report/kecamatan', $userPermissions)) {
            $allowedRegions[] = 'GARUT';
        }

        if (!empty($allowedRegions)) {
            $kecamatans->whereIn('kabkotas.nama', $allowedRegions);
        }
        

        // Tambahkan COUNT untuk setiap partai secara dinamis
        foreach ($partais as $partai) {
            $kecamatans->addSelect(DB::raw(
                "COUNT(CASE WHEN agent_tps.partai_id = {$partai->id} THEN anggotas.id END) as anggota_partai_{$partai->id}"
            ));
        }

        // Filter berdasarkan kecamatan
        if ($request->filled('kecamatan')) {
            $kecamatans->where('kecamatans.nama', 'like', '%' . $request['kecamatan'] . '%');
        }

        // Filter berdasarkan dapil
        if ($request->filled('dapil')) {
            $kecamatans->where('kelurahans.dapil', $request['dapil']);
        }

        // Ambil data kecamatan dari query
        $kecamatan = $kecamatans->groupBy('kecamatans.id', 'kecamatans.nama', 'kelurahans.dapil')->get();

        if ($kecamatan->isEmpty()) {
            $data = '
            <table class="table table-hover text-center" style="zoom: 0.75">
                <thead>
                    <tr>
                        <th>Kecamatan</th>
                        <th>Total DPT</th>
                        <th>Target</th>
                        <th>Konstituante</th>
                        <th>Verifikasi Sukses</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <tr>
                        <td colspan="5">Data Tidak Ditemukan</td>
                    </tr>
                </tbody>
            </table>';
        } else {
            // Render tampilan dengan data kecamatan
            $data = view('layouts.kecamatan.data_report', compact('kecamatan', 'tim'))->render();
        }

        // Kembalikan data dalam format JSON untuk AJAX
        return response()->json(['data' => $data]);
    }

    public function excel_report()
    {

        return Excel::download(new KecamatanExport(), 'kecamatan.xlsx');
    }

    public function detailReport($id)
    {
        $korcam = Korcam::with('partais')->where('kecamatan_id', '=', $id)->get();
        $kecamatan = Kecamatan::find($id);

        return view('layouts.kecamatan.detail-report', [
            'kecamatan' => $kecamatan,
            'korcam' => $korcam
        ]);
    }
}
