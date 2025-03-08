<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class KelurahansImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'TPS' => new KelurahansDataImport(),
        ];
    }
}
