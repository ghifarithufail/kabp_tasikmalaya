<?php

namespace Database\Seeders;

use App\Models\Kabkota;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class KabkotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kabkota::create([
        //     'nama_kabkota' => 'JAKARTA',
        //     'prov' => 'DKI JAKARTA'
        // ]);

        // Kabkota::create([
        //     'nama_kabkota' => 'KEPULAUAN SERIBU',
        //     'prov' => 'DKI JAKARTA'
        // ]);

        // Path ke file SQL
        $path = database_path('seeders/data/kabkotas.sql');

        // Baca file SQL
        $sql = File::get($path);

        \Log::info('kabkota');

        // Jalankan query SQL
        DB::unprepared($sql);
    }
}
