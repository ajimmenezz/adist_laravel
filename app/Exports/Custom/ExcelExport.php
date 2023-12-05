<?php

namespace App\Exports\Custom;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExcelExport implements FromQuery, WithHeadings, ShouldAutoSize
{
    protected $query;
    protected $columns;

    public function __construct($query, $columns)
    {
        $this->query = $query;
        $this->columns = $columns;
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return $this->columns;
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
