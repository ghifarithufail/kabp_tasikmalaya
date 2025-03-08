<?php

namespace App\Http\Controllers;

use App\DataTables\PerolehanKotaTasikmalayaDatatables;
use App\Models\Calon;
use App\Models\LogInputSuara;
use App\Models\PerolehanSuara;
use App\Models\Tps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PerolehanSuaraController extends Controller
{
    
      public function list_suara_calon(Request $request)
    {
        $nik = $request->input('nik');
        $kecamatan = $request->input('kecamatan');
        $kelurahan = $request->input('kelurahan');
        $tps = $request->input('tps');
        $calon_walkot = $request->input('calon_walkot');
        $kategori = $request->input('kategori');
        $data_calon = Calon::where('kategori', 'walkot')->get();

        $suara = PerolehanSuara::orderBy('id', 'desc');
        
         // Total TPS
        $totalTPS = DB::table('tps')
                    ->join('kelurahans', 'kelurahans.id', '=', 'tps.kelurahan_id')
                    ->join('kecamatans', 'kecamatans.id', '=', 'kelurahans.kecamatan_id')
                    ->where('kecamatans.kotakab_id', '29')
                    ->count();

        // TPS yang sudah diinput dengan kategori calon = 'walkot'
        $inputTPS = DB::table('perolehan_suaras')
            ->join('calons', 'perolehan_suaras.caleg_id', '=', 'calons.id')
            ->where('calons.kategori', 'walkot')
            ->distinct('tps_id') // Pastikan hanya menghitung TPS unik
            ->count('tps_id');

        // Hitung persentase
        $persentase = $totalTPS > 0 ? ($inputTPS / $totalTPS) * 100 : 0;

        if ($request['calon_walkot']) {
            $suara = $suara->whereHas('calons', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request['calon_walkot'] . '%');
            });
        }

        if ($request['kategori']) {
            $suara = $suara->whereHas('calons', function ($query) use ($request) {
                $query->where('kategori', 'like', '%' . $request['kategori'] . '%');
            });
        }

        if ($request['tps']) {
            $suara = $suara->whereHas('tps', function ($query) use ($request) {
                $query->where('tps', $request['tps']);
            });
        }

        if ($request['kelurahan']) {
            $suara = $suara->whereHas('tps.kelurahans', function ($query) use ($request) {
                $query->where('nama_kelurahan', 'like', '%' . $request['kelurahan'] . '%');
            });
        }

        if ($request['kecamatan']) {
            $suara = $suara->whereHas('tps.kelurahans.kecamatans', function ($query) use ($request) {
                $query->where('nama', 'like', '%' . $request['kecamatan'] . '%');
            });
        }



        $data = $suara->paginate(20)->appends($request->all());

        return view('layouts.input_suara.list_suara_calon', [
            'data' => $data,
            'data_calon' => $data_calon,
            'totalTPS' => $totalTPS,
            'persentase' => round($persentase, 2) . '%',
            'request' => [
                'nik' => $nik,
                'kecamatan' => $kecamatan,
                'kelurahan' => $kelurahan,
                'kategori' => $kategori,
                'tps' => $tps,
                'calon_walkot' => $calon_walkot,
            ],
        ]);
    }
    public function index()
    {
        return view('layouts.input_suara.index', [
            'calons' => Calon::where('kategori', '=', 'walkot')->where('daerah_pemilihan', '=', 'kota tasikmalaya')->get(),
        ]);
    }

    /**
     * INI KODE LAMA
     */
    // public function store(Request $request)
    // {
    //     $validate = $request->validate([
    //         'id_caleg.*' => 'required|integer|min:0',
    //         'tps_id' => 'required',
    //         'total_suara.*' => 'required|integer|min:0',
    //     ]);

    //     $checkData = PerolehanSuara::where('caleg_id', $request->caleg_id)->where('tps_id', $request->tps_id)->get();

    //     $total_suara_sebelumnya = $checkData->isEmpty() ? 0 : $checkData[0]->total_suara;

    //     $tps = Tps::with('kelurahans.kecamatans')->where('id', '=', $request->tps_id)->get(['id', 'kelurahan_id', 'tps', 'totdpt', 'dptl', 'dptp', 'lokasi']);

    //     $caleg = Calon::find($request->caleg_id)->name;
    //     $tps = Tps::find($request->tps_id);
    //     $nama_kelurahan = $tps->kelurahans->nama_kelurahan;
    //     $nama_tps = $tps->tps;

    //     // dd($checkData);
    //     if ($checkData->isEmpty()) {
    //         $inputSuara = new PerolehanSuara($validate);
    //         $inputSuara->save();

    //         $log = new LogInputSuara();

    //         $log->user_id = Auth::user()->id;
    //         $log->action = '1';
    //         $log->log = 'create input suara ' . $caleg . ' dengan total suara ' . $request->total_suara . ' Di TPS ' . $nama_tps . '  '. $nama_kelurahan;
    //         $log->save();

    //         return redirect()->back();
    //         // ->with('success', "$request->total_suara Suara untuk $caleg->name di {$tps[0]->tps} - {$tps[0]->kelurahans->nama_kelurahan} - {$tps[0]->kelurahans->kecamatans->nama}");
    //     } else {
    //         $inputSuara = PerolehanSuara::find($checkData[0]->id);
    //         $inputSuara->update($validate);

    //         $log = new LogInputSuara();

    //         $log->user_id = Auth::user()->id;
    //         $log->action = '2';
    //         $log->log = 'update input suara ' . $caleg . ' dari '. $total_suara_sebelumnya. ' menjadi ' . $request->total_suara . ' Di TPS ' . $nama_tps . ' '. $nama_kelurahan;
    //         $log->save();

    //         return redirect()->back();
    //         // ->with('success', "Update $request->total_suara Suara untuk $caleg->name di {$tps[0]->tps} -{$tps[0]->kelurahans->nama_kelurahan} - {$tps[0]->kelurahans->kecamatans->nama}");
    //     }
    // }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'id_caleg.*' => 'required|integer|min:0',
            'tps_id' => 'required|integer|exists:tps,id',
            'total_suara.*' => 'required|integer|min:0',
            'bukti_pleno' => 'nullable'
        ]);

        if ($request->hasFile('bukti_pleno')) {
            $file = $request->file('bukti_pleno');
            $filePath = $file->store('public/bukti_pleno'); // Menyimpan file di folder 'public/bukti_pleno'
            $validate['bukti_pleno'] = $filePath;
        }

        $tps = Tps::with('kelurahans.kecamatans')->where('id', $request->tps_id)->firstOrFail();
        $nama_kelurahan = $tps->kelurahans->nama_kelurahan;
        $nama_tps = $tps->tps;

        foreach ($request->id_caleg as $index => $caleg_id) {
            $total_suara = $request->total_suara[$index];

            // Check if record already exists
            $checkData = PerolehanSuara::where('caleg_id', $caleg_id)
                ->where('tps_id', $request->tps_id)
                ->first();

            $caleg = Calon::find($caleg_id)->name;

            if (is_null($checkData)) {
                // Insert new data if not found
                $inputSuara = new PerolehanSuara([
                    'caleg_id' => $caleg_id,
                    'tps_id' => $request->tps_id,
                    'total_suara' => $total_suara,
                    'bukti_pleno' => $validate['bukti_pleno']
                ]);
                $inputSuara->save();

                $log = new LogInputSuara();
                $log->user_id = Auth::user()->id;
                $log->action = '1'; // 1 for create
                $log->log = 'create input suara ' . $caleg . ' dengan total suara ' . $total_suara . ' di TPS ' . $nama_tps . ' ' . $nama_kelurahan;
                $log->save();
            } else {
                // Update existing data
                $total_suara_sebelumnya = $checkData->total_suara;
                $checkData->update([
                    'total_suara' => $total_suara,
                    'bukti_pleno' => $validate['bukti_pleno']
                ]);

                $log = new LogInputSuara();
                $log->user_id = Auth::user()->id;
                $log->action = '2'; // 2 for update
                $log->log = 'update input suara ' . $caleg . ' dari ' . $total_suara_sebelumnya . ' menjadi ' . $total_suara . ' di TPS ' . $nama_tps . ' ' . $nama_kelurahan;
                $log->save();
            }
        }

        return redirect()->back()->with('success', 'Data perolehan suara berhasil disimpan.');
    }

    public function log(Request $request)
    {
        $date_start = $request->input('date_start', now()->format('Y-m-d'));
        $date_end = $request->input('date_end', now()->format('Y-m-d'));

        $logInput = LogInputSuara::orderBy('created_at', 'desc');

        if ($request['date_start']) {
            $logInput->whereDate('created_at', '>=', $request['date_start']);
        }

        if ($request['date_end']) {
            $logInput->whereDate('created_at', '<=', $request['date_end']);
        }

        $log = $logInput->paginate(15);
        $log->appends($request->all());

        return view('layouts.input_suara.log', [
            'log' => $log,

            'request' => [
                'date_start' => $date_start,
                'date_end' => $date_end,
            ],
        ]);
    }

    public function indexBupatiTasikmalaya()
    {
        return view('layouts.input_suara_bupati_tasikmalaya.index', [
            'calons' => Calon::where('kategori', '=', 'bupati')->where('daerah_pemilihan', '=', 'kabupaten tasikmalaya')->get()
        ]);
    }

    public function storeBupatiTasikmalaya(Request $request)
    {
        $validate = $request->validate([
            'id_caleg.*' => 'required|integer|min:0',
            'tps_id' => 'required|integer|exists:tps,id',
            'total_suara.*' => 'required|integer|min:0',
            'bukti_pleno' => 'nullable|mimes:jpg,bmp,png,jpeg'
        ]);

        if ($request->hasFile('bukti_pleno')) {
            $file = $request->file('bukti_pleno');
            $filePath = $file->store('public/bukti_pleno'); // Menyimpan file di folder 'public/bukti_pleno'
            $validate['bukti_pleno'] = $filePath;
        }

        $tps = Tps::with('kelurahans.kecamatans')->where('id', $request->tps_id)->firstOrFail();
        $nama_kelurahan = $tps->kelurahans->nama_kelurahan;
        $nama_tps = $tps->tps;

        foreach ($request->id_caleg as $index => $caleg_id) {
            $total_suara = $request->total_suara[$index];

            // Check if record already exists
            $checkData = PerolehanSuara::where('caleg_id', $caleg_id)
                ->where('tps_id', $request->tps_id)
                ->first();

            $caleg = Calon::find($caleg_id)->name;

            if (is_null($checkData)) {
                // Insert new data if not found
                $inputSuara = new PerolehanSuara([
                    'caleg_id' => $caleg_id,
                    'tps_id' => $request->tps_id,
                    'total_suara' => $total_suara,
                    'bukti_pleno' => $validate['bukti_pleno']
                ]);
                $inputSuara->save();

                $log = new LogInputSuara();
                $log->user_id = Auth::user()->id;
                $log->action = '1'; // 1 for create
                $log->log = 'create input suara ' . $caleg . ' dengan total suara ' . $total_suara . ' di TPS ' . $nama_tps . ' ' . $nama_kelurahan;
                $log->save();
            } else {
                // Update existing data
                $total_suara_sebelumnya = $checkData->total_suara;
                $checkData->update([
                    'total_suara' => $total_suara,
                    'bukti_pleno' => $validate['bukti_pleno']
                ]);

                $log = new LogInputSuara();
                $log->user_id = Auth::user()->id;
                $log->action = '2'; // 2 for update
                $log->log = 'update input suara ' . $caleg . ' dari ' . $total_suara_sebelumnya . ' menjadi ' . $total_suara . ' di TPS ' . $nama_tps . ' ' . $nama_kelurahan;
                $log->save();
            }
        }

        return redirect()->back()->with('success', 'Data perolehan suara berhasil disimpan.');
    }

    public function indexBupatiGarut()
    {
        return view('layouts.input_suara_bupati_garut.index', [
            'calons' => Calon::where('kategori', '=', 'bupati')->where('daerah_pemilihan', '=', 'garut')->get()
        ]);
    }

    public function storeBupatiGarut(Request $request)
    {
        $validate = $request->validate([
            'id_caleg.*' => 'required|integer|min:0',
            'tps_id' => 'required|integer|exists:tps,id',
            'total_suara.*' => 'required|integer|min:0',
            'bukti_pleno' => 'nullable|mimes:jpg,bmp,png,jpeg'
        ]);

        if ($request->hasFile('bukti_pleno')) {
            $file = $request->file('bukti_pleno');
            $filePath = $file->store('public/bukti_pleno'); // Menyimpan file di folder 'public/bukti_pleno'
            $validate['bukti_pleno'] = $filePath;
        }

        $tps = Tps::with('kelurahans.kecamatans')->where('id', $request->tps_id)->firstOrFail();
        $nama_kelurahan = $tps->kelurahans->nama_kelurahan;
        $nama_tps = $tps->tps;

        foreach ($request->id_caleg as $index => $caleg_id) {
            $total_suara = $request->total_suara[$index];

            // Check if record already exists
            $checkData = PerolehanSuara::where('caleg_id', $caleg_id)
                ->where('tps_id', $request->tps_id)
                ->first();

            $caleg = Calon::find($caleg_id)->name;

            if (is_null($checkData)) {
                // Insert new data if not found
                $inputSuara = new PerolehanSuara([
                    'caleg_id' => $caleg_id,
                    'tps_id' => $request->tps_id,
                    'total_suara' => $total_suara,
                    'bukti_pleno' => $validate['bukti_pleno']
                ]);
                $inputSuara->save();

                $log = new LogInputSuara();
                $log->user_id = Auth::user()->id;
                $log->action = '1'; // 1 for create
                $log->log = 'create input suara ' . $caleg . ' dengan total suara ' . $total_suara . ' di TPS ' . $nama_tps . ' ' . $nama_kelurahan;
                $log->save();
            } else {
                // Update existing data
                $total_suara_sebelumnya = $checkData->total_suara;
                $checkData->update([
                    'total_suara' => $total_suara,
                    'bukti_pleno' => $validate['bukti_pleno']
                ]);

                $log = new LogInputSuara();
                $log->user_id = Auth::user()->id;
                $log->action = '2'; // 2 for update
                $log->log = 'update input suara ' . $caleg . ' dari ' . $total_suara_sebelumnya . ' menjadi ' . $total_suara . ' di TPS ' . $nama_tps . ' ' . $nama_kelurahan;
                $log->save();
            }
        }

        return redirect()->back()->with('success', 'Data perolehan suara berhasil disimpan.');
    }

    public function indexGubernurJawaBarat()
    {
        return view('layouts.input_suara_gubernur.index', [
            'calons' => Calon::where('kategori', '=', 'gubernur')->where('daerah_pemilihan', '=', 'jawa barat')->get()
        ]);
    }

    public function storeGubernurJawaBarat(Request $request)
    {
        $validate = $request->validate([
            'id_caleg.*' => 'required|integer|min:0',
            'tps_id' => 'required|integer|exists:tps,id',
            'total_suara.*' => 'required|integer|min:0',
            'bukti_pleno' => 'nullable|mimes:jpg,bmp,png,jpeg'
        ]);

        if ($request->hasFile('bukti_pleno')) {
            $file = $request->file('bukti_pleno');
            $filePath = $file->store('public/bukti_pleno'); // Menyimpan file di folder 'public/bukti_pleno'
            $validate['bukti_pleno'] = $filePath;
        }

        $tps = Tps::with('kelurahans.kecamatans')->where('id', $request->tps_id)->firstOrFail();
        $nama_kelurahan = $tps->kelurahans->nama_kelurahan;
        $nama_tps = $tps->tps;

        foreach ($request->id_caleg as $index => $caleg_id) {
            $total_suara = $request->total_suara[$index];

            // Check if record already exists
            $checkData = PerolehanSuara::where('caleg_id', $caleg_id)
                ->where('tps_id', $request->tps_id)
                ->first();

            $caleg = Calon::find($caleg_id)->name;

            if (is_null($checkData)) {
                // Insert new data if not found
                $inputSuara = new PerolehanSuara([
                    'caleg_id' => $caleg_id,
                    'tps_id' => $request->tps_id,
                    'total_suara' => $total_suara,
                    'bukti_pleno' => $validate['bukti_pleno']
                ]);
                $inputSuara->save();

                $log = new LogInputSuara();
                $log->user_id = Auth::user()->id;
                $log->action = '1'; // 1 for create
                $log->log = 'create input suara ' . $caleg . ' dengan total suara ' . $total_suara . ' di TPS ' . $nama_tps . ' ' . $nama_kelurahan;
                $log->save();
            } else {
                // Update existing data
                $total_suara_sebelumnya = $checkData->total_suara;
                $checkData->update([
                    'total_suara' => $total_suara,
                    'bukti_pleno' => $validate['bukti_pleno']
                ]);

                $log = new LogInputSuara();
                $log->user_id = Auth::user()->id;
                $log->action = '2'; // 2 for update
                $log->log = 'update input suara ' . $caleg . ' dari ' . $total_suara_sebelumnya . ' menjadi ' . $total_suara . ' di TPS ' . $nama_tps . ' ' . $nama_kelurahan;
                $log->save();
            }
        }

        return redirect()->back()->with('success', 'Data perolehan suara berhasil disimpan.');
    }

    public function reportWalkotTasik(PerolehanKotaTasikmalayaDatatables $dataTable){
        $kota = 'kota tasikmalaya';
        $calon_walkot = Calon::select(
            'calons.name',
            'calons.gambar',
            DB::raw('SUM(perolehan_suaras.total_suara) as total_suara'),
            DB::raw('ROUND((SUM(perolehan_suaras.total_suara) / 
                                                (SELECT SUM(ps2.total_suara) 
                                                FROM perolehan_suaras ps2
                                                LEFT JOIN calons c2 ON ps2.caleg_id = c2.id
                                                  WHERE c2.daerah_pemilihan = "' . $kota . '") * 100), 2) as persentase')
        )
            ->leftJoin('perolehan_suaras', 'calons.id', '=', 'perolehan_suaras.caleg_id')
            ->where('calons.daerah_pemilihan', $kota)
            ->groupBy('calons.name')
            ->orderBy('total_suara', 'DESC')
            ->get();

        return $dataTable->render('layouts.input_suara.report-walkot-tasik',[
            'calon_walkot' => $calon_walkot
        ]);
    }

    public function reportBupatiTasik(){
        
        $kabupaten = 'kabupaten tasikmalaya';
        $calon_kabupaten = Calon::select(
            'calons.name',
            'calons.gambar',
            DB::raw('SUM(perolehan_suaras.total_suara) as total_suara'),
            DB::raw('ROUND((SUM(perolehan_suaras.total_suara) / 
                                                (SELECT SUM(ps2.total_suara) 
                                                FROM perolehan_suaras ps2
                                                LEFT JOIN calons c2 ON ps2.caleg_id = c2.id
                                                  WHERE c2.daerah_pemilihan = "' . $kabupaten . '") * 100), 2) as persentase')
        )
            ->leftJoin('perolehan_suaras', 'calons.id', '=', 'perolehan_suaras.caleg_id')
            ->where('calons.daerah_pemilihan', $kabupaten)
            ->groupBy('calons.name')
            ->orderBy('total_suara', 'DESC')
            ->get();

        return view('layouts.input_suara.report-bupati-tasik', [
            'calon_kabupaten' => $calon_kabupaten
        ]);
    }

    public function reportBupatiGarut(){
        $garut = 'garut';
        $calon_garut = Calon::select(
            'calons.name',
            'calons.gambar',
            DB::raw('SUM(perolehan_suaras.total_suara) as total_suara'),
            DB::raw('ROUND((SUM(perolehan_suaras.total_suara) / 
                                                (SELECT SUM(ps2.total_suara) 
                                                FROM perolehan_suaras ps2
                                                LEFT JOIN calons c2 ON ps2.caleg_id = c2.id
                                                  WHERE c2.daerah_pemilihan = "' . $garut . '") * 100), 2) as persentase')
        )
            ->leftJoin('perolehan_suaras', 'calons.id', '=', 'perolehan_suaras.caleg_id')
            ->where('calons.daerah_pemilihan', $garut)
            ->groupBy('calons.name')
            ->orderBy('total_suara', 'DESC')
            ->get();

        return view('layouts.input_suara.report-bupati-garut', [
            'calon_garut' => $calon_garut
        ]);
    }

    public function reportGubernurJawabarat(Request $request)
    {
        $jawabarat = 'jawa barat';
        $calon_gubernur = Calon::select(
            'calons.name',
            'calons.gambar',
            DB::raw('SUM(perolehan_suaras.total_suara) as total_suara'),
            DB::raw('ROUND((SUM(perolehan_suaras.total_suara) / 
                                            (SELECT SUM(ps2.total_suara) 
                                            FROM perolehan_suaras ps2
                                            LEFT JOIN calons c2 ON ps2.caleg_id = c2.id
                                              WHERE c2.daerah_pemilihan = "' . $jawabarat . '") * 100), 2) as persentase')
        )
            ->leftJoin('perolehan_suaras', 'calons.id', '=', 'perolehan_suaras.caleg_id')
            ->where('calons.daerah_pemilihan', $jawabarat)
            ->groupBy('calons.name')
            ->orderBy('total_suara', 'DESC')
            ->get();

        if ($request->ajax()) {
            return response()->json(['data' => $calon_gubernur]);
        }

        return view('layouts.input_suara.report-gubernur-jawabarat', [
            'calon_gubernur' => $calon_gubernur
        ]);
    }
}
