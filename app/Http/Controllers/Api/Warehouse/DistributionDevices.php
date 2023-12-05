<?php

namespace App\Http\Controllers\Api\Warehouse;

use App\Exports\Custom\ArrayExcelExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Warehouse\Distribution as DistributionModel;
use App\Models\Warehouse\DistributionDevices as DistributionDevicesModel;
use App\Models\Warehouse\DistributionDevicesHistory as DistributionDevicesHistoryModel;
use App\Models\Old\Inventory\Movements as InventoryMovementsModel;
use App\Models\Old\Branches;
use App\Exports\Custom\ExcelExport;
use App\Models\Old\Inventory\Inventory;
use App\Models\Old\Inventory\Transfers\SecurityCodes;
use App\Models\Old\Inventory\Warehouses;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Old\Users;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DistributionDevices extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        try {
            return response()->json([
                'branches' => $this->groupedByBranch($id),
                'devices' => $this->groupedByDevice($id)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener la lista de equipos asignados',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function groupedByBranch($id)
    {
        return $this->baseQuery($id)
            ->select(
                'cat_v3_sucursales.Id as BranchId',
                'cat_v3_sucursales.Nombre as Branch',
                DB::raw('estadoBySucursal(cat_v3_sucursales.Id) as State'),
                DB::raw('count(InventoryId) as Devices'),
                DB::raw('estatus(adl_warehouse_distribution_devices.StatusId) as Status'),
                'adl_warehouse_distribution_devices.StatusId as StatusId',
                DB::raw('nombreUsuario(if(cav.IdTipoAlmacen = 1, cu.Id, if(cav.IdTipoAlmacen = 4, cu2.Id, null))) as ResponsibleName'),
                'cav.Id as WarehouseId',
                'adl_warehouse_distribution_devices.CurrentTransfer'
            )
            ->groupBy('cat_v3_sucursales.Id')
            ->groupBy('adl_warehouse_distribution_devices.StatusId')
            ->groupBy('ResponsibleName')
            ->groupBy('cav.Id')
            ->groupBy('adl_warehouse_distribution_devices.CurrentTransfer')
            ->orderBy('Branch', 'asc')
            ->get();
    }

    private function groupedByDevice($id, $branchId = null, $statusId = null, bool $get = true, $destinationWarehouse = null, $tranferId = null)
    {
        $result = $this->baseQuery($id, $branchId)
            ->select(
                'adl_warehouse_distribution_devices.InventoryId as InventoryId',
                DB::raw('linea(lineaByModelo(inventory.IdProducto)) as Line'),
                DB::raw('sublinea(sublineaByModelo(inventory.IdProducto)) as Subline'),
                DB::raw('marca(cme.Marca) as Brand'),
                DB::raw('cme.Nombre as Model'),
                'inventory.Serie as Serial',
                'cat_v3_sucursales.Nombre as Branch',
                DB::raw('areaAtencion(adl_warehouse_distribution_devices.AreaId) as Area'),
                DB::raw('estadoBySucursal(cat_v3_sucursales.Id) as State'),
                DB::raw('estatus(adl_warehouse_distribution_devices.StatusId) as Status'),
                DB::raw('nombreUsuario(if(cav.IdTipoAlmacen = 1, cu.Id, if(cav.IdTipoAlmacen = 4, cu2.Id, null))) as ResponsibleName'),
                'adl_warehouse_distribution_devices.CurrentTransfer'
            )
            ->groupBy('adl_warehouse_distribution_devices.InventoryId')
            ->orderBy('Model', 'asc');

        if (!is_null($statusId))
            $result->where('adl_warehouse_distribution_devices.StatusId', $statusId);

        if (!is_null($destinationWarehouse))
            $result->where('cav.Id', $destinationWarehouse);

        if (!is_null($tranferId))
            $result->where('adl_warehouse_distribution_devices.CurrentTransfer', $tranferId);

        if ($get)
            return $result->get();

        else
            return $result;
    }

    public function baseQuery($id, $branchId = null)
    {
        $result = DistributionDevicesModel::join('cat_v3_sucursales', 'cat_v3_sucursales.Id', '=', 'adl_warehouse_distribution_devices.BranchId')
            ->join('t_inventario as inventory', 'inventory.Id', '=', 'adl_warehouse_distribution_devices.InventoryId')
            ->join('cat_v3_modelos_equipo as cme', 'cme.Id', '=', 'inventory.IdProducto')
            ->leftJoin('cat_v3_almacenes_virtuales as cav', 'cav.Id', '=', 'inventory.IdAlmacen')
            ->leftJoin('cat_v3_usuarios as cu', function ($join) {
                $join->on('cav.IdReferenciaAlmacen', '=', 'cu.Id')
                    ->on('cav.IdTipoAlmacen', '=', DB::raw(1));
            })
            ->leftJoin('cat_v3_usuarios as cu2', function ($join) {
                $join->on('cav.IdResponsable', '=', 'cu2.Id')
                    ->on('cav.IdTipoAlmacen', '=', DB::raw(4));
            })
            ->where('adl_warehouse_distribution_devices.DistributionId', $id);
        if (!is_null($branchId))
            $result->where('adl_warehouse_distribution_devices.BranchId', $branchId);

        return $result;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        try {
            DB::transaction(function () use ($request, $id) {
                foreach ($request->devices as $device) {
                    $d = DistributionDevicesModel::create([
                        'DistributionId' => $id,
                        'BranchId' => $request->branch,
                        'InventoryId' => $device,
                        'AreaId' => $request->area ?? null,
                        'StatusId' => 66
                    ]);

                    DistributionDevicesHistoryModel::create([
                        'DistributionDeviceId' => $d->Id,
                        'WarehouseId' => env('GENERAL_WAREHOUSE_ID', 1297),
                        'StatusId' => 66,
                        'UserId' => $request->user->Id
                    ]);
                }
            });

            return response()->json([
                'message' => 'Dispositivos asignados correctamente'
            ], 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al asignar los dispositivos', $e);
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
     * @param  int  $branchId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {

            DB::transaction(function () use ($request, $id) {

                DistributionDevicesHistoryModel::whereIn(
                    'DistributionDeviceId',
                    DistributionDevicesModel::where('DistributionId', $id)
                        ->where('BranchId', $request->branchId)
                        ->where('StatusId', $request->statusId)
                        ->pluck('Id')
                )->delete();

                DistributionDevicesModel::where('DistributionId', $id)
                    ->where('BranchId', $request->branchId)
                    ->where('StatusId', $request->statusId)
                    ->delete();
            });

            DB::commit();

            return response()->json([
                'message' => 'El destino se ha eliminado correctamente'
            ], 200);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'message' => 'Error al cancelar el destino.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function toLogistic(Request $request, $id)
    {
        try {
            $destination = $this->destinationInfo($id, $request->branchId);

            $warehouse = Warehouses::one(env('LOGISTIC_WAREHOUSE_ID', 3205));
            if (!$warehouse)
                throw new \Exception("No se encontró el almacén de logística");

            $transfer = $this->transferItems($request, $id, $request->branchId, $destination, env('LOGISTIC_WAREHOUSE_ID', 3205), 68, 66); //To Logistic
            $destination['code'] = $transfer['code'];
            $destination['transfer'] = $transfer['transfer'];

            $file = $this->getDestinationFile($id, $request->branchId, $destination, 68);

            if (!is_null($warehouse->ResponsibleId) && !empty($warehouse->ResponsibleId) && $warehouse->ResponsibleId > 0) {
                $responsible = Users::fullUser($warehouse->ResponsibleId);
                if ($responsible->ChatId > 0)
                    $this->sendToLogisticNotification($responsible, $destination, $file);
            }

            $this->deleteDestinationFile($file);

            return response()->json([
                'message' => 'Notificación enviada a Logística'
            ], 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al enviar la notificación a Logística', $e);
        }
    }

    public function cancelToLogistic(Request $request, $id)
    {
        try {
            $destination = $this->destinationInfo($id, $request->branchId);

            $warehouse = Warehouses::one(env('LOGISTIC_WAREHOUSE_ID', 3205));
            if (!$warehouse)
                throw new \Exception("No se encontró el almacén de logística. Por favor contacte al administrador");

            $transferCancelled = $this->cancelTransferItems($id, $request, env('GENERAL_WAREHOUSE_ID', 1297), 66, 68, $warehouse->WarehouseId);

            $this->sendCancelToLogisticNotification($destination, $transferCancelled['transfer']);

            return response()->json([
                'message' => 'El traspaso se ha cancelado correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al cancelar el traspaso.',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }

    public function toSupport(Request $request, $id)
    {
        try {
            $destination = $this->destinationInfo($id, $request->branchId);

            switch ($request->from) {
                case 'WAREHOUSE':
                    $devicesStatus = 66;
                    break;
                case 'LOGISTIC':
                    $devicesStatus = 70;
                    break;
                default:
                    throw new \Exception("No se ha encontrado información acerca de este destino. Por favor contacte al administrador");
            }

            $warehouse = Warehouses::getByUser($request->technician);
            if (!$warehouse)
                throw new \Exception("No se encontró el almacén del usuario seleccionado");


            $transfer = $this->transferItems($request, $id, $request->branchId, $destination, $warehouse->WarehouseId, 69, $devicesStatus); //To User
            $destination['code'] = $transfer['code'];
            $destination['transfer'] = $transfer['transfer'];

            $file = $this->getDestinationFile($id, $request->branchId, $destination, 69);

            $technician = Users::fullUser($request->technician);
            if ($technician->ChatId > 0)
                $this->sendToSupportNotification($technician, $destination, $file);


            $this->deleteDestinationFile($file);

            return response()->json([
                'message' => 'Notificación enviada a ' . $technician->User_name
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al enviar la notificación al usuario seleccionado',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }

    public function cancelToSupport(Request $request, $id)
    {
        try {
            $destination = $this->destinationInfo($id, $request->branchId);
            $status = 66;

            switch ($request->from) {
                case 'WAREHOUSE':
                    $warehouse = Warehouses::one(env('GENERAL_WAREHOUSE_ID', 1297));
                    $status = 66;
                    break;
                case 'LOGISTIC':
                    $warehouse = Warehouses::one(env('LOGISTIC_WAREHOUSE_ID', 3205));
                    $status = 70;
                    break;
                default:
                    throw new \Exception("No se encontró el almacén de origen");
            }

            if (!$warehouse)
                throw new \Exception("No se encontró el almacén de origen");

            $transferCancelled = $this->cancelTransferItems($id, $request, $warehouse->WarehouseId, $status, 69, $request->WarehouseId); //To Origin

            $this->sendCancelToSupportNotification($destination, $transferCancelled['transfer'], $transferCancelled['destinationWarehouse'], $warehouse->WarehouseId);

            return response()->json([
                'message' => 'El traspaso se ha cancelado correctamente'
            ], 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al cancelar el traspaso.', $e);
        }
    }

    private function transferItems(Request $request, $id, $branchId, $destination, $destinationWarehouse, $status, $devicesStatus)
    {
        try {
            $devices = $this->groupedByDevice($id, $branchId, $devicesStatus, true);
            $nextTransferNumber = DB::table('t_movimientos_inventario')->max('NoTraspaso') + 1;
            $securityCode = SecurityCodes::code();

            DB::beginTransaction();

            foreach ($devices as $device) {

                $d = DistributionDevicesModel::where('InventoryId', $device->InventoryId)
                    ->where('DistributionId', $id)->first();
                $d->StatusId = $status;
                $d->CurrentTransfer = $nextTransferNumber;
                $d->save();

                DistributionDevicesHistoryModel::create([
                    'DistributionDeviceId' => $d->Id,
                    'WarehouseId' => $destinationWarehouse,
                    'StatusId' => $status,
                    'TransferId' => $nextTransferNumber,
                    'UserId' => $request->user->Id
                ]);


                $inventory = Inventory::find($device->InventoryId);
                $originWarehouse = $inventory->IdAlmacen;
                $inventory->IdAlmacen = $destinationWarehouse;
                $inventory->Bloqueado = 1;
                $inventory->IdEstatusAux = $inventory->IdEstatus;
                $inventory->IdEstatus = 55;
                $inventory->save();

                InventoryMovementsModel::create([
                    'IdTipoMovimiento' => 2,
                    'IdAlmacen' => $originWarehouse,
                    'IdTipoProducto' => $inventory->IdTipoProducto,
                    'IdProducto' => $inventory->IdProducto,
                    'IdEstatus' => $inventory->IdEstatusAux,
                    'IdUsuario' => $request->user->Id,
                    'Cantidad' => $inventory->Cantidad,
                    'Serie' => $inventory->Serie,
                    'Fecha' => date('Y-m-d H:i:s'),
                    'NoTraspaso' => $nextTransferNumber,
                    'IdCliente' => $inventory->IdCliente,
                    'IdInventario' => $inventory->Id
                ]);
            }

            SecurityCodes::create([
                'IdTraspaso' => $nextTransferNumber,
                'IdSucursalDestino' => $destination['branch']->Id,
                'Referencia' => $destination['distribution']->Project,
                'Codigo' => $securityCode,
                'FechaCodigo' => date('Y-m-d H:i:s')
            ]);

            DB::commit();

            return [
                'code' => $securityCode,
                'transfer' => $nextTransferNumber
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Error al transferir los equipos, " . $e->getMessage() . " " . $e->getLine() . " " . $e->getFile());
        }
    }

    private function cancelTransferItems($id, $request, $originWarehouse, $status, $devicesStatus, $destinationWarehouse = null)
    {
        try {
            $devices = $this->groupedByDevice($id, $request->branchId, $devicesStatus, true, $destinationWarehouse, $request->transfer ?? null);
            $destinationWarehouse = null;
            $transferId = null;

            DB::beginTransaction();

            foreach ($devices as $device) {
                $d = DistributionDevicesModel::where('InventoryId', $device->InventoryId)
                    ->where('DistributionId', $id)->first();
                $transferId = $d->CurrentTransfer;
                $d->StatusId = $status;
                $d->CurrentTransfer = null;
                $d->save();

                DistributionDevicesHistoryModel::create([
                    'DistributionDeviceId' => $d->Id,
                    'WarehouseId' => $originWarehouse,
                    'StatusId' => $status,
                    'TransferId' => null,
                    'UserId' => $request->user->Id
                ]);

                $inventory = Inventory::find($device->InventoryId);
                $destinationWarehouse = $inventory->IdAlmacen;

                $inventory->IdAlmacen = $originWarehouse;
                $inventory->Bloqueado = 0;
                $inventory->IdEstatus = $inventory->IdEstatusAux;
                $inventory->IdEstatusAux = null;
                $inventory->save();
            }

            SecurityCodes::where('IdTraspaso', $transferId)->delete();
            InventoryMovementsModel::where('NoTraspaso', $transferId)->delete();

            DB::commit();

            return [
                'transfer' => $transferId,
                'destinationWarehouse' => $destinationWarehouse
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Error al cancelar el traspaso, " . $e->getMessage() . " " . $e->getLine() . " " . $e->getFile());
        }
    }

    private function pendingTransfer($transfer)
    {
        return SecurityCodes::where('IdTraspaso', $transfer)->first();
    }

    private function destinationInfo($id, $branchId)
    {
        $distribution = DistributionModel::baseQuery($id);
        $branch = Branches::find($branchId);

        return [
            'distribution' => $distribution,
            'branch' => $branch
        ];
    }

    private function getDestinationFile($id, $branchId, $destination, $devicesStatus)
    {
        $devices = $this->groupedByDevice($id, $branchId, $devicesStatus, false);
        $columns = ['Id', 'Linea', 'Sublinea', 'Marca', 'Modelo', 'Serie', 'Sucursal', 'Area', 'Entidad', 'Estado'];

        $export = new ExcelExport($devices, $columns);

        Excel::store($export, 'exports/distribution/' . $destination['distribution']->Project . ' - ' . $destination['branch']->Nombre . '.xlsx', 'public');

        return storage_path('app/public/exports/distribution/' . $destination['distribution']->Project . ' - ' . $destination['branch']->Nombre . '.xlsx');
    }

    private function deleteDestinationFile($path)
    {
        unlink($path);
    }

    private function sendToLogisticNotification($user, $destination, $file)
    {
        $this->sendTelegramFile(
            $user->ChatId,
            "{$user->User_name}, se ha traspasado producto al almacén de logística para su distribución.\n\n" .
                "Los datos del proyecto son los siguientes:\n" .
                "*Proyecto:* {$destination['distribution']->Project}\n" .
                "*Cliente:* {$destination['distribution']->Customer}\n" .
                "*Sucursal:* {$destination['branch']->Nombre}\n\n" .
                "El archivo adjunto contiene los equipos que se han traspasado al almacén de logística.\n\n" .
                "El código de seguridad para la recepciòn de los equipos es: *{$destination['code']}. Por favor no comparta el código con nadie a menos que sea para verificar el traspaso*\n\n" .
                "El número de traspaso es: *{$destination['transfer']}*",
            $file,
            'Distribución de equipos a sucursal ' . $destination['branch']->Nombre . ' del proyecto ' . $destination['distribution']->Project . '.xlsx'
        );
    }

    private function sendToSupportNotification($user, $destination, $file)
    {
        $this->sendTelegramFile(
            $user->ChatId,
            "{$user->User_name}, se ha traspasado producto a su almacén para distribución e instalación.\n\n" .
                "Los datos del proyecto son los siguientes:\n" .
                "*Proyecto:* {$destination['distribution']->Project}\n" .
                "*Cliente:* {$destination['distribution']->Customer}\n" .
                "*Sucursal:* {$destination['branch']->Nombre}\n\n" .
                "El archivo adjunto contiene los equipos que estan pendientes de su aprobación.\n\n" .
                "El código de seguridad para la recepciòn de los equipos es: *{$destination['code']}. Por favor no comparta el código con nadie a menos que sea para verificar el traspaso*\n\n" .
                "El número de traspaso es: *{$destination['transfer']}*",
            $file,
            'Distribución de equipos a sucursal ' . $destination['branch']->Nombre . ' del proyecto ' . $destination['distribution']->Project . '.xlsx'
        );
    }

    private function sendCancelToLogisticNotification($destination, $transfer)
    {
        try {
            $generalWarehouse = Warehouses::one(env('GENERAL_WAREHOUSE_ID', 1297));
            $logisticWarehouse = Warehouses::one(env('LOGISTIC_WAREHOUSE_ID', 3205));

            if (!is_null($generalWarehouse->ResponsibleId) && !empty($generalWarehouse->ResponsibleId) && $generalWarehouse->ResponsibleId > 0) {
                $responsible = Users::fullUser($generalWarehouse->ResponsibleId);
                if ($responsible->ChatId > 0) {
                    $this->sendTelegramMessage(
                        $responsible->ChatId,
                        "{$responsible->User_name}, se ha cancelado el traspaso *{$transfer}* de productos al almacén de logística para su distribución.\n\n" .
                            "Los datos del proyecto son los siguientes:\n" .
                            "*Proyecto:* {$destination['distribution']->Project}\n" .
                            "*Cliente:* {$destination['distribution']->Customer}\n" .
                            "*Sucursal:* {$destination['branch']->Nombre}\n\n" .
                            "Ahora podrá agregar más productos a su destino o cancelarlo en caso de ser necesario.\n"
                    );
                }
            }

            if (!is_null($logisticWarehouse->ResponsibleId) && !empty($logisticWarehouse->ResponsibleId) && $logisticWarehouse->ResponsibleId > 0) {
                $responsible = Users::fullUser($logisticWarehouse->ResponsibleId);
                if ($responsible->ChatId > 0) {
                    $this->sendTelegramMessage(
                        $responsible->ChatId,
                        "{$responsible->User_name}, se ha cancelado el traspaso *{$transfer}* de productos al almacén de logística para su distribución.\n\n" .
                            "Los datos del proyecto son los siguientes:\n" .
                            "*Proyecto:* {$destination['distribution']->Project}\n" .
                            "*Cliente:* {$destination['distribution']->Customer}\n" .
                            "*Sucursal:* {$destination['branch']->Nombre}\n\n" .
                            "No es necesaria ninguna acción de su parte.\n"
                    );
                }
            }
        } catch (\Exception $e) {
        }
    }

    private function sendCancelToSupportNotification($destination, $transfer, $destinationWarehouse, $originWarehouse)
    {
        try {
            $destinationWarehouse = Warehouses::one($destinationWarehouse);
            $originWarehouse = Warehouses::one($originWarehouse);

            if (!is_null($destinationWarehouse->ResponsibleId) && !empty($destinationWarehouse->ResponsibleId) && $destinationWarehouse->ResponsibleId > 0) {
                $responsible = Users::fullUser($destinationWarehouse->ResponsibleId);
                if ($responsible->ChatId > 0) {
                    $this->sendTelegramMessage(
                        $responsible->ChatId,
                        "{$responsible->User_name}, se ha cancelado el traspaso *{$transfer}* de productos a su almacén.\n\n" .
                            "Los datos del proyecto son los siguientes:\n" .
                            "*Proyecto:* {$destination['distribution']->Project}\n" .
                            "*Cliente:* {$destination['distribution']->Customer}\n" .
                            "*Sucursal:* {$destination['branch']->Nombre}\n\n" .
                            "No es necesaria ninguna acción de su parte.\n"
                    );
                }
            }

            if (!is_null($originWarehouse->ResponsibleId) && !empty($originWarehouse->ResponsibleId) && $originWarehouse->ResponsibleId > 0) {
                $responsible = Users::fullUser($originWarehouse->ResponsibleId);
                if ($responsible->ChatId > 0) {
                    $this->sendTelegramMessage(
                        $responsible->ChatId,
                        "{$responsible->User_name}, se ha cancelado el traspaso *{$transfer}* de productos al almacén del personal de soporte.\n\n" .
                            "Los datos del proyecto son los siguientes:\n" .
                            "*Proyecto:* {$destination['distribution']->Project}\n" .
                            "*Cliente:* {$destination['distribution']->Customer}\n" .
                            "*Sucursal:* {$destination['branch']->Nombre}\n\n" .
                            "Contacte al adminsitrador si cree que esto es un error.\n"
                    );
                }
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
        }
    }

    public function pendingTransferDevices(Request $request)
    {
        try {
            $devices = $this->groupedByDevice($request->DistributionId, $request->BranchId, $request->StatusId, true, null, $request->TransferId);
            return response()->json([
                'message' => 'Dispositivos pendientes de traspaso',
                'inventory' => $devices
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los dispositivos pendientes de traspaso',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function transferCode(Request $request)
    {
        try {
            $distribution = DistributionModel::baseQuery($request->DistributionId);
            $branch = Branches::find($request->BranchId);
            $code = SecurityCodes::code();
            $device = DistributionDevicesModel::where('DistributionId', $request->DistributionId)
                ->where('BranchId', $request->BranchId)
                ->where('InventoryId', $request->Device)
                ->first();
            $pendingTransfer = SecurityCodes::where('IdTraspaso', $device->CurrentTransfer)->first();

            if (!$pendingTransfer)
                throw new \Exception("No se encontró el traspaso pendiente");

            $pendingTransfer->Codigo = $code;
            $pendingTransfer->FechaCodigo = date('Y-m-d H:i:s');
            $pendingTransfer->save();

            $inventory = Inventory::find($request->Device);
            $warehouse = Warehouses::one($inventory->IdAlmacen);
            $user = Users::fullUser($warehouse->ResponsibleId);

            $this->sendTransferCode($user, [
                'project' => $distribution->Project,
                'customer' => $distribution->Customer,
                'branch' => $branch->Nombre,
                'code' => $code,
                'transfer' => $pendingTransfer->IdTraspaso
            ]);

            return response()->json([
                'message' => 'Se ha generado y enviado el código de traspaso al encargado del almacén destino',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al reenviar el código de traspaso',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function sendTransferCode($user, $destination)
    {
        $this->sendTelegramMessage(
            $user->ChatId,
            "{$user->User_name}, se ha generado un nuevo código de traspaso. " .
                "Los datos del proyecto son los siguientes:\n" .
                "*Proyecto:* {$destination['project']}\n" .
                "*Cliente:* {$destination['customer']}\n" .
                "*Sucursal:* {$destination['branch']}\n\n" .
                "El código de seguridad para la recepciòn de los equipos es: *{$destination['code']}. Por favor no comparta el código con nadie a menos que sea para verificar el traspaso*\n\n" .
                "El número de traspaso es: *{$destination['transfer']}*",
        );
    }

    public function acceptDevices(Request $request)
    {
        try {
            $distribution = DistributionModel::baseQuery($request->DistributionId);
            $device = DistributionDevicesModel::where('InventoryId', $request->Devices[0])
                ->where('DistributionId', $request->DistributionId)
                ->where('BranchId', $request->BranchId)
                ->first();

            $pendingTransfer = SecurityCodes::where('IdTraspaso', $device->CurrentTransfer)->first();

            if (!$pendingTransfer)
                throw new \Exception("No se encontró el traspaso pendiente");

            if ($pendingTransfer->Codigo != $request->TransferCode)
                throw new \Exception("El código de traspaso no coincide. Por favor verifique el código o solicite uno nuevo");

            $branch = Branches::find($request->BranchId);
            $allDevices = $this->groupedByDevice($request->DistributionId, $request->BranchId, $request->StatusId, true, null, $pendingTransfer->IdTraspaso);

            $file = $this->getDestinationWithStatusFile($distribution->Project, $branch->Nombre, $allDevices, $request->Devices);

            $warehouse = 0;
            $warehouseRejected = 0;

            DB::beginTransaction();

            $complete = true;

            foreach ($allDevices as $device) {
                $d = DistributionDevicesModel::where('InventoryId', $device->InventoryId)
                    ->where('DistributionId', $request->DistributionId)
                    ->where('BranchId', $request->BranchId)
                    ->first();

                if (in_array($device->InventoryId, $request->Devices)) {
                    $d->StatusId = $request->ProcessStatus;
                    $d->save();

                    $inventory = Inventory::find($device->InventoryId);
                    $inventory->Bloqueado = 0;
                    $inventory->IdEstatus = $inventory->IdEstatusAux;
                    $inventory->IdEstatusAux = null;
                    $inventory->save();

                    DistributionDevicesHistoryModel::create([
                        'DistributionDeviceId' => $d->Id,
                        'WarehouseId' => $inventory->IdAlmacen,
                        'StatusId' => $request->ProcessStatus,
                        'UserId' => $request->user->Id
                    ]);

                    InventoryMovementsModel::create([
                        'IdTipoMovimiento' => 3,
                        'IdAlmacen' => $inventory->IdAlmacen,
                        'IdTipoProducto' => $inventory->IdTipoProducto,
                        'IdProducto' => $inventory->IdProducto,
                        'IdEstatus' => $inventory->IdEstatusAux,
                        'IdUsuario' => $request->user->Id,
                        'Cantidad' => $inventory->Cantidad,
                        'Serie' => $inventory->Serie,
                        'Fecha' => date('Y-m-d H:i:s'),
                        'NoTraspaso' => $pendingTransfer->IdTraspaso,
                        'IdCliente' => $inventory->IdCliente,
                        'IdInventario' => $inventory->Id
                    ]);

                    $warehouse = $inventory->IdAlmacen;
                } else {
                    $complete = false;
                    $history = DistributionDevicesHistoryModel::whereNotIn('StatusId', [68, 69, 72])
                        ->where('DistributionDeviceId', $d->Id)
                        ->orderBy('Id', 'desc')
                        ->first();

                    $historyTransfers = DistributionDevicesHistoryModel::whereIn('StatusId', [68, 69, 72])
                        ->where('DistributionDeviceId', $d->Id)
                        ->orderBy('Id', 'desc')
                        ->first();

                    if ($history) {
                        $d->StatusId = $history->StatusId;
                        $d->CurrentTransfer = null;
                        $d->save();
                    }

                    DistributionDevicesHistoryModel::create([
                        'DistributionDeviceId' => $d->Id,
                        'WarehouseId' => $historyTransfers->WarehouseId,
                        'StatusId' => 72,
                        'UserId' => $request->user->Id
                    ]);

                    DistributionDevicesHistoryModel::create([
                        'DistributionDeviceId' => $d->Id,
                        'WarehouseId' => $history->WarehouseId,
                        'StatusId' => $history->StatusId,
                        'UserId' => $request->user->Id
                    ]);

                    $movement = InventoryMovementsModel::where('IdInventario', $d->InventoryId)
                        ->where('NoTraspaso', $historyTransfers->TransferId)
                        ->where('IdTipoMovimiento', 2)
                        ->first();

                    $inventory = Inventory::find($d->InventoryId);
                    $inventory->Bloqueado = 0;
                    $inventory->IdEstatus = $inventory->IdEstatusAux;
                    $inventory->IdEstatusAux = null;
                    $inventory->IdAlmacen = $movement->IdAlmacen;
                    $inventory->save();

                    $warehouseRejected = $movement->IdAlmacen;

                    InventoryMovementsModel::where('Id', $movement->Id)->delete();
                }
            }

            // DB::commit();
            DB::rollBack();

            $this->sendReceivedDevicesNotification($warehouse, [
                'project' => $distribution->Project,
                'customer' => $distribution->Customer,
                'branch' => $branch->Nombre,
                'transfer' => $pendingTransfer->IdTraspaso,
            ], $file, $warehouseRejected);

            return response()->json([
                'message' => 'Los dispositivos seleccionados se han aceptado correctamente.'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Error al aceptar los dispositivos', $e);
        }
    }

    private function getDestinationWithStatusFile($project, $branch, $devices, $acceptedDevices)
    {
        $columns = ['Id', 'Linea', 'Sublinea', 'Marca', 'Modelo', 'Serie', 'Sucursal', 'Area', 'Entidad', 'Estado'];
        $array = [];
        foreach ($devices as $device) {
            $device->Estado = in_array($device->InventoryId, $acceptedDevices) ? 'Aceptado' : 'Rechazado';
            $array[] = [
                $device->InventoryId,
                $device->Line,
                $device->Subline,
                $device->Brand,
                $device->Model,
                $device->Serial,
                $device->Branch,
                $device->Area,
                $device->State,
                $device->Estado
            ];
        }

        $export = new ArrayExcelExport($array, $columns);

        Excel::store($export, 'exports/distribution/transfers/' . $project . ' - ' . $branch . '.xlsx', 'public');

        return storage_path('app/public/exports/distribution/transfers/' . $project . ' - ' . $branch . '.xlsx');
    }

    private function sendReceivedDevicesNotification($warehouseId, $data, $file, $warehouseRejected = 0)
    {
        $warehouse = Warehouses::one($warehouseId);
        $user = Users::fullUser($warehouse->ResponsibleId);

        $header1 = "{$user->User_name}, se ha recibido producto del traspaso *{$data['transfer']}* en su almacén para distribución e instalación.\n\n";

        $message =
            "Los datos del proyecto son los siguientes:\n" .
            "*Proyecto:* {$data['project']}\n" .
            "*Cliente:* {$data['customer']}\n" .
            "*Sucursal:* {$data['branch']}\n\n" .
            "El archivo adjunto contiene los detalles de los equipos que se han recibido en su almacén.\n\n";

        $message1 = $header1 . $message;

        $fileName = 'Traspaso_' . $data['transfer'] . '_recibido.xlsx';

        if ($warehouseRejected > 0) {
            $warehouseRejected = Warehouses::one($warehouseRejected);
            $userRejected = Users::fullUser($warehouseRejected->ResponsibleId);
            $header2 = "{$userRejected->User_name}, se ha recibido parcialmente el traspaso *{$data['transfer']}* en el almacén *{$warehouse->WarehouseName}*.\n\n";

            $message2 = $header2 . $message .
                "Los equipos rechazados han regresado al almacén {$warehouseRejected->WarehouseName}";
            
            $message1 .= "Los equipos rechazados han regresado al almacén {$warehouseRejected->WarehouseName}";

            $fileName = 'Traspaso_' . $data['transfer'] . '_recibido_parcialmente.xlsx';

            $this->sendTelegramFile(
                $userRejected->ChatId,
                $message2,
                $file,
                $fileName
            );
        }

        $this->sendTelegramFile(
            $user->ChatId,
            $message1,
            $file,
            $fileName
        );
    }
}
