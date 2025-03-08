<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class TandaTerimaSheet implements FromView, WithTitle
{
    protected $data, $namaKecamatan;

    public function __construct($data, $namaKecamatan)
    {
        $this->data = $data;
        $this->namaKecamatan = $namaKecamatan;
    }

    public function title(): string
    {
        return $this->namaKecamatan; // Nama sheet sesuai kecamatan
    }

    public function view(): View
    {
        // dd($this->data);
        // foreach($this->data['data'] as $item){
        //     // dd($item['korcam']->partais);
        //     foreach($item['korlurs'] as $korlur){
        //         // dd($korlur['korlur']);
        //         foreach($korlur['agents'] as $agent){
        //             // dd($agent);
        //         }
        //     }
        // }
        return view('export-tanda-terima', [
            'korcams' => $this->data,
        ]);
    }
}