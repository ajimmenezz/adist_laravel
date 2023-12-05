<?php

namespace App\Http\Controllers\Api\Logistic\Distribution;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Warehouse\DistributionDevices;
use Illuminate\Support\Facades\DB;

class Destinations extends Controller
{
    public function index()
    {
        try {
            $destinations = DistributionDevices::join('adl_warehouse_distributions as wd', 'wd.Id', '=', 'adl_warehouse_distribution_devices.DistributionId')
                ->select(
                    'wd.Id as DistributionId',
                    'wd.Project',
                    DB::raw('cliente(wd.CustomerId) as Customer'),
                    'wd.CustomerId',
                    DB::raw('sucursal(adl_warehouse_distribution_devices.BranchId) as Branch'),
                    'adl_warehouse_distribution_devices.BranchId',
                    DB::raw('estadoBySucursal(adl_warehouse_distribution_devices.BranchId) as State'),
                    DB::raw('count(*) as Devices'),
                    DB::raw('estatus(adl_warehouse_distribution_devices.StatusId) as Status'),
                    'adl_warehouse_distribution_devices.StatusId',
                    'adl_warehouse_distribution_devices.CurrentTransfer'
                )
                ->whereIn('adl_warehouse_distribution_devices.StatusId', [68, 70])
                ->groupBy('wd.Id', 'adl_warehouse_distribution_devices.BranchId', 'adl_warehouse_distribution_devices.StatusId', 'adl_warehouse_distribution_devices.CurrentTransfer')
                ->get();
            return response()->json([
                'message' => 'Lista de destinos asignados a logística',
                'data' => [
                    'destinations' => $destinations
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener la lista de destinos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
