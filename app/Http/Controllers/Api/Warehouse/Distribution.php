<?php

namespace App\Http\Controllers\Api\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Warehouse\Distribution as DistributionModel;
use App\Models\Old\Inventory;
use App\Models\Warehouse\DistributionDevices;

class Distribution extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return $this->success('Lista de proyectos de distribución', [
                'distributions' => DistributionModel::baseQuery()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener la lista de proyectos de distribución',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $duplicated = $this->findDuplicate($request->customer, $request->project);

        if ($duplicated) {
            return response()->json([
                'message' => 'Ya existe un proyecto con el mismo nombre para este cliente'
            ], 409);
        }

        try {
            $record = DistributionModel::create([
                'CreatedById' => $request->user->Id,
                'CustomerId' => $request->customer,
                'Project' => $request->project
            ]);

            return response()->json([
                'message' => 'Proyecto de distribución creado correctamente',
                'distribution' => DistributionModel::baseQuery($record->Id)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el proyecto de distribución',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function findDuplicate($customerId, $projectName, $id = null)
    {
        $record = DistributionModel::where('CustomerId', $customerId)
            ->where('Project', $projectName);
        if (!is_null($id))
            return $record->where('Id', '!=', $id)->first();
        else
            return $record->first();
    }

    public function availableInventory($customerId)
    {
        try {
            $inventory = Inventory::join('cat_v3_modelos_equipo as cme', 'cme.Id', '=', 't_inventario.IdProducto')
                ->select(
                    't_inventario.Id',
                    DB::raw('linea(lineaByModelo(cme.Id)) as Line'),
                    DB::raw('sublinea(sublineaByModelo(cme.Id)) as Subline'),
                    DB::raw('marca(cme.Marca) as Brand'),
                    'cme.Nombre as Model',
                    't_inventario.Serie as Serial'
                )
                ->where('IdCliente', $customerId)
                ->whereIn('IdEstatus', [17, 65])
                ->where('IdTipoProducto', 1)
                ->where('IdAlmacen', 1297)
                ->whereNotIn('t_inventario.Id', function ($query) {
                    $query->select('InventoryId')->from('adl_warehouse_distribution_devices')->where('StatusId', '!=', '67');
                })
                ->get();
            return response()->json([
                'message' => 'Inventario disponible',
                'inventory' => $inventory
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener el inventario disponible',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
