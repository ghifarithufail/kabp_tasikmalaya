<?php

namespace App\Imports;

use App\Models\Kecamatan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class KecamatansDataImport implements ToModel, WithHeadingRow, WithStartRow
{
    public function startRow(): int{
        return 12;
    }

    public function headingRow(): int
    {
        return 3; // Baris 1 sebagai header
    }

    public function model(array $row){
        // dd($row);
        $kecamatan = $row['kecamatan'];
        
        if ($kecamatan == null) {
            return null;
        }
        
        $existing = Kecamatan::where('nama', $row['kecamatan'])
        ->first();

        

        if(!$existing){
            return new Kecamatan([
                'nama' => $kecamatan,
                'kotakab_id' => '29'
            ]);
        }
    }



}
