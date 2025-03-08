<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TpsImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'TPS' => new TpsDataImport(),
        ];
    }
}
