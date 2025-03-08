<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calon extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    const STATUS = [
        '0'=>'Non Aktif',
        '1'=>'Aktif', 
    ];

    const KATEGORI = [
        'walkot' => 'Wali Kota', 
        'bupati' => 'Bupati', 
        'gubernur' => 'Gubernur'
    ];

    const DAERAH_PEMILIHAN = [
        'garut' => 'Kabupaten Garut', 
        'kota tasikmalaya' => 'Kota Tasikmalaya', 
        'kabupaten tasikmalaya' => 'Kabupaten Tasikmalaya',
        'jawa barat' => "Provinsi Jawa Barat"
    ];

    public function partais(){
        return $this->belongsTo(Partai::class, 'partai', 'id');
    }
}
