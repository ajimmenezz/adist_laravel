<?php

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Old\Users;
use PhpParser\Node\Expr\Throw_;

class Censos extends Model
{
    use HasFactory;
    protected  $table = 't_censos';
    protected  $primaryKey = 'Id';
    public $timestamps = false;

    public static function getLast($branchId)
    {
        $lastCompletedRecord = DB::table('t_servicios_ticket as tst')
            ->where('tst.IdSucursal', $branchId)
            ->where('tst.IdEstatus', 4)
            ->where('tst.IdTipoServicio', 11)
            ->orderBy('tst.Id', 'desc')
            ->first();

        if ($lastCompletedRecord) {
            return self::baseQuery()
                ->where('t_censos.IdServicio', $lastCompletedRecord->Id)
                ->where('t_censos.Existe', 1)
                ->get();
        } else {
            return [];
        }
    }

    public static function getOne($id)
    {
        return self::baseQuery()
            ->where('t_censos.Id', $id)
            ->first();
    }

    private static function baseQuery()
    {
        return self::join('cat_v3_modelos_equipo as cme', 'cme.Id', '=', 't_censos.IdModelo')
            ->join('cat_v3_marcas_equipo as cma', 'cma.Id', '=', 'cme.Marca')
            ->join('cat_v3_sublineas_equipo as cse', 'cse.Id', '=', 'cma.Sublinea')
            ->join('cat_v3_lineas_equipo as cle', 'cle.Id', '=', 'cse.Linea')
            ->join('cat_v3_areas_atencion as caa', 'caa.Id', '=', 't_censos.IdArea')
            ->select(
                't_censos.*',
                'caa.Nombre as Area',
                't_censos.Punto',
                'cle.Nombre as Linea',
                'cse.Nombre as Sublinea',
                'cma.Nombre as Marca',
                'cme.Nombre as Modelo',
                't_censos.Serie',
                'cle.Id as LineId',
                'cse.Id as SublineId',
                'cma.Id as BrandId',
                'cme.Id as ModelId'
            )->orderBy('cle.Nombre', 'asc')
            ->orderBy('cse.Nombre', 'asc')
            ->orderBy('cma.Nombre', 'asc')
            ->orderBy('cme.Nombre', 'asc')
            ->orderBy('t_censos.Serie', 'asc');
    }

    public static function getPendings($userId)
    {
        return DB::table('t_servicios_ticket as tst')
            ->join('cat_v3_sucursales as cs', 'cs.Id', '=', 'tst.IdSucursal')
            ->whereIn('tst.IdEstatus', [1, 2, 3, 10])
            ->where('tst.IdTipoServicio', 11)
            ->whereNotIn('cs.IdUnidadNegocio', [12, 14])
            ->whereIn('tst.Atiende', Users::subordinatesIds($userId))
            ->select(
                'tst.Id',
                'cs.Nombre as Branch',
                'tst.FechaCreacion as Created_at',
                'tst.IdEstatus as StatusId',
                DB::raw('nombreUsuario(tst.Atiende) as Attendant')
            )->get();
    }

    public static function getService($id)
    {
        return DB::table('t_servicios_ticket as tst')
            ->join('cat_v3_sucursales as cs', 'cs.Id', '=', 'tst.IdSucursal')
            ->where('tst.Id', $id)
            ->select(
                'tst.Id',
                'tst.Ticket',
                DB::raw('folioByServicio(tst.Id) as Folio'),
                'cs.IdCliente as CustomerId',
                'cs.IdUnidadNegocio as BusinessUnitId',
                'cs.Nombre as Branch',
                'tst.FechaCreacion as Created_at',
                'tst.IdEstatus as StatusId',
                DB::raw('nombreUsuario(tst.Atiende) as Attendant')
            )->first();
    }

    public static function getPointsByArea($serviceId, $areaId = null)
    {
        self::repairAreaPoints($serviceId);
        $result = DB::table('t_censos_puntos')
            ->where('IdServicio', $serviceId);

        if (!is_null($areaId))
            $result->where('IdArea', $areaId);

        $result = $result->get();

        $points = [];

        foreach ($result as $point) {
            $points[$point->IdArea] = $point->Puntos;
        }

        return $points;
    }

    public static function getItemsByArea($serviceId, $areaId = null)
    {
        $result = DB::table('t_censos')
            ->where('IdServicio', $serviceId);

        if (!is_null($areaId))
            $result->where('IdArea', $areaId);

        $result = $result->select('IdArea', DB::raw('count(*) as Quantity'))
            ->groupBy('IdArea')->get();

        $items = [];

        if ($result->count() == 0)
            return $items;

        foreach ($result as $item) {
            $items[$item->IdArea] = $item->Quantity;
        }

        return $items;
    }

    public static function repairAreaPoints(int $serviceId)
    {
        $area_points = DB::table('t_censos')
            ->where('IdServicio', $serviceId)
            ->select('IdArea', DB::raw('MAX(Punto) as Punto'))
            ->groupBy('IdArea')
            ->get();

        foreach ($area_points as $area_point) {

            $record = DB::table('t_censos_puntos')
                ->where('IdServicio', $serviceId)
                ->where('IdArea', $area_point->IdArea)
                ->first();
            if (!$record) {
                DB::table('t_censos_puntos')->insert([
                    'IdServicio' => $serviceId,
                    'IdArea' => $area_point->IdArea,
                    'Puntos' => $area_point->Puntos
                ]);
            } else {
                $points = $record->Puntos > $area_point->Punto ? $record->Puntos : $area_point->Punto;
                DB::table('t_censos_puntos')
                    ->where('IdServicio', $serviceId)
                    ->where('IdArea', $area_point->IdArea)
                    ->update(['Puntos' => $points]);
            }
        }
    }

    public static function addPoint($serviceId, $areaId)
    {
        $newPoint = 1;
        $record = DB::table('t_censos_puntos')
            ->where('IdServicio', $serviceId)
            ->where('IdArea', $areaId)
            ->first();
        if (!$record) {
            DB::table('t_censos_puntos')->insert([
                'IdServicio' => $serviceId,
                'IdArea' => $areaId,
                'Puntos' => $newPoint
            ]);
        } else {
            $newPoint = $record->Puntos + 1;
            DB::table('t_censos_puntos')
                ->where('IdServicio', $serviceId)
                ->where('IdArea', $areaId)
                ->update(['Puntos' => $newPoint]);
        }

        return $newPoint;
    }

    public static function getPointDevices($serviceId, $areaId, $point)
    {
        return self::baseQuery()
            ->where('t_censos.IdServicio', $serviceId)
            ->where('t_censos.IdArea', $areaId)
            ->where('t_censos.Punto', $point)
            ->get();
    }

    public static function updateModel($id, $modelId)
    {
        DB::table('t_censos')
            ->where('Id', $id)
            ->update(['IdModelo' => $modelId]);
    }

    public static function updateSerial($id, $serial)
    {
        DB::table('t_censos')
            ->where('Id', $id)
            ->update(['Serie' => $serial]);
    }

    public static function updateStatus($id, $status)
    {
        DB::table('t_censos')
            ->where('Id', $id)
            ->update(['IdEstatus' => $status]);
    }

    public static function updateFeature($id, $featureId, $value)
    {
        $record = DB::table('t_censos_device_features')
            ->where('CensoId', $id)
            ->where('FeatureId', $featureId)
            ->first();
        if ($record) {
            DB::table('t_censos_device_features')
                ->where('Id', $record->Id)
                ->update(['Value' => $value]);
        } else {
            $record = DB::table('t_censos_device_features')
                ->insert([
                    'CensoId' => $id,
                    'FeatureId' => $featureId,
                    'Value' => $value,
                    'Active' => 1
                ]);
        }
    }

    public static function addDevice($serviceId, $areaId, $point, $modelId, $serial, $status)
    {
        $service = ServiciosTicket::where('Id', $serviceId)->first();
        $serial = $serial == '' ? 'ILEGIBLE' : $serial;

        if ($serial != "ILEGIBLE") {
            $record = self::baseQuery()->where('t_censos.IdServicio', $serviceId)
                ->where('t_censos.Serie', $serial)
                ->first();
            if ($record) {
                throw new \Exception("Ya existe un dispositivo {$record->Modelo} con esa serie en el punto {$record->Punto} del área {$record->Area}");
            }
        }

        $id = DB::table('t_censos')->insertGetId([
            'IdServicio' => $serviceId,
            'IdArea' => $areaId,
            'IdModelo' => $modelId,
            'Punto' => $point,
            'Serie' => $serial,
            'Extra' => self::extraField($service->IdSucursal, $areaId, $point),
            'Existe' => 1,
            'Danado' => 0,
            'IdEstatus' => $status
        ]);

        return $id;
    }

    public static function extraField($branchId, $areaId, $point)
    {
        $branch = Branches::where("Id", $branchId)->first();
        $area = AttentionAreas::where("Id", $areaId)->first();
        $point = str_pad($point, 2, '0', STR_PAD_LEFT);

        return $branch->Dominio . $area->ClaveCorta . $point;
    }
}
