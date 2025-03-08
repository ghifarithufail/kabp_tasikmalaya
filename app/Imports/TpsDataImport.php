<?php

namespace App\Imports;

use App\Models\Kelurahan;
use App\Models\Tps;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class TpsDataImport implements ToModel, WithHeadingRow, WithStartRow
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

        $tps = $row['no_tps'];
        $namaKelurahan = $row['kelurahan'];
        $dptl = $row['daftar_pemilih_sementara_dps'];
        $dptp = $row[8];
        

        if ($tps == null) {
            return null;
        }

        $lokasi = $row[13] . " " . $row[14];
        $totdpt = $dptl + $dptp;
        
        $kelurahan = Kelurahan::where('nama_kelurahan', $namaKelurahan)->get();

        return new Tps([
            'kelurahan_id' => $kelurahan[0]->id,
            'tps' => $tps,
            'totdpt' => $totdpt,
            'dptl' => $dptl,
            'dptp' => $dptp,
            'lokasi' => $lokasi,
            'deleted' => '0',
            'target' => '45'
        ]);

    }



}
