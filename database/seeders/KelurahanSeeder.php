<?php

namespace Database\Seeders;

use App\Imports\KelurahanGarutTasikImport;
use App\Imports\KelurahansImport;
use App\Models\Kelurahan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class KelurahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Kelurahan::create([
        //     'nama_kelurahan' => 'BUNGURSARI',
        //     'kecamatan_id' => '1',
        //     'dapil' => 'DAPIL 1',
        //     'kabkota' => 'KOTA TASIKMALAYA',
        //     'provinsi' => 'JAWA BARAT',
        //     'kode_kel' => '32.78.09.1003',
        //     'deleted' => '0',
        // ]);

        // Path ke file Excel
        $excelPath = database_path('seeders/data/lokasi_tps_pilkada_2024.xlsx');
        $excelGarutTasikPath = database_path('seeders/data/data_kabupaten_garut_tasikmalaya.xlsx');

        // Import data dari file Excel
        Excel::import(new KelurahansImport, $excelPath);
        Excel::import(new KelurahanGarutTasikImport, $excelGarutTasikPath);


        // $data = Excel::inport(new KelurahanGarutTasikImport, $excelGarutTasikPath);

        // foreach ($data[0] as $row) {
        //     dd($row);
        //     if($row['nama_kelurahan']){
        //         Kelurahan::create([
        //             'nama_kelurahan' => $row['nama_kelurahan'],
        //             'kecamatan_id' => $row['kecamatan_id'],
        //             'dapil' => $row['dapil'],
        //             'kabkota' => $row['kabkota'],
        //             'provinsi' => 'JAWA BARAT',
        //             'deleted' => '0',
        //         ]);

        //     }
        // }
    }

}
