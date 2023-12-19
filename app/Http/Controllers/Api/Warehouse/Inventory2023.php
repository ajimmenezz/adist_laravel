<?php

namespace App\Http\Controllers\Api\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Warehouse\Inventory2023 as Inventory2023Model;
use App\Exports\Custom\ArrayExcelExport;
use Maatwebsite\Excel\Facades\Excel;

class Inventory2023 extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($warehouseId)
    {
        try {
            $localInventory = Inventory2023Model::where('WarehouseKey', $warehouseId)->get();
            if ($localInventory->count() <= 0) {
                $inventory = DB::connection('sqlsrv')->table('dbo.MULT03')
                    ->join('dbo.INVE03', 'dbo.MULT03.CVE_ART', '=', 'dbo.INVE03.CVE_ART')
                    ->join('dbo.ALMACENES03', 'dbo.MULT03.CVE_ALM', '=', 'dbo.ALMACENES03.CVE_ALM')
                    ->leftJoin('dbo.CLIN03', 'dbo.INVE03.LIN_PROD', '=', 'dbo.CLIN03.CVE_LIN')
                    ->where('dbo.MULT03.CVE_ALM', $warehouseId)
                    ->where(('dbo.MULT03.EXIST'), '>', 0)
                    ->select([
                        'dbo.ALMACENES03.CVE_ALM as WarehouseId',
                        'dbo.ALMACENES03.DESCR as Warehouse',
                        'dbo.MULT03.CVE_ART as ItemKey',
                        'dbo.INVE03.DESCR as Item',
                        'dbo.CLIN03.DESC_LIN as ItemLine',
                        'dbo.INVE03.UNI_MED as Measure',
                        'dbo.MULT03.EXIST as Quantity'
                    ])
                    ->orderBy('dbo.MULT03.CVE_ART')
                    ->get();
                foreach ($inventory as $item) {
                    Inventory2023Model::create([
                        'WarehouseKey' => $item->WarehouseId,
                        'Warehouse' => $item->Warehouse,
                        'ItemKey' => $item->ItemKey,
                        'Item' => $item->Item,
                        'ItemLine' => $item->ItemLine ?? '',
                        'Measure' => $item->Measure,
                        'Quantity' => $item->Quantity,
                        'ValidatedQuantity' => 0,
                        'LastUpdateUser' => '1'
                    ]);
                }

                $localInventory = Inventory2023Model::where('WarehouseKey', $warehouseId)->get();
            }



            return response()->json([
                'message' => 'Inventario del almacén seleccionado',
                'data' => $localInventory
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener el inventario del almacén seleccionado',
                'error' => $e->getMessage()
            ], 500);
        }
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
        try {
            Inventory2023Model::where('Id', $id)->update([
                'ValidatedQuantity' => $request->quantity,
                'LastUpdateUser' => $request->user->Id
            ]);

            return response()->json([
                'message' => 'Inventario revisado del almacén seleccionado',
                'data' => Inventory2023Model::where('Id', $id)->first()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el inventario revisado del almacén seleccionado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function export($id)
    {
        try {
            $localInventory = Inventory2023Model::where('WarehouseKey', $id)->get();
            $warehouse = '';

            $columns = [
                'Id',
                'Clave Almacén',
                'Almacén',
                'Clave Item',
                'Item',
                'Linea',
                'Unidad Medida',
                'Inventario',
                'Inventario Validado',
                'Ultima Actualización'
            ];
            $array = [];
            foreach ($localInventory as $item) {
                $array[] = [
                    $item->Id,
                    $item->WarehouseKey,
                    $item->Warehouse,
                    $item->ItemKey,
                    $item->Item,
                    $item->ItemLine,
                    $item->Measure,
                    $item->Quantity,
                    $item->ValidatedQuantity,
                    $item->updated_at
                ];
                $warehouse = $item->Warehouse;
            }

            $export = new ArrayExcelExport($array, $columns);
            // Excel::store($export, 'public/inventory2023.xlsx', 'public');

            return Excel::download($export, $warehouse . '.xlsx');

        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
