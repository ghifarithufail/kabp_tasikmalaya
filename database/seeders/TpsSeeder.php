<?php

namespace Database\Seeders;

use App\Imports\TpsImport;
use App\Models\Tps;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class TpsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tps::create([
        //     'kelurahan_id' => '1',
        //     'tps' => '001',
        //     'totdpt' => '267',
        //     'dptl' => '134',
        //     'dptp' => '133',
        //     'lokasi' => 'RT : 1, 2, 3, 4, 6, 7 / RW : 1 , RT : 8 / RW : 8 SINDANGSARI',
        //     'deleted' => '0',
        //     'target' => '45',
        // ]);        

        // Tps::create([
        //     'kelurahan_id' => '1',
        //     'tps' => '002',
        //     'totdpt' => '244',
        //     'dptl' => '125',
        //     'dptp' => '118',
        //     'lokasi' => 'RT : 2, 3 / RW : 1 SINDANGSARI',
        //     'deleted' => '0',
        //     'target' => '45',
        // ]);

        // Path ke file Excel
        $excelPath = database_path('seeders/data/lokasi_tps_pilkada_2024.xlsx');

        // Import data dari file Excel
        Excel::import(new TpsImport, $excelPath);
        
    }
}
