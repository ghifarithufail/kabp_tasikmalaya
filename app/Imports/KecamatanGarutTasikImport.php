<?php

namespace App\Imports;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class KecamatanGarutTasikImport implements ToModel, WithHeadingRow, WithStartRow
{

    public function startRow(): int{
        return 2;
    }

    public function headingRow(): int
    {
        return 1; // Baris 1 sebagai header
    }

    public function model(array $row){  
        return new Kecamatan([
            'nama' => $row['nama'],
            'kotakab_id' => intval($row['kotakab_id'])
        ]);
    }

}
