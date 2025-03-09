<?php

namespace App\Http\Controllers;

use App\Exports\AgentExport;
use App\Exports\AgentGeneralExport;
use App\Exports\CalonsExport;
use App\Exports\TandaTerimaExport;
use App\Models\AgentTps;
use App\Models\Anggota;
use App\Models\Kabkota;
use App\Models\Kelurahan;
use App\Models\Korcam;
use App\Models\Korlur;
use App\Models\LogAgent;
use App\Models\Partai;
use App\Models\Tps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AgentTpsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $nik = $request->input('nik');
        $korlur = $request->input('korlur');
        $kelurahan = $request->input('kelurahan');
        $partai = $request->input('partai');

        $partais = Partai::orderBy('nama', 'asc')->get();


        if(Auth::user()->role == '1' || Auth::user()->role == '2' || Auth::user()->role == '5'){
            $agents = AgentTps::with('kelurahans')->where('deleted', '0')
                ->orderBy('created_at', 'desc');
        } else {
            $agents = AgentTps::with('kelurahans')
                ->where('deleted', '0')
                ->where('partai_id', Auth::user()->partai)
                ->orderBy('created_at', 'desc');
        }

        if ($request['nik']) {
            $agents = $agents->where('nama', 'like', '%' . $request['nik'] . '%')
                ->orWhere('nik', $request['nik']);
        }

        if ($request['korlur']) {
            $agents = $agents->whereHas('korlurs', function ($korlurs) use ($request) {
                $korlurs->where('nama', 'like', '%' . $request['korlur'] . '%');
            });
        }

        if ($request['kelurahan']) {
            $agents = $agents->whereHas('kelurahans', function ($kelurahans) use ($request) {
                $kelurahans->where('nama_kelurahan', 'like', '%' . $request['kelurahan'] . '%');
            });
        }

        if ($request['partai']) {
            $agents = $agents->where('partai_id', $request['partai']);
        }

        $agent = $agents->paginate(20)->appends($request->all());
        // dd($agent);

        return view('layouts.agent_tps.index', [
            'agent' => $agent,
            'partais' => $partais,
            'menu' => 'koordinator',
            'request' => [
                'nik' => $nik,
                'korlur' => $korlur,
                'kelurahan' => $kelurahan,
                'partai' => $partai,
                // 'kecamatan' => $kecamatan,
                // 'partai' => $partai,
            ],
        ]);
    }

    public function add_koordinator($id)
    {

        $agent = AgentTps::find($id);
        $agent->is_koordinator = '1';
        $agent->save();

        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $partai = Partai::orderBy('nama', 'asc')->where('deleted', '0')->get();

        return view('layouts.agent_tps.create', [
            'partai' => $partai,
            'menu' => 'koordinator',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nik' => 'required|unique:agent_tps,nik|numeric|digits:16',
            'nama' => 'required',
            'kabkota_id' => 'nullable',
            'alamat' => 'nullable',
            'rt' => 'required',
            'rw' => 'required',
            'kelurahan_id' => 'required',
            'korlur_id' => 'required',
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
            'kelurahan_id.required' => 'KELURAHAN harus diisi',
            'status.required' => 'Status harus diisi',
            'partai_id.required' => 'Partai harus diisi',
            'alamat.required' => 'Alamat  harus diisi',
            'korlur_id.required' => 'Koordinator Tps harus diisi',
        ]);

        $agent = new AgentTps($validatedData);
        $agent->user_id = Auth::user()->id;
        $agent->save();

        $agent->tps_pivot()->sync($request->input('tps_id', []));

        $log = new LogAgent();
        $log->user_id = Auth::user()->id;
        $log->action = '1';
        $log->log = 'Create Agent ' . $request->nama . ' with nik ' . $request->nik;
        $log->save();



        return redirect()->route('koordinator/agent');
    }

    /**
     * Display the specified resource.
     */
    public function show(AgentTps $agentTps)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $agent = AgentTps::with(['tps_pivot', 'partais', 'kelurahans' => function($kelurahan){
            $kelurahan->with(['kabkotas']);
        }])->findOrFail($id);
        $tps = Tps::orderBy('tps', 'asc')->get();
        $partai = Partai::orderBy('nama', 'asc')->get();

        // Load kelurahans associated with tps using eager loading
        $kelurahans = Kelurahan::whereIn('id', $tps->pluck('kelurahan_id'))->get()->keyBy('id');

        // dd($agent);
        return view('layouts.agent_tps.edit', [
            'agent' => $agent,
            'partai' => $partai,
            'tps' => $tps,
            'kelurahans' => $kelurahans,
            'menu' => 'koordinator',
            'subMenu' => 'agent'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $oldAgent = AgentTps::findOrFail($id);

        $validatedData = $request->validate([
            'nik' => 'required|unique:agent_tps,nik,' . $id,
            'nama' => 'required',
            'kabkota_id' => 'required',
            'alamat' => 'required',
            'rt' => 'required',
            'rw' => 'required',
            'kelurahan_id' => 'required',
            'korlur_id' => 'required',
            'phone' => 'required',
            'status' => 'required',
            'keterangan' => 'nullable',
            'tgl_lahir' => 'nullable',
            'partai_id' => 'required',
            'deleted' => 'nullable',
        ]);

        $agent = AgentTps::find($id);
        $agent->update($validatedData);

        $agent->tps_pivot()->sync($request->input('tps_id', []));

        $log = new LogAgent();

        $log->user_id =  Auth::user()->id;
        $log->action = '2';

        $changes = [];
        if ($oldAgent->nama != $agent->nama) {
            $changes[] = 'nama from ' . $oldAgent->nama . ' to ' . $agent->nama;
        }
        if ($oldAgent->nik != $agent->nik) {
            $changes[] = 'nik from ' . $oldAgent->nik . ' to ' . $agent->nik;
        }
        if ($oldAgent->kabkota_id != $agent->kabkota_id) {
            $oldKabkota = Kabkota::find($oldAgent->kabkota_id)->nama;
            $Kabkota = Kabkota::find($agent->kabkota_id)->nama;

            $changes[] = 'kota from ' . $oldKabkota . ' to ' . $Kabkota;
        }
        if ($oldAgent->tgl_lahir != $agent->tgl_lahir) {
            $changes[] = 'tanggal from ' . $oldAgent->tgl_lahir . ' to ' . $agent->tgl_lahir;
        }
        if ($oldAgent->alamat != $agent->alamat) {
            $changes[] = 'alamat from ' . $oldAgent->alamat . ' to ' . $agent->alamat;
        }
        if ($oldAgent->rt != $agent->rt) {
            $changes[] = 'rt from ' . $oldAgent->rt . ' to ' . $agent->rt;
        }
        if ($oldAgent->rw != $agent->rw) {
            $changes[] = 'rw from ' . $oldAgent->rw . ' to ' . $agent->rw;
        }
        if ($oldAgent->kelurahan_id != $agent->kelurahan_id) {
            $oldkelurahan = Kelurahan::find($oldAgent->kelurahan_id)->nama;
            $kelurahan = Kelurahan::find($agent->kelurahan_id)->nama;

            $changes[] = 'kelurahan from ' . $oldkelurahan . ' to ' . $kelurahan;
        }
        if ($oldAgent->phone != $agent->phone) {
            $changes[] = 'phone from ' . $oldAgent->phone . ' to ' . $agent->phone;
        }
        $oldStatus = $oldAgent->status == 1 ? 'aktif' : 'non aktif';
        $status = $agent->status == 1 ? 'aktif' : 'non aktif';
        if ($oldAgent->status != $agent->status) {
            $changes[] = 'status from ' . $oldStatus . ' to ' . $status;
        }

        if ($oldAgent->keterangan != $agent->keterangan) {
            $changes[] = 'keterangan from ' . $oldAgent->keterangan . ' to ' . $agent->keterangan;
        }

        if ($oldAgent->partai_id != $agent->partai_id) {
            $oldpartai = Partai::find($oldAgent->partai_id)->nama;
            $partai = Partai::find($agent->partai_id)->nama;

            $changes[] = 'partai from ' . $oldpartai . ' to ' . $partai;
        }

        if ($oldAgent->korlur_id != $agent->korlur_id) {
            $oldkorlur = Korlur::find($oldAgent->korlur_id)->nama;
            $korlur = Korlur::find($agent->korlur_id)->nama;

            $changes[] = 'korlur from ' . $oldkorlur . ' to ' . $korlur;
        }

        $oldTPS = $oldAgent->tps_pivot->pluck('id')->toArray();
        $newTPS = $request->input('tps_id', []);

        $addedTPS = array_diff($newTPS, $oldTPS);

        // Mendapatkan nama TPS sebelum pembaruan
        $oldTPSNames = TPS::whereIn('id', $oldTPS)->pluck('tps')->toArray();

        // Mendapatkan nama TPS yang baru ditambahkan
        $newAddedTPSNames = TPS::whereIn('id', $addedTPS)->pluck('tps')->toArray();

        // Membuat pesan perubahan TPS
        if (!empty($newAddedTPSNames)) {
            $changes[] = 'added TPS from ' . implode(', ', $oldTPSNames) . ' to ' . implode(', ', $newAddedTPSNames);
        }

        $log->log = 'update data ' . implode(', ', $changes);
        $log->save();


        return redirect()->route('koordinator/agent');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $agent = AgentTps::find($id);

        $agent->deleted = "1";
        $agent->save();

        $log = new LogAgent();

        $log->user_id =  Auth::user()->id;
        $log->action = '3';
        $log->log = 'delete agent ' . $agent->nama . ' with nik ' . $agent->nik;
        $log->save();

        return redirect()->back();
    }

    public function report(Request $request)
    {
        // $agent = AgentTps::where('deleted', '0')
        //     ->withCount(['anggotas' => function ($q) {
        //         $q->where('deleted', '0');
        //     }])->get();

        $nik = $request->input('nik');
        $korlur = $request->input('korlur');
        $kelurahan = $request->input('kelurahan');
        $partai = $request->input('partai');

        $partais = Partai::orderBy('nama', 'asc')->get();

        if ($request->hasAny(['nik', 'korlur', 'kelurahan', 'partai'])) {
        $agents = DB::table('agent_tps AS at')
            ->select(
                'at.nama AS nama_koordinator',
                'at.phone',
                't.id AS id_tps',
                'at.id',
                'at.nik',
                'kk.nama',
                'at.tgl_lahir',
                'at.alamat',
                'at.rt',
                'at.rw',
                'k.nama_kelurahan',
                'k.kabkota',
                'kh.nama AS nama_korhan',
                'at.keterangan',
                't.tps AS tps',
                'at.status',
                't.target AS target',
                 DB::raw('COUNT(CASE WHEN a.deleted = "0" THEN a.id END) AS belum'),
                DB::raw('COUNT(CASE WHEN a.verified = "1" THEN 1 ELSE NULL END) AS berhasil')
            )
            ->leftJoin('kelurahans AS k', 'at.kelurahan_id', '=', 'k.id')
            ->leftJoin('korlurs AS kh', 'kh.id', '=', 'at.korlur_id')
            ->leftJoin('anggotas AS a', 'at.id', '=', 'a.agent_id')
            ->leftJoin('tps AS t', 'a.tps_id', '=', 't.id')
            ->leftJoin('kabkotas AS kk', 'at.kabkota_id', '=', 'kk.id')
            ->where('at.deleted', '0')
            ->groupBy(
                'at.nama',
                'at.phone',
                't.id',
                // 'at.id',
                // 'at.nik',
                // 'kk.nama',
                // 'at.tgl_lahir',
                // 'at.alamat',
                // 'at.rt',
                // 'at.rw',
                // 'k.nama_kelurahan',
                // 'k.kabkota',
                // 'kh.nama',
                // 'at.keterangan',
                // 't.tps',
                // 'at.status',
                // 't.target'
            );

        if (Auth::user()->role != '1' && Auth::user()->role != '2' && Auth::user()->role != '5') {
            $agents->where('at.partai_id', Auth::user()->partai);
        }

        if ($request['nik']) {
            $agents = $agents->where('at.nama',  'like', '%' . $request['nik'] . '%')
                ->orWhere('at.nik', $request['nik']);
        }

        if ($request['korlur']) {
            $agents = $agents->where('kh.nama', 'like', '%' . $request['korlur'] . '%');
        }

        if ($request['kelurahan']) {
            $agents = $agents->where('k.nama_kelurahan', 'like', '%' . $request['kelurahan'] . '%');
        }

        if ($request['partai']) {
            $agents = $agents->where('at.partai_id', $request['partai']);
        }

        $agent = $agents->paginate(20)->appends($request->all());
    } else {
        // Return an empty collection or array if there are no request parameters
        $agent = []; // or use [] for an empty array
    }   // dd($agent);
        return view('layouts.agent_tps.report', [
            'agent' => $agent,
            'partais' => $partais,
            'request' => [
                'nik' => $nik,
                'korlur' => $korlur,
                'kelurahan' => $kelurahan,
                'partai' => $partai,
                // 'kecamatan' => $kecamatan,
                // 'partai' => $partai,
            ],
        ]);
    }

    public function generalExcel()
    {
        return Excel::download(new AgentGeneralExport(), 'AgentGeneral.xlsx');
    }

    public function report_detail(Request $request, $id, $tps)
    {
        $anggota = Anggota::where('agent_id', $id)
            ->where('tps_id', $tps)
            ->where('deleted', '0')->get();

        // dd($anggota);

        $agent = AgentTps::find($id);
        $jumlahAnggota = $anggota->count();
        $verifikasi = $anggota->where('verified', '1')->count();


        return view('layouts.agent_tps.report_detail', [
            'anggota' => $anggota,
            'jumlahAnggota' => $jumlahAnggota,
            'verifikasi' => $verifikasi,
            'agent' => $agent,
        ]);
    }

    public function pdf(Request $request, $id, $tps)
    {
        $anggota = Anggota::where('agent_id', $id)
            ->where('tps_id', $tps)
            ->where('verified', '1')

            // ->with('kabkotas', 'tps', 'koordinators')
            ->where('deleted', '0')->get();

            $anggota_gagal = Anggota::where('agent_id', $id)
            ->where('tps_id', $tps)
            ->where('verified', '2')

            // ->with('kabkotas', 'tps', 'koordinators')
            ->where('deleted', '0')->get();

        // dd($anggota);

        $agent = AgentTps::find($id);

        $lolos = Anggota::where('agent_id', $id)
            ->where('tps_id', $tps)
            ->where('verified', '1')

            // ->with('kabkotas', 'tps', 'koordinators')
            ->where('deleted', '0')->count();

        $gagal = Anggota::where('agent_id', $id)
            ->where('tps_id', $tps)
            ->where('verified', '2')

            // ->with('kabkotas', 'tps', 'koordinators')
            ->where('deleted', '0')->count();


        return view('layouts.agent_tps.pdf', [
            'anggota' => $anggota,
            'anggota_gagal' => $anggota_gagal,
            'lolos' => $lolos,
            'gagal' => $gagal,
            'agent' => $agent,
        ]);
    }

    public function excel($id, $tps)
    {

        return Excel::download(new AgentExport($id, $tps), 'agent.xlsx');
    }

    public function cek_data(Request $request)
    {
        $nik = $request->input('nik');

        $query = DB::table('anggotas AS a')
            ->select(
                'a.nama',
                'a.nik',
                'a.phone',
                't.tps',
                'k.nama_kelurahan',
                'a.keterangan'
            )
            ->leftJoin('agent_tps AS at', 'at.id', '=', 'a.agent_id')
            ->leftJoin('tps AS t', 't.id', '=', 'a.tps_id')
            ->leftJoin('kelurahans AS k', 'k.id', '=', 't.kelurahan_id');

        if ($request['nik']) {
            $query->where('at.nik', $request['nik']);
        } else {
            $agent = $query;
            $agent = [];
            return view('layouts.agent_tps.cek_data', [
                'request' => [
                    'nik' => $nik
                ],
                'agent' => $agent
            ]);
        }

        $agent = $query->get();

        return view('layouts.agent_tps.cek_data', [
            'agent' => $agent,
        ]);
    }

    public function log(Request $request)
    {
        $date_start = $request->input('date_start', now()->format('Y-m-d'));
        $date_end = $request->input('date_end', now()->format('Y-m-d'));

        $logAgent = LogAgent::with('users')->orderBy('created_at', 'desc');

        if ($request['date_start']) {
            $logAgent->whereDate('created_at', '>=', $request['date_start']);
        }

        if ($request['date_end']) {
            $logAgent->whereDate('created_at', '<=', $request['date_end']);
        }

        $log = $logAgent->paginate(15);
        $log->appends($request->all());

        return view('layouts.agent_tps.log', [
            'log' => $log,

            'request' => [
                'date_start' => $date_start,
                'date_end' => $date_end,
            ],
        ]);
    }

    public function exportTandaTerima(Request $request)
    {
        $korcams = Korcam::all();
        // $korlur = [];
        // $agent = [];
        // $anggotas = [];

        $data = [];

        foreach ($korcams as $korcam) {
            $tmpData = [];  // Data temporary untuk korcam
            $dataKorcam = ['korcam' => $korcam, 'korlurs' => []];  // Array korcam dengan korlurs sebagai child

            // Ambil korlurs yang berhubungan dengan korcam ini
            $korlurs = Korlur::where('korcam_id', $korcam->id)->get();

            foreach ($korlurs as $korlur) {
                $dataKorlur = ['korlur' => $korlur, 'agents' => []];  // Array korlur dengan agents sebagai child

                // Ambil agents yang berhubungan dengan korlur ini
                $agents = AgentTps::where('korlur_id', $korlur->id)->get();

                foreach ($agents as $agent) {
                    $dataAgent = ['agent' => $agent, 'anggotas' => []];  // Array agent dengan anggotas sebagai child

                    // Ambil anggotas yang berhubungan dengan agent ini
                    $anggotas = Anggota::where('agent_id', $agent->id)->get();

                    foreach ($anggotas as $anggota) {
                        // Masukkan anggota ke dalam list anggotas pada agent
                        $dataAgent['anggotas'][] = $anggota;
                    }

                    // Masukkan agent (beserta anggotas) ke dalam list agents pada korlur
                    $dataKorlur['agents'][] = $dataAgent;
                }

                // Masukkan korlur (beserta agents) ke dalam list korlurs pada korcam
                $dataKorcam['korlurs'][] = $dataKorlur;
            }

            // Masukkan korcam (beserta korlurs) ke dalam list data akhir
            $data[] = $dataKorcam;
        }

        // dd($data);
        $result = ['data' => $data];

        return Excel::download(new TandaTerimaExport($result), 'test.xlsx');
    }
}
