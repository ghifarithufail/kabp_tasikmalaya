<?php

namespace Database\Seeders;

use App\Imports\KecamatanGarutTasikImport;
use App\Imports\KecamatansImport;
use App\Imports\TPSSheetImport;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class KecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kecamatan::create([
        //     'kecamatan' => 'BUNGURSARI',
        //     'deleted' => '0:00,0'
        // ]);

        // Kecamatan::create([
        //     'kecamatan' => 'CIHIDEUNG',
        //     'deleted' => '0:00,0'
        // ]);

        // Path ke file Excel
        $excelPath = database_path('seeders/data/lokasi_tps_pilkada_2024.xlsx');
        $excelKecamatanGarutTasikPath = database_path('seeders/data/data_kecamatan_kabgarut_kabtasik.xlsx');

        // Import data dari file Excel
        Excel::import(new KecamatansImport, $excelPath);
        Excel::import(new KecamatanGarutTasikImport, $excelKecamatanGarutTasikPath);
    }
}
