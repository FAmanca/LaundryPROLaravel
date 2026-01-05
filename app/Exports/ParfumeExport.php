<?php

namespace App\Exports;

use App\Models\Parfume;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ParfumeExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Parfume::select('parfume_name', 'description')->get();
    }

    public function headings(): array
    {
        return [
            'Nama Parfum',
            'Deskripsi'
        ];
    }
}
