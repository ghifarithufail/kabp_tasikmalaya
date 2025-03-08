<?php

namespace App\Imports;

use App\Models\Dpt;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DptImport implements WithMultipleSheets
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data; // Data berisi TPS, Kecamatan, dan Kelurahan
    }

    public function sheets(): array
    {
        $sheets = [];
        foreach ($this->data as $sheetName => $info) {
            $sheets[$sheetName] = new SheetImport($info['tps'], $info['kecamatan'], $info['kelurahan']);
        }
        return $sheets;
    }
}

class SheetImport implements ToModel, WithHeadingRow
{
    protected $tps;
    protected $kecamatan;
    protected $kelurahan;

    public function __construct($tps, $kecamatan, $kelurahan)
    {
        $this->tps = $tps;
        $this->kecamatan = $kecamatan;
        $this->kelurahan = $kelurahan;
    }

    public function headingRow(): int
    {
        return 4;
    }

    public function model(array $row)
    {
        // \Log::info('123');

        \Log::info('Nama: ' . $row['nama']); // Untuk memeriksa nilai kolom 'nama'
        \Log::info('Row data: ', $row);   

        return new Dpt([
            'nama'          => $row['nama'],            // Column B
            'jenis_kelamin' => $row['jenis_kelamin'],   // Column C
            'usia'          => $row['usia'],            // Column D
            'alamat'        => $row['alamat'],          // Column E
            'rt'            => $row['rt'],              // Column F
            'rw'            => $row['rw'],              // Column G
            'tps'           => $this->tps,              // Using dynamic TPS
            'kecamatan'     => $this->kecamatan,        // Using dynamic Kecamatan
            'kelurahan'     => $this->kelurahan,        // Using dynamic Desa/Kelurahan
        ]);
    }
}
