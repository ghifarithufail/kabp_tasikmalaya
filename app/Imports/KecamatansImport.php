<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class KecamatansImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'TPS' => new KecamatansDataImport(),
        ];
    }
}
