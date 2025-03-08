<?php

namespace Database\Seeders;

use App\Imports\KorcamImport;
use App\Models\Korcam;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Maatwebsite\Excel\Facades\Excel;

class KorcamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Korcam::create([
        // 'nik' => '3275020701020008',
        // 'nama' => 'Ghifari',
        // 'kabkota_id' => '11',
        // 'alamat' => 'bekasi',
        // 'rt' => '1',
        // 'rw' => '2',
        // 'kecamatan_id' => '1',
        // 'phone' => '082246161931',
        // 'status' => '1',
        // 'keterangan' => 'testing',
        // 'tgl_lahir' => '2002-01-07',
        // 'partai_id' => '1',
        // 'user_id' => '1',
        // 'deleted' => '0',
        // ]);

        $filePath = database_path('seeders/data/KORCAM.xlsx');

        Excel::import(new KorcamImport, $filePath);
    }
}
