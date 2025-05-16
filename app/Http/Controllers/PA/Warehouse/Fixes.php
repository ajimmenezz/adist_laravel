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
    public function FixInventoryTroughCensos(Request $request, $warehouseId)
    {
        try {
            $begin = date('Y-m-d H:i:s');

            $results = [];

            $inventory = DB::table('t_inventario as ti')
                ->join('cat_v3_almacenes_virtuales as cav', 'ti.IdAlmacen', '=', 'cav.Id')
                ->where('ti.IdCliente', 1)
                ->where('ti.IdTipoProducto', 1)
                ->where('ti.Serie', '<>', 'ILEGIBLE')
                ->whereIn('cav.IdTipoAlmacen', [1, 4]);

            if ($warehouseId != 0) {
                $inventory = $inventory->where('ti.IdAlmacen', $warehouseId);
            }
            // ->where('ti.IdAlmacen', 3737)
            $inventory = $inventory->select(
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

            foreach ($inventory as $item) {

                $ultimoMovimiento = DB::table('t_movimientos_inventario as tmi')
                    ->where('tmi.IdCliente', 1)
                    ->where('tmi.IdTipoProducto', 1)
                    ->where('tmi.IdProducto', $item->IdProducto)
                    ->where('tmi.Serie', $item->Serie)
                    ->select(
                        'tmi.IdProducto',
                        DB::raw('modelo(tmi.IdProducto) as Producto'),
                        'tmi.Serie',
                        'tmi.IdAlmacen as IdAlmacenMovimiento',
                        'tmi.Fecha',
                        'tmi.IdTipoMovimiento'
                    )->orderBy('tmi.Fecha', 'desc')
                    ->first();
                if (!$ultimoMovimiento) {
                    continue;
                }


                $censo = DB::table('t_censos as tc')
                    ->join('t_servicios_ticket as tst', 'tc.IdServicio', 'tst.Id')
                    ->join('cat_v3_sucursales as cs', 'tst.IdSucursal', 'cs.Id')
                    ->where('tc.Serie', $item->Serie)
                    ->where('tst.IdEstatus', 4)
                    ->where('tst.FechaConclusion', '>', $ultimoMovimiento->Fecha)
                    ->where(function ($query) {
                        $query->where('cs.IdSubdireccion', 0)
                            ->orWhereNull('cs.IdSubdireccion')
                            ->orWhere('cs.IdSubdireccion', '');
                    })
                    ->where(DB::raw('cs.Nombre'), 'not like', '%PROYECCION%')
                    ->where(DB::raw('cs.Nombre'), 'not like', '%MANTENIMIENTO%')
                    ->select(
                        'tst.Id',
                        'tst.FechaConclusion',
                        'tst.IdSucursal',
                        'tst.Atiende',
                        DB::raw("modelo(tc.IdModelo) as Modelo"),
                        'tc.IdModelo'
                    )->orderBy('tst.FechaConclusion', 'desc')
                    ->first();

                if ($censo) {
                    $branchWarehouse = DB::table('cat_v3_almacenes_virtuales as cav')
                        ->where('cav.IdTipoAlmacen', 2)
                        ->where('cav.IdReferenciaAlmacen', $censo->IdSucursal)
                        ->select('cav.Id')
                        ->first();

                    if (!$branchWarehouse) {
                        $branch = Branches::find($censo->IdSucursal);

                        $branchWarehouse = CVirtualWarehouses::create([
                            'IdTipoAlmacen' => 2,
                            'IdReferenciaAlmacen' => $item->IdSucursal,
                            'IdResponsable' => null,
                            'Nombre' => "Inventario de {$branch->Nombre}",
                            'Flag' => 1
                        ]);
                    }

                    DB::transaction(function () use ($item, $branchWarehouse, $censo, $ultimoMovimiento) {
                        $firstMovement = Movements::create([
                            'IdTipoMovimiento' => 4,
                            'IdServicio' => $censo->Id,
                            'IdAlmacen' => $ultimoMovimiento->IdAlmacenMovimiento ?? $item->IdAlmacen,
                            'IdTipoProducto' => 1,
                            'IdProducto' => $item->IdProducto,
                            'IdEstatus' => $item->IdEstatus,
                            'IdUsuario' => $censo->Atiende,
                            'Cantidad' => 1,
                            'Serie' => $item->Serie,
                            'Fecha' => $censo->FechaConclusion,
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
                            'Fecha' => $censo->FechaConclusion,
                            'NoTraspaso' => null,
                            'IdCliente' => 1,
                            'IdInventario' => $item->Id
                        ]);

                        Inventory::where('Id', $item->Id)->update([
                            'IdEstatus' => 17,
                            'IdEstatusAux' => null,
                            'Bloqueado' => 0,
                            'IdAlmacen' => $branchWarehouse->Id
                        ]);
                    });
                }

                $results[] = [
                    'Id' => $item->Id,
                    'IdProducto' => $item->IdProducto,
                    'Producto' => $ultimoMovimiento->Producto ?? null,
                    'Serie' => $item->Serie,
                    'IdModelo' => $censo->IdModelo ?? null,
                    'Modelo' => $censo->Modelo ?? null,
                    'IdCenso' => $censo->Id ?? null,
                    'FechaCenso' => $censo->FechaConclusion ?? null,
                    'IdSucursal' => $censo->IdSucursal ?? null,
                    'Atiende' => $censo->Atiende ?? null,
                    'IdAlmacenMovimiento' => $ultimoMovimiento->IdAlmacenMovimiento ?? null,
                    'FechaUltimoMovimiento' => $ultimoMovimiento->Fecha ?? null,
                    'TipoMovimiento' => $ultimoMovimiento->IdTipoMovimiento ?? null
                ];
            }

            return response()->json([
                'message' => '',
                'result' => $results,
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
