<?php

namespace Database\Seeders;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

ini_set('memory_limit', '512M');
class KoordinatSeeder extends Seeder
{
    
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('seeders/data/kotatasikmalaya.geojson'));
        $koordinats = json_decode($json, true);

        foreach ($koordinats as $data) {
            $kecamatan = Kecamatan::where('nama', '=' ,$data['sub_district'])->first();
            $kelurahan = Kelurahan::where('nama_kelurahan', $data['village'])->where('kecamatan_id', '=', $kecamatan->id)->where(column: 'kabkota', operator: '=', value: '29')->first();

            if ($kelurahan) {
                $dataKoordinat = json_encode($data['border']);
                
                $kelurahan->update([
                    'koordinat' => $dataKoordinat
                ]);
            }
        }

        $jsonGarut = File::get(database_path('seeders/data/kabupatengarut.geojson'));
        $koordinatGarut = json_decode($jsonGarut, true);

        foreach($koordinatGarut as $data){
            $kecamatan = Kecamatan::where('nama', '=' ,$data['sub_district'])->where('kotakab_id', '=', '18')->first();
            $kelurahan = Kelurahan::where('nama_kelurahan', $data['village'])->where('kecamatan_id', '=', $kecamatan->id)->where('kabkota', '=', '18')->first();

            if ($kelurahan) {
                $dataKoordinat = json_encode($data['border']);
                
                $kelurahan->update([
                    'koordinat' => $dataKoordinat
                ]);
            }
        }

        $jsonKabupatenTasik = File::get(database_path('seeders/data/kabupatentasikmalaya.geojson'));
        $koordinatKabupatenTasik = json_decode($jsonKabupatenTasik, true);

        foreach($koordinatKabupatenTasik as $data){
            $kecamatan = Kecamatan::where('nama', '=' ,$data['sub_district'])->where('kotakab_id', '=', '498')->first();
            if($kecamatan){
                $kelurahan = Kelurahan::where('nama_kelurahan', $data['village'])->where('kecamatan_id', '=', $kecamatan->id)->where('kabkota', '=', '498')->first();

                if ($kelurahan) {
                    $dataKoordinat = json_encode($data['border']);
                    
                    $kelurahan->update([
                        'koordinat' => $dataKoordinat
                    ]);
                }else{
                    echo $kecamatan->nama . "\n";
                }
            }
        }
    }
}
