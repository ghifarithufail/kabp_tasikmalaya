<?php

namespace App\Http\Controllers;

use App\Exports\KorlurExport;
use App\Exports\KorlurGeneralExport;
use App\Models\Korcam;
use App\Models\Korlur;
use App\Models\Partai;
use App\Models\AgentTps;
use App\Models\Kabkota;
use App\Models\Kelurahan;
use App\Models\LogKorlur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Excel;

class KorlurController extends Controller
{
    public function index(Request $request){

        $nik = $request->input('nik');
        $korcam = $request->input('korcam');
        $kelurahan = $request->input('kelurahan');
        $partai = $request->input('partai');

        $partais = Partai::orderBy('nama', 'asc')->get();



        if(Auth::user()->role == '1' || Auth::user()->role == '2' || Auth::user()->role == '5'){
        $data = Korlur::with('korcams','kecamatans','kelurahans')
                    ->orderBy('created_at', 'desc')
                    ->where('deleted','0');
        }else{
            $data = Korlur::with('korcams','kecamatans','kelurahans')
            ->orderBy('created_at', 'desc')
            ->where('deleted','0')
            ->where('partai_id', Auth::user()->partai);
        }

        if ($request['nik']) {
            $data = $data->where('nama', 'like', '%' . $request['nik'] . '%')
                ->orWhere('nik', $request['nik']);
        }

        if ($request['korcam']) {
            $data = $data->whereHas('korcams', function ($korcams) use ($request) {
                $korcams->where('nama', 'like', '%' . $request['korcam'] . '%');
            });
        }

        if ($request['kelurahan']) {
            $data = $data->whereHas('kelurahans', function ($kelurahans) use ($request) {
                $kelurahans->where('nama_kelurahan', 'like', '%' . $request['kelurahan'] . '%');
            });
        }

        if ($request['partai']) {
            $data = $data->where('partai_id', $request['partai'] );
        }

        $korlur = $data->paginate(20)->appends($request->all());

        return view('layouts.korlur.index',[
            'korlur' => $korlur,
            'partais' => $partais,
            'menu' => 'koordinator',
            'subMenu' => 'korlur',
            'request' => [
                'nik' => $nik,
                'korcam' => $korcam,
                'kelurahan' => $kelurahan,
                'partai' => $partai,
                // 'kecamatan' => $kecamatan,
                // 'partai' => $partai,
            ],
        ]);
    }

    public function create(){

        // if(Auth::user()->role == '1'){
            $partai = Partai::orderBy('nama', 'asc')->where('deleted', '0')->get();
        // }else{
        //     $partai = Partai::orderBy('nama', 'asc')
        //     ->where('deleted', '0')
        //     ->where('id', Auth::user()->partai)
        //     ->get();
        // }
        $korcam = Korcam::orderBy('nama','asc')->where('deleted', '0')->get();

        return view('layouts.korlur.create',[
            'menu' => 'koordinator',
            'partai' => $partai,
            'korcam' => $korcam,
            'subMenu' => 'korlur'
        ]);

    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nik' => 'required|unique:korlurs,nik|numeric|digits:16',
            'nama' => 'required',
            'kabkota_id' => 'nullable',
            'alamat' => 'nullable',
            'rt' => 'required',
            'rw' => 'required',
            'phone' => 'required',
            'status' => 'required',
            'keterangan' => 'nullable',
            'tgl_lahir' => 'nullable',
            'kelurahan_id' => 'required',
            'partai_id' => 'required',
            'tps_id' => 'required',
            'korcam_id' => 'required',
            'deleted' => 'nullable',
            'user_id' => 'nullable',
        ],[
            'nama.required' => 'nama harus diisi',
            'phone.required' => 'No Telpon harus diisi',
            'nik.required' => 'NIK harus diisi',
            'nik.unique' => 'NIK sudah terdaftar.',
            'nik.digits' => 'Jumlah NIK kurang atau Lebih Dari 16 Angka.',
            'kabkota_id.required' => 'KABKOTA harus diisi',
            'tgl_lahir.required' => 'Tanggal Lahir harus diisi',
            'rt.required' => 'RT harus diisi',
            'rw.required' => 'RW harus diisi',
            'kelurahan_id.required' => 'KELURAHAN harus diisi',
            'status.required' => 'Status harus diisi',
            'partai_id.required' => 'Partai harus diisi',
            'tps_id.required' => 'Tps harus diisi',
            'alamat.required' => 'Alamat  harus diisi',
        ]);

        $korlur = new Korlur($validatedData);
        $korlur->user_id = Auth::user()->id;
        $korlur->save();

        $log = new LogKorlur();
        $log->user_id = Auth::user()->id;
        $log->action = '1';
        $log->log = 'Create Korlur ' . $request->nama . ' with nik ' . $request->nik;
        $log->save();


        return redirect('koordinator/korlur');
    }

    public function edit($id)
    {
        $korlur = Korlur::find($id)->load(['kabkotas']);
        $korcam = Korcam::orderBy('nama','asc')->get();
        $partai = Partai::orderBy('nama','asc')->get();


        return view('layouts.korlur.edit',[
            'korlur' => $korlur,
            'korcam' => $korcam,
            'partai' => $partai,
            'menu' => 'koordinator',
            'subMenu' => 'korlur'
        ]);
    }

    public function update(Request $request, $id)
    {
        $oldKorlur = Korlur::findOrFail($id);

        $validatedData = $request->validate([
            'nik' => 'required|unique:korlurs,nik,' . $id,
            'nama' => 'required',
            'kabkota_id' => 'nullable',
            'alamat' => 'nullable',
            'rt' => 'required',
            'rw' => 'required',
            'phone' => 'required',
            'status' => 'required',
            'keterangan' => 'nullable',
            'tgl_lahir' => 'nullable',
            'kelurahan_id' => 'required',
            'partai_id' => 'required',
            'korcam_id' => 'required',
            'tps_id' => 'required',
            'deleted' => 'nullable',
        ]);

        $korlur = Korlur::find($id);
        $korlur->update($validatedData);

        $log = new LogKorlur();

        $log->user_id =  Auth::user()->id;
        $log->action = '2';

        $changes = [];
        if ($oldKorlur->nama != $korlur->nama) {
            $changes[] = 'nama from ' . $oldKorlur->nama . ' to ' . $korlur->nama;
        }
        if ($oldKorlur->nik != $korlur->nik) {
            $changes[] = 'nik from ' . $oldKorlur->nik . ' to ' . $korlur->nik;
        }
        if ($oldKorlur->kabkota_id != $korlur->kabkota_id) {
            $oldKabkota = Kabkota::find($oldKorlur->kabkota_id)->nama;
            $Kabkota = Kabkota::find($korlur->kabkota_id)->nama;

            $changes[] = 'kota from ' . $oldKabkota . ' to ' . $Kabkota;
        }
        if ($oldKorlur->tgl_lahir != $korlur->tgl_lahir) {
            $changes[] = 'tanggal from ' . $oldKorlur->tgl_lahir . ' to ' . $korlur->tgl_lahir;
        }
        if ($oldKorlur->alamat != $korlur->alamat) {
            $changes[] = 'alamat from ' . $oldKorlur->alamat . ' to ' . $korlur->alamat;
        }
        if ($oldKorlur->rt != $korlur->rt) {
            $changes[] = 'rt from ' . $oldKorlur->rt . ' to ' . $korlur->rt;
        }
        if ($oldKorlur->rw != $korlur->rw) {
            $changes[] = 'rw from ' . $oldKorlur->rw . ' to ' . $korlur->rw;
        }
        if ($oldKorlur->kelurahan_id != $korlur->kelurahan_id) {
            $oldkelurahan = Kelurahan::find($oldKorlur->kelurahan_id)->nama;
            $kelurahan = Kelurahan::find($korlur->kelurahan_id)->nama;

            $changes[] = 'kelurahan from ' . $oldkelurahan . ' to ' . $kelurahan;
        }
        if ($oldKorlur->phone != $korlur->phone) {
            $changes[] = 'phone from ' . $oldKorlur->phone . ' to ' . $korlur->phone;
        }
        $oldStatus = $oldKorlur->status == 1 ? 'aktif' : 'non aktif';
        $status = $korlur->status == 1 ? 'aktif' : 'non aktif';
        if ($oldKorlur->status != $korlur->status) {
            $changes[] = 'status from ' . $oldStatus . ' to ' . $status;
        }
        if ($oldKorlur->keterangan != $korlur->keterangan) {
            $changes[] = 'keterangan from ' . $oldKorlur->keterangan . ' to ' . $korlur->keterangan;
        }
        if ($oldKorlur->partai_id != $korlur->partai_id) {
            $oldpartai = Partai::find($oldKorlur->partai_id)->nama;
            $partai = Partai::find($korlur->partai_id)->nama;

            $changes[] = 'partai from ' . $oldpartai . ' to ' . $partai;
        }

        if ($oldKorlur->korcam_id != $korlur->korcam_id) {
            $oldkorcam = Korcam::find($oldKorlur->korcam_id)->nama;
            $korcam = Korcam::find($korlur->korcam_id)->nama;

            $changes[] = 'korcam from ' . $oldkorcam . ' to ' . $korcam;
        }

        $log->log = 'update data ' . implode(', ', $changes);
        $log->save();

        return redirect('koordinator/korlur');
    }

    public function destroy($id)
    {
        $korlur = Korlur::find($id);

        $korlur->deleted = "1";
        $korlur->save();

        $log = new LogKorlur();

        $log->user_id =  Auth::user()->id;
        $log->action = '3';
        $log->log = 'delete KORLUR ' . $korlur->nama . ' with nik ' . $korlur->nik;
        $log->save();

        return redirect()->back();
    }

    public function report(Request $request){

        $nik = $request->input('nik');
        $korcam = $request->input('korcam');
        $kelurahan = $request->input('kelurahan');
        $partai = $request->input('partai');

        $partais = Partai::orderBy('nama', 'asc')->get();

        $data = Korlur::where('deleted','0')
        ->whereHas('korcams', function ($q){
            $q->where('deleted', '0');
        })
        ->withCount('agent_tps_data')->orderBy('created_at', 'desc');

        if ($request['nik']) {
            $data = $data->where('nama', 'like', '%' . $request['nik'] . '%')
                ->orWhere('nik', $request['nik']);
        }

        if ($request['korcam']) {
            $data = $data->whereHas('korcams', function ($korcams) use ($request) {
                $korcams->where('nama', 'like', '%' . $request['korcam'] . '%');
            });
        }

        if ($request['kelurahan']) {
            $data = $data->whereHas('kelurahans', function ($kelurahans) use ($request) {
                $kelurahans->where('nama_kelurahan', 'like', '%' . $request['kelurahan'] . '%');
            });
        }

        if ($request['partai']) {
            $data = $data->where('partai_id', $request['partai'] );
        }

        $korlur = $data->paginate(20)->appends($request->all());

        return view('layouts.korlur.report',[
            'korlur' => $korlur,
            'partais' => $partais,
            'request' => [
                'nik' => $nik,
                'korcam' => $korcam,
                'kelurahan' => $kelurahan,
                'partai' => $partai,
                // 'kecamatan' => $kecamatan,
                // 'partai' => $partai,
            ],
        ]);
    }

    public function report_detail($id)
    {

        $agent = AgentTps::where('korlur_id', $id)
            ->where('deleted', '0')
            ->withCount('anggotas')
            ->get();

        $korlur = Korlur::find($id);

        return view('layouts.korlur.report_detail', [
            'agent' => $agent,
            'korlur' => $korlur,
            'menu' => 'report',
            'subMenu' => 'report_korcam'
        ]);
    }

    public function pdf($id)
    {
        $agent = AgentTps::where('korlur_id', $id)
            ->where('deleted', '0')
            ->withCount('anggotas')
            ->get();

            // dd($agent);

        $korlur = Korlur::find($id);

        return view('layouts.korlur.pdf', [
            'agent' => $agent,
            'korlur' => $korlur,
            'menu' => 'report',
            'subMenu' => 'report_korcam'
        ]);
    }

    public function general_excel(){

        return Excel::download(new KorlurGeneralExport(), 'korAgenGeneral.xlsx');
    }

    public function excel($id){

        return Excel::download(new KorlurExport($id), 'korlur.xlsx');

    }

    public function cek_data(Request $request)
    {
        $nik = $request->input('nik');

        $query = DB::table('agent_tps AS at')
            ->select(
                'at.nama',
                'at.nik',
                'at.phone',
                'k.nama_kelurahan AS kelurahan',
                'at.keterangan',
                DB::raw('COUNT(a.id) AS jumlah_anggota'),
                DB::raw('COUNT(CASE WHEN a.verified = "1" THEN 1 ELSE NULL END) AS berhasil')
            )
            ->leftJoin('korlurs AS kl', 'kl.id', '=', 'at.korlur_id')
            ->leftJoin('kelurahans AS k', 'k.id', '=', 'at.kelurahan_id')
            ->leftJoin('anggotas AS a', 'at.id', '=', 'a.agent_id')
            ->groupBy('at.nama', 'at.nik', 'at.phone', 'k.nama_kelurahan', 'at.keterangan');

        if ($request['nik']) {
            $query->where('kl.nik', $request['nik']);
        } else {
            $agent = $query;
            $agent = [];
            return view('layouts.korlur.cek_data', [
                'request' => [
                    'nik' => $nik
                ],
                'agent' => $agent
            ]);
        }

        $agent = $query->get();

        return view('layouts.korlur.cek_data', [
            'agent' => $agent,
        ]);
    }

    public function log(Request $request)
    {
        $date_start = $request->input('date_start', now()->format('Y-m-d'));
        $date_end = $request->input('date_end', now()->format('Y-m-d'));

        $logKorlur = LogKorlur::with('users')->orderBy('created_at','desc');

        if ($request['date_start']) {
            $logKorlur->whereDate('created_at', '>=', $request['date_start']);
        }

        if ($request['date_end']) {
            $logKorlur->whereDate('created_at', '<=', $request['date_end']);
        }

        $log = $logKorlur->paginate(15);
        $log->appends($request->all());

        return view('layouts.korlur.log', [
            'log' => $log,

            'request' => [
                'date_start' => $date_start,
                'date_end' => $date_end,
            ],
        ]);
    }

}
