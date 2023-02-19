<?php

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Old\Users;

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
                't_censos.Serie'
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
}
