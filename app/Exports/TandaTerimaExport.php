<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TandaTerimaExport implements WithMultipleSheets
{
    protected $korcams;

    public function __construct($korcams)
    {
        $this->korcams = $korcams;
    }

    public function sheets(): array
    {
        $sheets = [];
        $i = 0;
        foreach ($this->korcams as $key=>$korcam) {
            // dd( $korcam[$i]['korcam']->kecamatans->nama);
            $sheets[] = new TandaTerimaSheet($korcam, $korcam[$i]['korcam']->kecamatans->nama);
            $i++;
        }

        return $sheets;
    }
 
}
