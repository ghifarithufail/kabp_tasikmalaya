<?php

namespace App\Imports;

use App\Models\Korcam;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class KorcamImport implements ToModel, WithHeadingRow, WithStartRow
{
    public function startRow(): int{
        return 4;
    }
    public function headingRow(): int
    {
        return 3;
    }

    public function model(array $row)
    {
        $kecamatanId = DB::table('kecamatans')->select('id')->where('nama', 'LIKE', '%' . $row[3] . '%')->first();
        $tanggal_lahir = Date::excelToDateTimeObject($row['lahir'])->format('d/m/Y');
        $dapil = explode(" ", $row[1]);

        // dd($row);
        if($row[10] != null){
            Korcam::create([
            'nik' => (int)$row[9],
            'nama' => $row[10],
            'kabkota_id' => '11',
            'alamat' => $row[14],
            'rt' => strval($row[15]),
            'rw' => strval($row[16]),
            'kecamatan_id' => strval($kecamatanId->id),
            'phone' => "0$row[13]",
            'status' => '1',
            'keterangan' => $row[12],
            'tgl_lahir' => $tanggal_lahir,
            'partai_id' => '1',
            'kordapil' => $dapil[1],
            'user_id' => '1',
            'deleted' => '0',
            ]);
        }
    }
}
