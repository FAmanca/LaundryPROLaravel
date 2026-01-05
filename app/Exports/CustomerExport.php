<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class CustomerExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
*/
    public function collection()
    {
        return Customer::select('name', 'email', 'phone', 'address')->get();
    }

    public function headings(): array
    {
        return [
            'Nama Pelanggan',
            'Email',
            'No Hp',
            'Alamat',
        ];
    }
}
