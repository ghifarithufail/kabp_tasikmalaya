<?php

namespace Database\Seeders;

use App\Models\Korcam;
use App\Models\PivotAgent;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PivotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PivotAgent::create([
            'agent_tps_id' => '1',
            'tps_id' => '1',
        ]);
    }
}
