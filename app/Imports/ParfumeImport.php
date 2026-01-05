<?php

namespace App\Imports;

use App\Models\Parfume;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ParfumeImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Parfume([
            'parfume_name' => $row['nama_parfum'],
            'description' => $row['deskripsi'] ?? null,
        ]);
    }
}
