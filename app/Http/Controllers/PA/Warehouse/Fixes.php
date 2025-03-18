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
                    'cav.IdReferenciaAlmacen'
                )->get();

            $lastMovements = DB::table('v_inventario_ultimo_movimiento_serie as tmi')
                ->where('tmi.IdCliente', 1)
                ->where('tmi.IdTipoProducto', 1)
                ->select(
                    'tmi.IdProducto',
                    'tmi.Serie',
                    DB::raw('(select Nombre from cat_v3_tipos_movimiento_inventario where Id = tmi.IdTipoMovimiento) as TipoMovimiento'),
                    'tmi.IdAlmacen as IdAlmacenMovimiento',
                    'tmi.Fecha'
                )->get();



            $lastMovements = $lastMovements->keyBy(function ($item) {
                if(is_numeric($item->Serie)){
                    $item->Serie = ltrim($item->Serie, '0');
                }
                return $item->IdProducto . '-' . $item->Serie;
            });

            $inventory = $inventory->map(function ($item) use ($lastMovements) {
                $key = $item->IdProducto . '-' . $item->Serie;
                if (isset($lastMovements[$key])) {
                    $item->TipoMovimiento = $lastMovements[$key]->TipoMovimiento;
                    $item->IdAlmacenMovimiento = $lastMovements[$key]->IdAlmacenMovimiento;
                    $item->Fecha = $lastMovements[$key]->Fecha;
                } else {
                    $item->TipoMovimiento = null;
                    $item->IdAlmacenMovimiento = null;
                    $item->Fecha = null;
                }
                return $item;
            });

            $censos = DB::table('t_censos as tc')
                ->join('t_servicios_ticket as tst', 'tc.IdServicio', 'tst.Id')
                ->where('tst.IdEstatus', 4)
                ->where('tc.Serie', '<>', 'ILEGIBLE')
                ->where('tc.Serie', '<>', '')
                ->whereNotNull('tc.Serie')
                ->where('tst.IdSucursal', '<>', 0)
                ->where('tst.IdSucursal', '<>', null)
                ->where('tst.IdSucursal', '<>', '')
                ->select(
                    DB::raw('MAX(tst.Id) as Id'),
                    DB::raw('MAX(tst.FechaConclusion) as FechaConclusion'),
                    'tst.IdSucursal',
                    'tst.Atiende',
                    'tc.IdModelo as IdProducto',
                    'tc.Serie'
                )->groupBy('tc.IdModelo', 'tc.Serie')
                ->get();

            $censos = $censos->keyBy(function ($item) {
                return $item->IdProducto . '-' . $item->Serie;
            });

            $inventory = $inventory->map(function ($item) use ($censos) {
                $key = $item->IdProducto . '-' . $item->Serie;
                if (isset($censos[$key])) {
                    $item->IdCenso = $censos[$key]->Id;
                    $item->FechaCenso = $censos[$key]->FechaConclusion;
                    $item->IdSucursal = $censos[$key]->IdSucursal;
                    $item->Atiende = $censos[$key]->Atiende;
                } else {
                    $item->IdCenso = null;
                    $item->FechaCenso = null;
                    $item->IdSucursal = null;
                    $item->Atiende = null;
                }
                return $item;
            });

            $results = [];
            $erros = [];

            foreach ($inventory as $item) {
                try {
                    // $censo = DB::table("t_censos as tc")
                    //     ->join('t_servicios_ticket as tst', 'tc.IdServicio', 'tst.Id')
                    //     ->where('tc.IdModelo', $item->IdProducto)
                    //     ->where('tc.Serie', $item->Serie)
                    //     ->where('tst.IdEstatus', 4)
                    //     ->select(
                    //         'tst.Id',
                    //         'tst.FechaConclusion',
                    //         'tst.IdSucursal',
                    //         'tst.Atiende'
                    //     )->orderBy('tst.FechaConclusion', 'desc')
                    //     ->first();

                    // if (!$censo) continue;

                    if (!$item->IdCenso) {
                        continue;
                    }

                    if ($item->Fecha < $item->FechaCenso) {

                        $branchWarehouse = DB::table('cat_v3_almacenes_virtuales as cav')
                            ->where('cav.IdTipoAlmacen', 2)
                            ->where('cav.IdReferenciaAlmacen', $item->IdSucursal)
                            ->select('cav.Id')
                            ->first();

                        if (!$branchWarehouse) {
                            $branch = Branches::find($item->IdSucursal);

                            $branchWarehouse = CVirtualWarehouses::create([
                                'IdTipoAlmacen' => 2,
                                'IdReferenciaAlmacen' => $item->IdSucursal,
                                'IdResponsable' => null,
                                'Nombre' => "Inventario de {$branch->Nombre}",
                                'Flag' => 1
                            ]);
                        }

                        DB::transaction(function () use ($item, $branchWarehouse) {
                            $firstMovement = Movements::create([
                                'IdTipoMovimiento' => 4,
                                'IdServicio' => $item->IdCenso,
                                'IdAlmacen' => $item->IdAlmacenMovimiento ?? $item->IdAlmacen,
                                'IdTipoProducto' => 1,
                                'IdProducto' => $item->IdProducto,
                                'IdEstatus' => $item->IdEstatus,
                                'IdUsuario' => $item->Atiende,
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
                                'IdServicio' => $item->IdCenso,
                                'IdAlmacen' => $branchWarehouse->Id,
                                'IdTipoProducto' => 1,
                                'IdProducto' => $item->IdProducto,
                                'IdEstatus' => 17,
                                'IdUsuario' => $item->Atiende,
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
