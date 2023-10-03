<?php

namespace App\Exports\Inventories;

use App\Models\Old\Censos;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class Branch implements FromQuery, WithMapping, WithHeadings,ShouldAutoSize
{
    use Exportable;

    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }


    public function query()
    {
        return Censos::getInventory($this->id);
    }

    public function map($row): array
    {
        return [
            $row->Area,
            $row->Punto,
            $row->Linea,
            $row->Sublinea,
            $row->Marca,
            $row->Modelo,
            $row->Serie
        ];
    }

    public function headings(): array
    {
        return [
            'Area',
            'Punto',
            'Línea',
            'Sublínea',
            'Marca',
            'Modelo',
            'Serie'
        ];
    }
}
