<?php

namespace App\Http\Controllers;

use App\Exports\AnggotaGeneralExport;
use App\Models\AgentTps;
use App\Models\Anggota;
use App\Models\DataVerif;
use App\Models\Dpt;
use App\Models\Kabkota;
use App\Models\Kelurahan;
use App\Models\LogAnggota;
use App\Models\Partai;
use App\Models\Tps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class AnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $nik = $request->input('nik');
        $agent = $request->input('agent');
        $kelurahan = $request->input('kelurahan');
        $verifikasi = $request->input('verifikasi'); 
        
        if (Auth::user()->role == '1' || Auth::user()->role == '2' || Auth::user()->role == '5') {
            $anggotas = Anggota::orderBy('created_at', 'desc')->where('deleted', '0');
        } else {
            $anggotas = Anggota::orderBy('created_at', 'desc')
                ->where('deleted', '0')
                ->whereHas('agents', function ($agent) {
                    $agent->where('partai_id', Auth::user()->partai);
                });
        }

        if ($request['nik']) {
            $anggotas = $anggotas->where('nama', 'like', '%' . $request['nik'] . '%')
                ->orWhere('nik', $request['nik']);
        }

        if ($request['agent']) {
            $anggotas = $anggotas->whereHas('agents', function ($agents) use ($request) {
                $agents->where('nama', 'like', '%' . $request['agent'] . '%');
            });
        }

        if ($request['kelurahan']) {
            $anggotas = $anggotas->whereHas('tps.kelurahans', function ($kelurahans) use ($request) {
                $kelurahans->where('nama_kelurahan', 'like', '%' . $request['kelurahan'] . '%');
            });
        }
        
        if ($request['verifikasi']) {
            $anggotas = $anggotas->where('verified', 'like', '%' . $request['verifikasi'] . '%');
        }

        $anggota = $anggotas->paginate(20)->appends($request->all());

        return view('layouts.anggota.index', [
            'anggota' => $anggota,
            'menu' => 'koordinator',
            'subMenu' => 'anggota',
            'request' => [
                'nik' => $nik,
                'agent' => $agent,
                'kelurahan' => $kelurahan,
                'verifikasi' => $verifikasi,
            ],
        ]);
    }

    public function report(Request $request){
        $nik = $request->input('nik');
        $agent = $request->input('agent');
        $kelurahan = $request->input('kelurahan');
        $verifikasi = $request->input('verifikasi');
        $tim = $request->input('tim');
        $user_role = Auth::user();
        $partai = Partai::orderBy('nama', 'asc')->get();

        if (Auth::user()->role == '1' || Auth::user()->role == '2' || Auth::user()->role == '5') {
            $anggotas = Anggota::orderBy('created_at', 'desc')->where('deleted', '0');
        } else {
            $anggotas = Anggota::orderBy('created_at', 'desc')
                ->where('deleted', '0')
                ->whereHas('agents', function ($agent) {
                    $agent->where('partai_id', Auth::user()->partai);
                });
        }

        if ($request['nik']) {
            $anggotas = $anggotas->where('nama', 'like', '%' . $request['nik'] . '%')
                ->orWhere('nik', $request['nik']);
        }

        if ($request['verifikasi']) {
            $anggotas = $anggotas->where('verified', 'like', '%' . $request['verifikasi'] . '%');
        }
        
        if ($request['tim']) {
            $anggotas = $anggotas->whereHas('agents', function ($agents) use ($request) {
                $agents->where('partai_id', $request['tim']);
            });
        }

        if ($request['agent']) {
            $anggotas = $anggotas->whereHas('agents', function ($agents) use ($request) {
                $agents->where('nama', 'like', '%' . $request['agent'] . '%');
            });
        }

        if ($request['kelurahan']) {
            $anggotas = $anggotas->whereHas('tps.kelurahans', function ($kelurahans) use ($request) {
                $kelurahans->where('nama_kelurahan', 'like', '%' . $request['kelurahan'] . '%');
            });
        }

        $anggota = $anggotas->paginate(20)->appends($request->all());

        return view('layouts.anggota.report', [
            'anggota' => $anggota,
            'partai' => $partai,
            'user_role' => $user_role,
            'menu' => 'koordinator',
            'subMenu' => 'anggota',
            'request' => [
                'nik' => $nik,
                'agent' => $agent,
                'tim' => $tim,
                'kelurahan' => $kelurahan,
                'verifikasi' => $verifikasi,
            ],
        ]);
    }

    public function excel(){
        return Excel::download(new AnggotaGeneralExport(), 'AnggotatGeneral.xlsx');

    }


    public function verif_lolos($id){
        $anggota = Anggota::find($id);
        $anggota->verified = '1';
        $anggota->verified_id = Auth::user()->id;
        $anggota->save();

        return redirect()->back();
    }

    public function verif_gagal($id){
        $anggota = Anggota::find($id);
        $anggota->verified = '2';
        $anggota->verified_id = Auth::user()->id;
        $anggota->save();

        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $partai = Partai::orderBy('nama', 'asc')->where('deleted', '0')->get();

        return view('layouts.anggota.create', [
            'partai' => $partai,
            'menu' => 'koordinator',
            'subMenu' => 'anggota'
        ]);
    }

    /**
     * Store a newly created resource in storage. s
     */

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nik' => 'required|unique:anggotas,nik|numeric|digits:16',
            'nama' => 'required',
            'kabkota_id' => 'required',
            'alamat' => 'required',
            'rt' => 'required',
            'rw' => 'required',
            'jenis_kelamin' => 'required',
            'usia' => 'required',
            'agent_id' => 'required',
            'phone' => 'required',
            'status' => 'required',
            'keterangan' => 'nullable',
            'tgl_lahir' => 'required',
            'tps_id' => 'required',
            'deleted' => 'nullable',
        ], [
            'nama.required' => 'Nama harus diisi',
            'phone.required' => 'No Telpon harus diisi',
            'nik.required' => 'NIK harus diisi',
            'nik.unique' => 'NIK sudah terdaftar.',
            'nik.digits' => 'Format NIK tidak sesuai.',
            'kabkota_id.required' => 'KABKOTA harus diisi',
            'tgl_lahir.required' => 'Tanggal Lahir harus diisi',
            'rt.required' => 'RT harus diisi',
            'rw.required' => 'RW harus diisi',
            'status.required' => 'Status harus diisi',
            'jenis_kelamin.required' => 'Status harus diisi',
            'usia.required' => 'Status harus diisi',
            'tps_id.required' => 'tps harus diisi',
            'alamat.required' => 'Alamat harus diisi',
            'agent_id.required' => 'Koordinator Tps harus diisi',
        ]);

        DB::beginTransaction();

        try {
            $anggota = new Anggota($validatedData);
            $anggota->user_id = Auth::user()->id;

            $verifikasi = Dpt::where('nik', $anggota->nik)->first();

            if ($verifikasi) {
                $anggota->verified = '1';
            } else {
                $anggota->verified = '2';
            }

            $anggota->save();

            \Log::info($anggota);

            $log = new LogAnggota();
            $log->user_id = Auth::user()->id;
            $log->action = '1';
            $log->log = 'Create Anggota ' . $request->nama . ' with nik ' . $request->nik;
            $log->save();

            \Log::info($log);
            DB::commit();

            return redirect()->route('koordinator/anggota')->with('success', 'Anggota berhasil disimpan');
        } catch (\Exception $e) {
            
            DB::rollBack();

            \Log::error('Error creating anggota: ' . $e->getMessage());

            return redirect()->back();
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Anggota $anggota)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $anggota = Anggota::find($id);

        return view('layouts.anggota.edit', [
            'anggota' => $anggota,
            'menu' => 'koordinator',
            'subMenu' => 'anggota'
        ]);
    }
    
    public function edit_report($id)
    {
        $anggota = Anggota::find($id);

        return view('layouts.anggota.edit_report', [
            'anggota' => $anggota,
            'menu' => 'koordinator',
            'subMenu' => 'anggota'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $oldAnggota = Anggota::find($id);

        $validatedData = $request->validate([
            'nik' => 'required|unique:anggotas,nik,' . $id,
            'nama' => 'required',
            'kabkota_id' => 'required',
            'alamat' => 'required',
            'rt' => 'required',
            'rw' => 'required',
            // 'agent_id' => 'required',
            'phone' => 'required',
            'status' => 'required',
            'jenis_kelamin' => 'required',
            'usia' => 'required',
            'keterangan' => 'nullable',
            'tgl_lahir' => 'nullable',
            'tps_id' => 'required',
            'deleted' => 'nullable',
        ]);

        $anggota = Anggota::find($id);

        $anggota->update($validatedData);

        $verifikasi = Dpt::where('tps', $anggota->tps->tps)
        ->where('kelurahan', $anggota->tps->kelurahans->nama_kelurahan)
        ->where('nama', 'like', '%' . $anggota->nama . '%')
        ->where('rt', $anggota->rt)
        ->where('rw', $anggota->rw)
        ->where('usia', $anggota->usia)
        ->where('jenis', $anggota->jenis_kelamin)
        ->where('dapil', "Dapil ".$anggota->tps->kelurahans->dapil)
        ->first();

        if ($verifikasi) {
            $anggota->verified = '1';
        } else {
            $anggota->verified = '2';
        }

        $anggota->save();

        \Log::info($verifikasi);


        $log = new LogAnggota();

        $log->user_id =  Auth::user()->id;
        $log->action = '2';

        $changes = [];
        if ($oldAnggota->nama != $anggota->nama) {
            $changes[] = 'nama from ' . $oldAnggota->nama . ' to ' . $anggota->nama;
        }
        if ($oldAnggota->nik != $anggota->nik) {
            $changes[] = 'nik from ' . $oldAnggota->nik . ' to ' . $anggota->nik;
        }
        if ($oldAnggota->kabkota_id != $anggota->kabkota_id) {
            $oldKabkota = Kabkota::find($oldAnggota->kabkota_id)->nama;
            $Kabkota = Kabkota::find($anggota->kabkota_id)->nama;

            $changes[] = 'kota from ' . $oldKabkota . ' to ' . $Kabkota;
        }
        if ($oldAnggota->tgl_lahir != $anggota->tgl_lahir) {
            $changes[] = 'tanggal from ' . $oldAnggota->tgl_lahir . ' to ' . $anggota->tgl_lahir;
        }
        if ($oldAnggota->alamat != $anggota->alamat) {
            $changes[] = 'alamat from ' . $oldAnggota->alamat . ' to ' . $anggota->alamat;
        }
        if ($oldAnggota->rt != $anggota->rt) {
            $changes[] = 'rt from ' . $oldAnggota->rt . ' to ' . $anggota->rt;
        }
        if ($oldAnggota->rw != $anggota->rw) {
            $changes[] = 'rw from ' . $oldAnggota->rw . ' to ' . $anggota->rw;
        }
        if ($oldAnggota->tps_id != $anggota->tps_id) {
            $oldtps = Tps::find($oldAnggota->tps_id);
            $tps = Tps::find($anggota->tps_id);

            $oldKelurahan = Kelurahan::find($oldtps->kelurahan_id)->nama_kelurahan;
            $Kelurahan = Kelurahan::find($oldtps->kelurahan_id)->nama_kelurahan;

            $oldtpsNama = $oldtps->tps;
            $tpsNama = $tps->tps;

            $changes[] = 'Tps from ' . $oldtpsNama . ' ' . $oldKelurahan . ' to ' . $tpsNama . ' ' . $Kelurahan;
        }
        if ($oldAnggota->phone != $anggota->phone) {
            $changes[] = 'phone from ' . $oldAnggota->phone . ' to ' . $anggota->phone;
        }
        $oldStatus = $oldAnggota->status == 1 ? 'aktif' : 'non aktif';
        $status = $anggota->status == 1 ? 'aktif' : 'non aktif';
        if ($oldAnggota->status != $anggota->status) {
            $changes[] = 'status from ' . $oldStatus . ' to ' . $status;
        }
        if ($oldAnggota->keterangan != $anggota->keterangan) {
            $changes[] = 'keterangan from ' . $oldAnggota->keterangan . ' to ' . $anggota->keterangan;
        }
        if ($oldAnggota->agent_id != $anggota->agent_id) {
            $oldpartai = AgentTps::find($oldAnggota->agent_id)->nama;
            $partai = AgentTps::find($anggota->agent_id)->nama;

            $changes[] = 'partai from ' . $oldpartai . ' to ' . $partai;
        }

        $log->log = 'update data ' . implode(', ', $changes);
        $log->save();

        return redirect()->route('koordinator/anggota');
    }

    public function report_update(Request $request, $id)
        {
    
            $oldAnggota = Anggota::find($id);
    
            $validatedData = $request->validate([
                'nik' => 'required|unique:anggotas,nik,' . $id,
                'nama' => 'required',
                'kabkota_id' => 'required',
                'alamat' => 'required',
                'rt' => 'required',
                'rw' => 'required',
                'agent_id' => 'required',
                'phone' => 'required',
                'status' => 'required',
                'jenis_kelamin' => 'required',
                'usia' => 'required',
                'keterangan' => 'nullable',
                'tgl_lahir' => 'nullable',
                'tps_id' => 'required',
                'deleted' => 'nullable',
            ]);
    
            $anggota = Anggota::find($id);
    
            $anggota->update($validatedData);
    
            $verifikasi = Dpt::where('tps', $anggota->tps->tps)
            ->where('kelurahan', $anggota->tps->kelurahans->nama_kelurahan)
            ->where('nama', 'like', '%' . $anggota->nama . '%')
            ->where('rt', $anggota->rt)
            ->where('rw', $anggota->rw)
            ->where('usia', $anggota->usia)
            ->where('jenis', $anggota->jenis_kelamin)
            ->where('dapil', "Dapil ".$anggota->tps->kelurahans->dapil)
            ->first();
    
            if ($verifikasi) {
                $anggota->verified = '1';
            } else {
                $anggota->verified = '2';
            }
    
            $anggota->save();
    
            \Log::info($verifikasi);
    
    
            $log = new LogAnggota();
    
            $log->user_id =  Auth::user()->id;
            $log->action = '2';
    
            $changes = [];
            if ($oldAnggota->nama != $anggota->nama) {
                $changes[] = 'nama from ' . $oldAnggota->nama . ' to ' . $anggota->nama;
            }
            if ($oldAnggota->nik != $anggota->nik) {
                $changes[] = 'nik from ' . $oldAnggota->nik . ' to ' . $anggota->nik;
            }
            if ($oldAnggota->kabkota_id != $anggota->kabkota_id) {
                $oldKabkota = Kabkota::find($oldAnggota->kabkota_id)->nama;
                $Kabkota = Kabkota::find($anggota->kabkota_id)->nama;
    
                $changes[] = 'kota from ' . $oldKabkota . ' to ' . $Kabkota;
            }
            if ($oldAnggota->tgl_lahir != $anggota->tgl_lahir) {
                $changes[] = 'tanggal from ' . $oldAnggota->tgl_lahir . ' to ' . $anggota->tgl_lahir;
            }
            if ($oldAnggota->alamat != $anggota->alamat) {
                $changes[] = 'alamat from ' . $oldAnggota->alamat . ' to ' . $anggota->alamat;
            }
            if ($oldAnggota->rt != $anggota->rt) {
                $changes[] = 'rt from ' . $oldAnggota->rt . ' to ' . $anggota->rt;
            }
            if ($oldAnggota->rw != $anggota->rw) {
                $changes[] = 'rw from ' . $oldAnggota->rw . ' to ' . $anggota->rw;
            }
            if ($oldAnggota->tps_id != $anggota->tps_id) {
                $oldtps = Tps::find($oldAnggota->tps_id);
                $tps = Tps::find($anggota->tps_id);
    
                $oldKelurahan = Kelurahan::find($oldtps->kelurahan_id)->nama_kelurahan;
                $Kelurahan = Kelurahan::find($oldtps->kelurahan_id)->nama_kelurahan;
    
                $oldtpsNama = $oldtps->tps;
                $tpsNama = $tps->tps;
    
                $changes[] = 'Tps from ' . $oldtpsNama . ' ' . $oldKelurahan . ' to ' . $tpsNama . ' ' . $Kelurahan;
            }
            if ($oldAnggota->phone != $anggota->phone) {
                $changes[] = 'phone from ' . $oldAnggota->phone . ' to ' . $anggota->phone;
            }
            $oldStatus = $oldAnggota->status == 1 ? 'aktif' : 'non aktif';
            $status = $anggota->status == 1 ? 'aktif' : 'non aktif';
            if ($oldAnggota->status != $anggota->status) {
                $changes[] = 'status from ' . $oldStatus . ' to ' . $status;
            }
            if ($oldAnggota->keterangan != $anggota->keterangan) {
                $changes[] = 'keterangan from ' . $oldAnggota->keterangan . ' to ' . $anggota->keterangan;
            }
            if ($oldAnggota->agent_id != $anggota->agent_id) {
                $oldpartai = AgentTps::find($oldAnggota->agent_id)->nama;
                $partai = AgentTps::find($anggota->agent_id)->nama;
    
                $changes[] = 'partai from ' . $oldpartai . ' to ' . $partai;
            }
    
            $log->log = 'update data ' . implode(', ', $changes);
            $log->save();
    
            return redirect()->route('report/anggota');
        }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $anggota = Anggota::find($id);

        $anggota->deleted = "1";
        $anggota->save();

        $log = new LogAnggota();

        $log->user_id =  Auth::user()->id;
        $log->action = '3';
        $log->log = 'delete anggota ' . $anggota->nama . ' with nik ' . $anggota->nik;
        $log->save();

        return redirect()->back();
    }

    public function log(Request $request)
    {
        $date_start = $request->input('date_start', now()->format('Y-m-d'));
        $date_end = $request->input('date_end', now()->format('Y-m-d'));

        $logAnggota = LogAnggota::with('users')->orderBy('created_at', 'desc');

        if ($request['date_start']) {
            $logAnggota->whereDate('created_at', '>=', $request['date_start']);
        }

        if ($request['date_end']) {
            $logAnggota->whereDate('created_at', '<=', $request['date_end']);
        }

        $log = $logAnggota->paginate(15);
        $log->appends($request->all());

        return view('layouts.anggota.log', [
            'log' => $log,

            'request' => [
                'date_start' => $date_start,
                'date_end' => $date_end,
            ],
        ]);
    }
}
