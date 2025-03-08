<?php

namespace App\Imports;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class KelurahansDataImport implements ToModel, WithHeadingRow, WithStartRow
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

        $kelurahan = $row['kelurahan'];
        $dapil = $row['dapil'];
        $namaKecamatan = $row['kecamatan'];

        if ($kelurahan == null) {
            return null;
        }

        $kecamatan = Kecamatan::where('nama', $namaKecamatan)->get();
        
        $existing = Kelurahan::where('nama_kelurahan', $kelurahan)->where('kecamatan_id', '=', $kecamatan[0]->id)
        ->first();

        // Pecah string menjadi array berdasarkan spasi
        $words = explode(' ', $dapil);

        // Ambil elemen terakhir dari array
        $last_word = end($words);
        

        if(!$existing){
            return new Kelurahan([
                'nama_kelurahan' => $kelurahan,
                'kecamatan_id' => $kecamatan[0]->id,
                'dapil' => $last_word,
                'kabkota' => '29',
                'provinsi' => 'JAWA BARAT',
                'deleted' => '0'
            ]);
        }
    }



}
