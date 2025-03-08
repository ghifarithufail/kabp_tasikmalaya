<?php

namespace App\Imports;

use App\Models\Kelurahan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class KelurahanGarutTasikImport implements ToModel, WithHeadingRow, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Kelurahan([
            'nama_kelurahan' => $row['nama_kelurahan'], // Assuming the second column is 'nama_kelurahan'
            'kecamatan_id' => strval($row['kecamatan_id']), // And so on, based on your Excel structure
            'dapil' => strval($row['dapil']),
            'kabkota' => strval($row['kabkota']),
            'provinsi' => 'JAWA BARAT',
            'deleted' => '0',
        ]);
    }

    public function startRow(): int{
        return 2;
    }

    public function headingRow(): int
    {
        return 1; // Baris 1 sebagai header
    }

}
