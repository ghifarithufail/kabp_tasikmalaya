<?php

namespace App\Http\Controllers;

use App\Exports\KorcamExport;
use App\Exports\KorcamGeneralExport;
use App\Models\Korcam;
use App\Models\Korlur;
use App\Models\Partai;
use App\Models\Kabkota;
use App\Models\Kecamatan;
use App\Models\LogKorcam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Excel;


class KorcamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $nik = $request->input('nik');
        $kecamatan = $request->input('kecamatan');
        $partai = $request->input('partai');
        $partais = Partai::orderBy('nama', 'asc')->get();



        if (Auth::user()->role == '1' || Auth::user()->role == '2' || Auth::user()->role == '5') {
            $data = Korcam::with('partais', 'kecamatans')->orderBy('created_at', 'desc')->where('deleted', '0');
        } else {
            $data = Korcam::with('partais', 'kecamatans')
                ->orderBy('created_at', 'desc')
                ->where('deleted', '0')
                ->where('partai_id', Auth::user()->partai);
        }

        if ($request['nik']) {
            $data = $data->where('nama', 'like', '%' . $request['nik'] . '%')
                ->orWhere('nik', $request['nik']);
        }

        if ($request['kecamatan']) {
            $data = $data->whereHas('kecamatans', function ($kecamatans) use ($request) {
                $kecamatans->where('nama', 'like', '%' . $request['kecamatan'] . '%');
            });
        }

        if ($request['partai']) {
            $data = $data->where('partai_id', $request['partai'] );
        }


        $korcams = $data->paginate(20)->appends($request->all());

        return view('layouts.korcam.index', [
            'korcams' => $korcams,
            'partais' => $partais,
            'menu' => 'koordinator',
            'subMenu' => 'korcam',
            'request' => [
                'nik' => $nik,
                'kecamatan' => $kecamatan,
                'partai' => $partai,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role == '1') {
            $partai = Partai::orderBy('nama', 'asc')->where('deleted', '0')->get();
        } else {
            $partai = Partai::orderBy('nama', 'asc')
                ->where('deleted', '0')
                ->where('id', Auth::user()->partai)
                ->get();
        }
        $kecamatan = Kecamatan::orderBy('nama', 'asc')->where('deleted', '0')->get();
        $kabkota = Kabkota::orderBy('nama', 'asc')->get();

        return view('layouts.korcam.create', [
            'partai' => $partai,
            'kecamatan' => $kecamatan,
            'kabkota' => $kabkota,
            'menu' => 'koordinator',
            'subMenu' => 'korcam'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nik' => 'required|unique:korcams,nik|numeric|digits:16',
            'nama' => 'required',
            'kabkota_id' => 'nullable',
            'alamat' => 'nullable',
            'rt' => 'required',
            'rw' => 'required',
            'kecamatan_id' => 'required',
            'phone' => 'required',
            'status' => 'required',
            'keterangan' => 'nullable',
            'tgl_lahir' => 'nullable',
            'partai_id' => 'required',
            'deleted' => 'nullable',
        ], [
            'nama.required' => 'Nama harus diisi',
            'phone.required' => 'No Telpon harus diisi',
            'nik.required' => 'NIK harus diisi',
            'nik.unique' => 'NIK sudah terdaftar.',
            'nik.digits' => 'Jumlah NIK kurang atau Lebih Dari 16 Angka.',
            'kabkota_id.required' => 'KABKOTA harus diisi',
            'tgl_lahir.required' => 'Tanggal Lahir harus diisi',
            'rt.required' => 'RT harus diisi',
            'rw.required' => 'RW harus diisi',
            'kecamatan_id.required' => 'Kecamatan harus diisi',
            'status.required' => 'Status harus diisi',
            'partai_id.required' => 'Partai harus diisi',
            'alamat.required' => 'Alamat  harus diisi',
        ]);

        // \Log::info("input : " . json_encode($validatedData));

        $korcam = new Korcam($validatedData);
        $korcam->user_id = Auth::user()->id;
        $korcam->save();

        $log = new LogKorcam();
        $log->user_id = Auth::user()->id;
        $log->action = '1';
        $log->log = 'Create korcam ' . $request->nama . ' with nik ' . $request->nik;
        $log->save();

        return redirect()->route('koordinator/korcam');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $korcam = Korcam::find($id);
        $partai = Partai::orderBy('nama', 'asc')->get();

        return view('layouts.korcam.edit', [
            'korcam' => $korcam,
            'partai' => $partai,
            'menu' => 'koordinator',
            'subMenu' => 'korcam'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $oldKorcam = Korcam::findOrFail($id);


        $validatedData = $request->validate([
            'nik' => 'required|unique:korcams,nik,' . $id,
            'nama' => 'required',
            'kabkota_id' => 'nullable',
            'alamat' => 'nullable',
            'rt' => 'required',
            'rw' => 'required',
            'kecamatan_id' => 'required',
            'phone' => 'required',
            'status' => 'required',
            'keterangan' => 'nullable',
            'tgl_lahir' => 'nullable',
            'partai_id' => 'required',
            'deleted' => 'nullable',
        ]);

        $korcam = Korcam::findOrFail($id);
        $korcam->update($validatedData);

        $log = new LogKorcam();

        $log->user_id =  Auth::user()->id;
        $log->action = '2';

        $changes = [];
        if ($oldKorcam->nama != $korcam->nama) {
            $changes[] = 'nama from ' . $oldKorcam->nama . ' to ' . $korcam->nama;
        }
        if ($oldKorcam->nik != $korcam->nik) {
            $changes[] = 'nik from ' . $oldKorcam->nik . ' to ' . $korcam->nik;
        }
        if ($oldKorcam->kabkota_id != $korcam->kabkota_id) {
            $oldKabkota = Kabkota::find($oldKorcam->kabkota_id)->nama;
            $Kabkota = Kabkota::find($korcam->kabkota_id)->nama;

            $changes[] = 'kota from ' . $oldKabkota . ' to ' . $Kabkota;
        }
        if ($oldKorcam->tgl_lahir != $korcam->tgl_lahir) {
            $changes[] = 'tanggal from ' . $oldKorcam->tgl_lahir . ' to ' . $korcam->tgl_lahir;
        }
        if ($oldKorcam->alamat != $korcam->alamat) {
            $changes[] = 'alamat from ' . $oldKorcam->alamat . ' to ' . $korcam->alamat;
        }
        if ($oldKorcam->rt != $korcam->rt) {
            $changes[] = 'rt from ' . $oldKorcam->rt . ' to ' . $korcam->rt;
        }
        if ($oldKorcam->rw != $korcam->rw) {
            $changes[] = 'rw from ' . $oldKorcam->rw . ' to ' . $korcam->rw;
        }
        if ($oldKorcam->kecamatan_id != $korcam->kecamatan_id) {
            $oldkecamatan = Kecamatan::find($oldKorcam->kecamatan_id)->nama;
            $kecamatan = Kecamatan::find($korcam->kecamatan_id)->nama;

            $changes[] = 'kecamatan from ' . $oldkecamatan . ' to ' . $kecamatan;
        }
        if ($oldKorcam->phone != $korcam->phone) {
            $changes[] = 'phone from ' . $oldKorcam->phone . ' to ' . $korcam->phone;
        }
        $oldStatus = $oldKorcam->status == 1 ? 'aktif' : 'non aktif';
        $status = $korcam->status == 1 ? 'aktif' : 'non aktif';
        if ($oldKorcam->status != $korcam->status) {
            $changes[] = 'status from ' . $oldStatus . ' to ' . $status;
        }
        if ($oldKorcam->keterangan != $korcam->keterangan) {
            $changes[] = 'keterangan from ' . $oldKorcam->keterangan . ' to ' . $korcam->keterangan;
        }
        if ($oldKorcam->partai_id != $korcam->partai_id) {
            $oldpartai = Partai::find($oldKorcam->partai_id)->nama;
            $partai = Partai::find($korcam->partai_id)->nama;

            $changes[] = 'partai from ' . $oldpartai . ' to ' . $partai;
        }

        $log->log = 'update data ' . implode(', ', $changes);
        $log->save();


        return redirect()->route('koordinator/korcam');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $korcam = Korcam::find($id);

        $korcam->deleted = "1";
        $korcam->save();

        $log = new LogKorcam();

        $log->user_id =  Auth::user()->id;
        $log->action = '3';
        $log->log = 'delete korcam ' . $korcam->nama . ' with nik ' . $korcam->nik;
        $log->save();

        return redirect()->back();
    }

    public function report(Request $request)
    {
        $nik = $request->input('nik');
        $kecamatan = $request->input('kecamatan');
        $partai = $request->input('partai');
        $partais = Partai::orderBy('nama', 'asc')->get();

        $data = Korcam::withCount('korlurs');

        if ($request['nik']) {
            $data = $data->where('nama', 'like', '%' . $request['nik'] . '%')
                ->orWhere('nik', $request['nik']);
        }

        if ($request['kecamatan']) {
            $data = $data->whereHas('kecamatans', function ($kecamatans) use ($request) {
                $kecamatans->where('nama', 'like', '%' . $request['kecamatan'] . '%');
            });
        }

        if ($request['partai']) {
            $data = $data->where('partai_id', $request['partai'] );
        }


        $korcam = $data->paginate(20)->appends($request->all());

        return view('layouts.korcam.report', [
            'korcam' => $korcam,
            'partais' => $partais,
            'menu' => 'report',
            'subMenu' => 'report_korcam',
            'request' => [
                'nik' => $nik,
                'kecamatan' => $kecamatan,
                'partai' => $partai,
            ],
        ]);
    }

    public function generelExcel(){
        return Excel::download(new KorcamGeneralExport(), 'korcamGeneral.xlsx');

    }

    public function report_detail($id)
    {

        $korlur = Korlur::where('korcam_id', $id)
            ->where('deleted', '0')
            ->with('agent_tps')
            ->withCount(['agent_tps as anggota_count' => function ($query) {
                $query->leftJoin('anggotas', 'agent_tps.id', '=', 'anggotas.agent_id')
                    ->where('anggotas.deleted', '0');
            }])
            ->get();

        $korcam = Korcam::find($id);

        return view('layouts.korcam.report_detail', [
            'korlur' => $korlur,
            'korcam' => $korcam,
            'menu' => 'report',
            'subMenu' => 'report_korcam'
        ]);
    }

    public function pdf($id)
    {

        $korlur = Korlur::where('korcam_id', $id)
            ->where('deleted', '0')
            ->with('agent_tps')
            ->withCount(['agent_tps as anggota_count' => function ($query) {
                $query->leftJoin('anggotas', 'agent_tps.id', '=', 'anggotas.agent_id')
                    ->where('anggotas.deleted', '0');
            }])
            ->get();

        $korcam = Korcam::find($id);

        return view('layouts.korcam.pdf', [
            'korlur' => $korlur,
            'korcam' => $korcam,
            'menu' => 'report',
            'subMenu' => 'report_korcam'
        ]);
    }

    public function excel($id)
    {

        return Excel::download(new KorcamExport($id), 'korcam.xlsx');
    }

    public function cek_data(Request $request)
    {
        $nik = $request->input('nik');

        $query = DB::table('korlurs AS kl')
            ->select(
                'kl.nama',
                'kl.nik',
                'kl.phone',
                'k.nama_kelurahan AS kelurahan',
                'kl.keterangan',
                DB::raw('COUNT(a.id) AS jumlah_anggota'),
                DB::raw('COUNT(CASE WHEN a.verified = "1" THEN 1 ELSE NULL END) AS berhasil')
            )
            ->leftJoin('korcams AS kc', 'kc.id', '=', 'kl.korcam_id')
            ->leftJoin('kelurahans AS k', 'kl.kelurahan_id', '=', 'k.id')
            ->leftJoin('agent_tps AS at', 'kl.id', '=', 'at.korlur_id')
            ->leftJoin('anggotas AS a', 'at.id', '=', 'a.agent_id')
            ->groupBy('kl.nama', 'kl.nik', 'kl.phone', 'k.nama_kelurahan', 'kl.keterangan');

        if ($request['nik']) {
            $query->where('kc.nik', $request['nik']);
        } else {
            $korlur = $query;
            $korlur = [];
            return view('layouts.korcam.cek_data', [
                'request' => [
                    'nik' => $nik
                ],
                'korlur' => $korlur
            ]);
        }

        $korlur = $query->get();

        return view('layouts.korcam.cek_data', [
            'korlur' => $korlur,
        ]);
    }

    public function log(Request $request)
    {
        $date_start = $request->input('date_start', now()->format('Y-m-d'));
        $date_end = $request->input('date_end', now()->format('Y-m-d'));

        $logKorcam = LogKorcam::with('users')->orderBy('created_at', 'desc');

        if ($request['date_start']) {
            $logKorcam->whereDate('created_at', '>=', $request['date_start']);
        }

        if ($request['date_end']) {
            $logKorcam->whereDate('created_at', '<=', $request['date_end']);
        }

        $log = $logKorcam->paginate(15);
        $log->appends($request->all());

        return view('layouts.korcam.log', [
            'log' => $log,

            'request' => [
                'date_start' => $date_start,
                'date_end' => $date_end,
            ],
        ]);
    }
}
