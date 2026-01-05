<?php

namespace App\Imports;

use App\Models\Service;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ServiceImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Service([
            'service_name' => $row['nama_layanan'],
            'description' => $row['deskripsi'] ?? null,
            'price' => $row['harga'],
            'unit' => $row['unit'],
            'icon_name' => $row['icon_name'] ?? null,
        ]);
    }
}
