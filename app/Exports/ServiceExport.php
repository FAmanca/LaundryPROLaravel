<?php

namespace App\Exports;

use App\Models\Service;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ServiceExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Service::select('service_name', 'unit', 'price', 'description')->get();
    }

    public function headings(): array
    {
        return [
            'Nama Layanan',
            'Unit',
            'Harga',
            'Deskripsi',
        ];
    }
}
