<?php

namespace App\Http\Controllers\Logistic;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class Distribution extends Controller
{
    public function index()
    {
        return view('logistic.distribution.index', [
            'title_content' => $this->title_content('logistic.distribution.index'),
            'table_headers_destination_view' => $this->tableHeadersDestinationView(),
            'table_headers_assigned_inventory' => $this->tableHeadersAssignedDevices(),
            'table_headers_accepted_devices' => $this->tableHeadersAcceptedDevices(),
            'forms' => [
                'accept_code' => view('warehouse.distribution.accept_code'),
                'accept_transfer' => view('logistic.distribution.forms.accept_transfer'),
            ]
        ]);
    }

    private function tableHeadersDestinationView()
    {
        return [
            ['label' => __(''), 'classes' => ['all align-middle']],
            ['label' => __('Proyecto'), 'classes' => ['all align-middle']],
            ['label' => __('Cliente'), 'classes' => ['desktop align-middle']],
            ['label' => __('Sucursal'), 'classes' => ['all align-middle']],
            ['label' => __('Entidad'), 'classes' => ['desktop align-middle']],
            ['label' => __('# Equipos'), 'classes' => ['desktop align-middle']],
            ['label' => __('Estado'), 'classes' => ['all align-middle']],
            ['label' => __('# Transferencia'), 'classes' => ['desktop align-middle']]
        ];
    }

    private function tableHeadersAssignedDevices()
    {
        return [
            ['label' => __('Línea'), 'classes' => ['all align-middle']],
            ['label' => __('Modelo'), 'classes' => ['all align-middle']],
            ['label' => __('Serie'), 'classes' => ['all align-middle']],
            ['label' => __('Aceptar'), 'classes' => ['all align-middle']]
        ];
    }

    private function tableHeadersAcceptedDevices()
    {
        return [
            ['label' => __('Regresar'), 'classes' => ['all align-middle']],
            ['label' => __('Línea'), 'classes' => ['all align-middle']],
            ['label' => __('Modelo'), 'classes' => ['all align-middle']],
            ['label' => __('Serie'), 'classes' => ['all align-middle']],
        ];
    }
}
