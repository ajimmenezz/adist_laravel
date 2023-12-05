<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Old\AttentionAreas;
use App\Models\Old\Branches;
use App\Models\Old\Customers;
use App\Models\Old\Users;
use App\Models\Warehouse\Distribution as DistributionModel;

class Distribution extends Controller
{
    public function index()
    {
        $table_headers = [
            ['label' => __('Proyecto'), 'classes' => ['all align-middle']],
            ['label' => __('Cliente'), 'classes' => ['desktop align-middle']],
            ['label' => __('Creado por'), 'classes' => ['desktop align-middle']],
            ['label' => __('Fecha de creación'), 'classes' => ['desktop align-middle']]
        ];

        return view('warehouse.distribution.index', [
            'title_content' => $this->title_content('warehouse.distribution.index'),
            'table_headers' => $table_headers,
            'customers' => Customers::where('Flag', 1)->orderBy('Nombre', 'asc')->get(),
            'forms' => [
                'new_distribution' => view('warehouse.distribution.new_distribution_form', [
                    'customers' => Customers::where('Flag', 1)->orderBy('Nombre', 'asc')->get()
                ])
            ]
        ]);
    }

    public function one($id)
    {
        $distribution = DistributionModel::baseQuery($id);

        $title_content = $this->title_content('warehouse.distribution.one', [
            'project' => $distribution->Project,
            'customer' => $distribution->Customer
        ]);

        return view('warehouse.distribution.one', [
            'title_content' => $title_content,
            'distribution' => $distribution,
            'table_headers_branch_view' => $this->tableHeadersBranchView(),
            'table_headers_device_view' => $this->tableHeadersDeviceView(),
            'table_headers_inventory' => $this->tableHeadersInventory(),
            'table_headers_assigned_devices' => $this->tableHeadersAssignedDevices(),
            'forms' => [
                'new_destination' => view('warehouse.distribution.new_destination_form', [
                    'branches' => Branches::where('Flag', 1)->where('IdCliente', $distribution->CustomerId)->whereNotIn('IdUnidadNegocio', [12, 14])->orderBy('Nombre', 'asc')->get(),
                    'areas' => AttentionAreas::get(null, $distribution->CustomerId, null, 1),
                ]),
                'assign_to_support' => view('warehouse.distribution.assign_to_support_form', [
                    'users' => Users::technicians()
                ]),
            ]
        ]);
    }

    private function tableHeadersInventory()
    {
        return [
            ['label' => __('Línea'), 'classes' => ['all align-middle']],
            ['label' => __('Modelo'), 'classes' => ['all align-middle']],
            ['label' => __('Serie'), 'classes' => ['all align-middle']],
            ['label' => __('Asignar'), 'classes' => ['all align-middle']]
        ];
    }

    private function tableHeadersAssignedDevices()
    {
        return [
            ['label' => __('Desasignar'), 'classes' => ['all align-middle']],
            ['label' => __('Línea'), 'classes' => ['all align-middle']],
            ['label' => __('Modelo'), 'classes' => ['all align-middle']],
            ['label' => __('Serie'), 'classes' => ['all align-middle']],
        ];
    }

    private function tableHeadersBranchView()
    {
        return [
            ['label' => __(''), 'classes' => ['all align-middle']],
            ['label' => __('Sucursal'), 'classes' => ['all align-middle']],
            ['label' => __('Entidad'), 'classes' => ['desktop align-middle']],
            ['label' => __('# Equipos'), 'classes' => ['all align-middle']],
            ['label' => __('Estado'), 'classes' => ['all align-middle']],
            ['label' => __('# Traspaso'), 'classes' => ['desktop align-middle']],
        ];
    }

    private function tableHeadersDeviceView()
    {
        return [
            ['label' => __('Línea'), 'classes' => ['desktop align-middle']],
            ['label' => __('Modelo'), 'classes' => ['all align-middle']],
            ['label' => __('Serie'), 'classes' => ['all align-middle']],
            ['label' => __('Sucursal'), 'classes' => ['all align-middle']],
            ['label' => __('Área'), 'classes' => ['desktop align-middle']],
            ['label' => __('Entidad'), 'classes' => ['desktop align-middle']],
            ['label' => __('Estado'), 'classes' => ['desktop align-middle']],
            ['label' => __('# Traspaso'), 'classes' => ['desktop align-middle']],

        ];
    }
}
