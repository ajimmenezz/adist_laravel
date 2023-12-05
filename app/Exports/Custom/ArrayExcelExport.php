<?php

namespace App\Exports\Custom;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class ArrayExcelExport implements FromArray, WithHeadings, ShouldAutoSize
{

    protected $array;
    protected $headings;

    public function __construct(array $array, $columns)
    {
        $this->array = $array;
        $this->headings = $columns;
    }

    public function array(): array
    {
        return $this->array;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->calculateSheetDimension();
            },
        ];
    }
}
