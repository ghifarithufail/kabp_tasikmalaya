<?php

namespace App\Http\Controllers;

use App\Exports\CalonsExport;
use App\Models\AgentTps;
use App\Models\Anggota;
use App\Models\Kelurahan;
use App\Models\Korcam;
use App\Models\Korlur;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DashboardController extends Controller
{
     public function index()
    {
        // $usersWithRoles = User::with('roles')->get(); // Eager load roles to reduce queries

        // $duplicateRoles = [];

        // foreach ($usersWithRoles as $user) {
        //     $roles = $user->getRoleNames();

        //     $duplicateRoles[] = [
        //         'user_id' => $user->id,
        //         'user_name' => $user->name,
        //         'roles' => $roles->toArray(),
        //     ];
        //     // // Check if the user has more than one role
        //     // if ($roles->count() > 1) {
        //     // }
        // }

        // // Output users with duplicate roles
        // dd($duplicateRoles);

        // if ($auth->role == 1) {
            $user = User::where('status', 1)->count();
            $korcam = Korcam::where('status', 1)->count();
            $korAgent = Korlur::where('status', 1)->count();
            $agent = AgentTps::where('status', 1)->count();
            // $anggota = Anggota::where('status', 0)->count();
            $Lolos = Anggota::where('verified', '1')->count();
            $Gagal = Anggota::where('verified', '2')->count();
            $account = Auth::user();
        // } else {
        //     $user = User::where('status', 1)
        //         ->where('partai', $auth->partai)
        //         ->count();
        //     $korcam = Korcam::where('status', 1)
        //         ->where('partai_id', $auth->partai)
        //         ->count();
        //     $korlur = Korlur::where('status', 1)->count();
        //     $agent = AgentTps::where('status', 1)->count();
        //     $anggota = Anggota::where('status', 1)->count();
        // }


        $countAnggota = Anggota::select(DB::raw('DATE_FORMAT(created_at, "%M") as month_name'), DB::raw('COUNT(id) as total'))
            // ->where('verified', '1') 
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();


        $months = [];
        $monthCounth = [];

        foreach ($countAnggota as $values) {
            $months[] = $values->month_name;
            $monthCounth[] = $values->total;
        }


        $data = [
            'user' => $user,
            'korcam' => $korcam,
            'korAgent' => $korAgent,
            'agent' => $agent,
            // 'anggota' => $anggota,
            'Lolos' => $Lolos,
            'Gagal' => $Gagal,
        ];

        return view('layouts.dashboard', [
            'data' => $data,
            'countAnggota' => $countAnggota,
            'months' => $months,
            'monthCounth' => $monthCounth,
            'account' => $account,

        ]);
    }
    public function generateGeoJson()
    {
        // Cek apakah folder 'public/data' ada, jika tidak buat foldernya
        $directory = public_path('data');
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $kelurahanData = DB::table('tps')
            ->select(
                'kelurahans.id AS id',
                'kelurahans.nama_kelurahan AS nama_kelurahan',
                'kelurahans.koordinat AS koordinat',
                DB::raw('SUM(tps.totdpt) AS total_totdpt'),
                DB::raw("SUM(CASE WHEN calons.id = 1 THEN perolehan_suaras.total_suara ELSE NULL END) AS 'paslon_1'"),
                DB::raw("SUM(CASE WHEN calons.id = 2 THEN perolehan_suaras.total_suara ELSE NULL END) AS 'paslon_2'"),
                DB::raw("SUM(CASE WHEN calons.id = 3 THEN perolehan_suaras.total_suara ELSE NULL END) AS 'paslon_3'"),
                DB::raw("SUM(CASE WHEN calons.id = 4 THEN perolehan_suaras.total_suara ELSE NULL END) AS 'paslon_4'"),
                DB::raw("SUM(CASE WHEN calons.id = 5 THEN perolehan_suaras.total_suara ELSE NULL END) AS 'paslon_5'")
            )
            ->leftJoin('kelurahans', 'tps.kelurahan_id', '=', 'kelurahans.id')
            ->leftJoin('perolehan_suaras', 'perolehan_suaras.tps_id', '=', 'tps.id')
            ->leftJoin('calons', 'calons.id', '=', 'perolehan_suaras.caleg_id')
            ->where('kelurahans.kabkota', '=', 29)
            ->groupBy('kelurahans.id', 'kelurahans.nama_kelurahan', 'kelurahans.koordinat')
            ->get();

        // Format GeoJSON
        $geoJson = [
            "type" => "FeatureCollection",
            "features" => []
        ];

        foreach ($kelurahanData as $kelurahan) {
            if ($kelurahan->koordinat) {
                $geoJson['features'][] = [
                    "type" => "Feature",
                    "id" => (string)$kelurahan->id,
                    "properties" => [
                        "name" => $kelurahan->nama_kelurahan,
                        "totalDpt" => $kelurahan->total_totdpt,
                        "paslon1" => $kelurahan->paslon_1,
                        "paslon2" => $kelurahan->paslon_2,
                        "paslon3" => $kelurahan->paslon_3,
                        "paslon4" => $kelurahan->paslon_4,
                        "paslon5" => $kelurahan->paslon_5
                    ],
                    "geometry" => [
                        "type" => "Polygon", // Sesuaikan dengan tipe geometri (Polygon, Point, etc)
                        "coordinates" => [json_decode($kelurahan->koordinat)] // Pastikan kolom 'koordinat' menyimpan data JSON
                    ]
                ];
            }
        }

        // Ubah array menjadi JSON string
        $geoJsonString = json_encode($geoJson, JSON_PRETTY_PRINT);

        // Simpan file GeoJSON di folder public
        File::put(public_path('data/kotatasikmalaya.geojson'), $geoJsonString);

        return response()->json(['message' => 'GeoJSON file has been generated and saved!']);
    }

    public function generateGeoJsonKabupatenTasik(){
        // Cek apakah folder 'public/data' ada, jika tidak buat foldernya
        $directory = public_path('data');
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Ambil semua data kelurahan menggunakan Query Builder
        $kelurahanData = DB::table('kelurahans')->where('kabkota', '=', '498')->get();

        // Format GeoJSON
        $geoJson = [
            "type" => "FeatureCollection",
            "features" => []
        ];

        foreach ($kelurahanData as $kelurahan) {
            if($kelurahan->koordinat){
                $geoJson['features'][] = [
                    "type" => "Feature",
                    "id" => (string)$kelurahan->id,
                    "properties" => [
                        "name" => $kelurahan->nama_kelurahan
                    ],
                    "geometry" => [
                        "type" => "Polygon", // Sesuaikan dengan tipe geometri (Polygon, Point, etc)
                        "coordinates" => [json_decode($kelurahan->koordinat)] // Pastikan kolom 'koordinat' menyimpan data JSON
                    ]
                ];
            }
        }

        // Ubah array menjadi JSON string
        $geoJsonString = json_encode($geoJson, JSON_PRETTY_PRINT);

        // Simpan file GeoJSON di folder public
        File::put(public_path('data/kabupatentasikmalaya.geojson'), $geoJsonString);

        return response()->json(['message' => 'GeoJSON file has been generated and saved!']);
    }

    public function generateGeoJsonKabupatenGarut(){
        // Cek apakah folder 'public/data' ada, jika tidak buat foldernya
        $directory = public_path('data');
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Ambil semua data kelurahan menggunakan Query Builder
        $kelurahanData = DB::table('kelurahans')->where('kabkota', '=', '18')->get();

        // Format GeoJSON
        $geoJson = [
            "type" => "FeatureCollection",
            "features" => []
        ];

        foreach ($kelurahanData as $kelurahan) {
            if($kelurahan->koordinat){
                $geoJson['features'][] = [
                    "type" => "Feature",
                    "id" => (string)$kelurahan->id,
                    "properties" => [
                        "name" => $kelurahan->nama_kelurahan
                    ],
                    "geometry" => [
                        "type" => "Polygon", // Sesuaikan dengan tipe geometri (Polygon, Point, etc)
                        "coordinates" => [json_decode($kelurahan->koordinat)] // Pastikan kolom 'koordinat' menyimpan data JSON
                    ]
                ];
            }
        }

        // Ubah array menjadi JSON string
        $geoJsonString = json_encode($geoJson, JSON_PRETTY_PRINT);

        // Simpan file GeoJSON di folder public
        File::put(public_path('data/kabupatengarut.geojson'), $geoJsonString);

        return response()->json(['message' => 'GeoJSON file has been generated and saved!']);
    }
}
