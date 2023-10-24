<?php

namespace App\Http\Controllers\PA\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Catalogs\CVirtualWarehouses;
use App\Models\Old\Branches;
use App\Models\Old\Inventory;
use App\Models\Old\Inventory\Movements;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Fixes extends Controller
{
    public function FixInventoryTroughCensos()
    {
        try {
            $begin = date('Y-m-d H:i:s');

            $inventory = DB::table('t_inventario as ti')
                ->join('cat_v3_almacenes_virtuales as cav', 'ti.IdAlmacen', '=', 'cav.Id')
                ->leftJoin('v_inventario_ultimo_movimiento_serie as tmi', function ($join) {
                    $join->on('ti.IdProducto', '=', 'tmi.IdProducto')
                        ->on('ti.Serie', '=', 'tmi.Serie');
                })
                ->where('ti.IdCliente', 1)
                ->where('ti.IdTipoProducto', 1)
                ->where('ti.Serie', '<>', 'ILEGIBLE')
                ->whereIn('cav.IdTipoAlmacen', [1, 4])
                ->select(
                    'ti.Id',
                    'ti.IdCliente',
                    'ti.IdAlmacen',
                    'ti.IdProducto',
                    'ti.IdEstatus',
                    'ti.Serie',
                    'cav.Id as IdAlmacen',
                    'cav.IdTipoAlmacen',
                    'cav.IdReferenciaAlmacen',
                    DB::raw('(select Nombre from cat_v3_tipos_movimiento_inventario where Id = tmi.IdTipoMovimiento) as TipoMovimiento'),
                    'tmi.IdAlmacen as IdAlmacenMovimiento',
                    'tmi.Fecha'
                )->get();

            $results = [];
            $erros = [];

            foreach ($inventory as $item) {
                try {
                    $censo = DB::table("t_censos as tc")
                        ->join('t_servicios_ticket as tst', 'tc.IdServicio', 'tst.Id')
                        ->where('tc.IdModelo', $item->IdProducto)
                        ->where('tc.Serie', $item->Serie)
                        ->where('tst.IdEstatus', 4)
                        ->select(
                            'tst.Id',
                            'tst.FechaConclusion',
                            'tst.IdSucursal',
                            'tst.Atiende'
                        )->orderBy('tst.FechaConclusion', 'desc')
                        ->first();

                    if (!$censo) continue;

                    if ($item->Fecha < $censo->FechaConclusion) {
                        $item->FechaCenso = $censo->FechaConclusion;

                        $branchWarehouse = DB::table('cat_v3_almacenes_virtuales as cav')
                            ->where('cav.IdTipoAlmacen', 2)
                            ->where('cav.IdReferenciaAlmacen', $censo->IdSucursal)
                            ->select('cav.Id')
                            ->first();

                        if (!$branchWarehouse) {
                            $branch = Branches::find($censo->IdSucursal);

                            $branchWarehouse = CVirtualWarehouses::create([
                                'IdTipoAlmacen' => 2,
                                'IdReferenciaAlmacen' => $censo->IdSucursal,
                                'IdResponsable' => null,
                                'Nombre' => "Inventario de {$branch->Nombre}",
                                'Flag' => 1
                            ]);
                        }

                        DB::transaction(function () use ($item, $censo, $branchWarehouse) {
                            $firstMovement = Movements::create([
                                'IdTipoMovimiento' => 4,
                                'IdServicio' => $censo->Id,
                                'IdAlmacen' => $item->IdAlmacenMovimiento ?? $item->IdAlmacen,
                                'IdTipoProducto' => 1,
                                'IdProducto' => $item->IdProducto,
                                'IdEstatus' => $item->IdEstatus,
                                'IdUsuario' => $censo->Atiende,
                                'Cantidad' => 1,
                                'Serie' => $item->Serie,
                                'Fecha' => $item->FechaCenso,
                                'NoTraspaso' => null,
                                'IdCliente' => 1,
                                'IdInventario' => $item->Id
                            ]);

                            Movements::create([
                                'IdMovimientoEnlazado' => $firstMovement->Id,
                                'IdTipoMovimiento' => 5,
                                'IdServicio' => $censo->Id,
                                'IdAlmacen' => $branchWarehouse->Id,
                                'IdTipoProducto' => 1,
                                'IdProducto' => $item->IdProducto,
                                'IdEstatus' => 17,
                                'IdUsuario' => $censo->Atiende,
                                'Cantidad' => 1,
                                'Serie' => $item->Serie,
                                'Fecha' => $item->FechaCenso,
                                'NoTraspaso' => null,
                                'IdCliente' => 1,
                                'IdInventario' => $item->Id
                            ]);

                            Inventory::where('Id', $item->Id)->update([
                                'IdEstatus' => 17,
                                'IdEstatusAux' => null,
                                'IdAlmacen' => $branchWarehouse->Id
                            ]);
                        });

                        $results[] = $item;
                    }
                } catch (\Exception $e) {
                    $erros[] = [
                        'item' => $item,
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTrace()
                    ];
                }
            }

            return response()->json([
                'message' => '',
                'result' => $results,
                'errors' => $erros,
                'begin' => $begin,
                'end' => date('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'No se ha podido procesar la reparación de inventario',
                'error' => [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTrace()
                ]
            ]);
        }
    }
}
