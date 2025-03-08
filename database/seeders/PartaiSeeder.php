<?php

namespace Database\Seeders;

use App\Models\Korcam;
use App\Models\Partai;
use App\Models\PivotAgent;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PartaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Partai::create([
        //     'nama' => 'Gerindra',
        //     'foto' => 'BFyzwq9SAOjsLIr1j56KtzyheIEGGsPv2Y4Brg1l.jpg',
        //     'deleted' => '0',
        // ]);

        Partai::create([
            'nama' => 'Karyawan',
            'deleted' => '0',
        ]);
        
        Partai::create([
            'nama' => 'Partai PBB',
            'deleted' => '0',
        ]);

        Partai::create([
            'nama' => 'Jabar',
            'deleted' => '0',
        ]);

        Partai::create([
            'nama' => 'Dewan',
            'deleted' => '0',
        ]);

        Partai::create([
            'nama' => 'Caleg Gerindra',
            'deleted' => '0',
        ]);

        
    }
}
