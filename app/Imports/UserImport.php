<?php

namespace App\Imports;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;


class UserImport implements ToModel, WithHeadingRow, WithStartRow
{

    public function startRow(): int
    {
        return 2;
    }

    public function headingRow(): int
    {
        return 1; // Baris 1 sebagai header
    }

    public function model(array $row)
    {
        \Log::info($row);

        // Buat user baru
        $user = User::create([
            'name' => $row['name'],
            'username' => $row['username'],
            'partai' => $row['partai'],
            'status' => $row['status'],
            'role' => $row['role'],
            'kelurahan_id' => $row['kelurahan_id'],
            'password' => bcrypt($row['password']),
        ]);

        // Tetapkan peran admin
        $user->assignRole('Saksi');
    }
}
