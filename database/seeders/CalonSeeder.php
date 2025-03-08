<?php

namespace Database\Seeders;

use App\Models\Calon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CalonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Calon::create([
            'name' => "Hj.Nurhayati & H.Muslim",
            'gambar' => "img/paslon-1.jpeg",
            'kategori' => 'walkot',
            'daerah_pemilihan' => 'kota tasikmalaya',
            'status' => 1
        ]);

        Calon::create([
            'name' => "Ivan Dicksan & Dede Muharam",
            'gambar' => "img/paslon-2.jpeg",
            'kategori' => 'walkot',
            'daerah_pemilihan' => 'kota tasikmalaya',
            'status' => 1
        ]);

        Calon::create([
            'name' => "Muhammad Yusuf & Hendro Nugraha",
            'gambar' => "img/paslon-3.jpeg",
            'kategori' => 'walkot',
            'daerah_pemilihan' => 'kota tasikmalaya',
            'status' => 1
        ]);

        
        Calon::create([
            'name' => "Viman Alfarizi & Diky Chandra",
            'gambar' => "img/paslon-4.jpeg",
            'kategori' => 'walkot',
            'daerah_pemilihan' => 'kota tasikmalaya',
            'status' => 1
        ]);

        Calon::create([
            'name' => "Yanto Aprianto & Aminudin Bustomi",
            'gambar' => "img/paslon-5.jpeg",
            'kategori' => 'walkot',
            'daerah_pemilihan' => 'kota tasikmalaya',
            'status' => 1
        ]);
    }
}
