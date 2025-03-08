<?php

namespace Database\Seeders;

use App\Imports\DptImport;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $path = database_path('seeders/data/dpt.sql');

        \Log::info('123');
        // Baca file SQL
        $sql = File::get($path);

        // Jalankan query SQL
        DB::unprepared($sql);
    }
}
