<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TemplateSiswaExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return ['nama', 'nis', 'kelas'];
    }

    public function array(): array
    {
        return [
            ['Contoh Nama', '131232750027123456', 'X'],
        ];
    }
}
