<?php

namespace Database\Seeders;

use App\Imports\UserImport;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Maatwebsite\Excel\Facades\Excel;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {

        $excelPath = database_path('seeders/data/USER_SAKSI.xlsx');

        Excel::import(new UserImport, $excelPath);

    }
}
