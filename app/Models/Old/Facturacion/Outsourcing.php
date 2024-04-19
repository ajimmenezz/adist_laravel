<?php

namespace App\Models\Old\Facturacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Outsourcing extends Model
{
    use HasFactory;

    protected $table = 't_facturacion_outsourcing_documentacion';

    public static function getWeekPendingInvoices($dates, $all = false)
    {
        $query = DB::table('t_facturacion_outsourcing_documentacion as tfod')
            ->join('t_facturacion_outsourcing as tfo', 'tfod.IdVuelta', '=', 'tfo.Id')
            ->join('t_servicios_ticket as tst', 'tfo.IdServicio', '=', 'tst.Id')
            ->select(
                'tfo.Folio as FolioSD',
                'tst.Ticket',
                'tst.Id as Servicio',
                DB::raw('nombreUsuario(tfod.IdUsuario) as Tecnico'),
                DB::raW('estatus(tst.IdEstatus) as EstatusServicio'),
                DB::raW('sucursal(tst.IdSucursal) as Sucursal'),
                DB::raW('tipoServicio(tst.IdTipoServicio) as TipoServicio'),

                'tfo.Vuelta',
                'tfo.Fecha as FechaVuelta',
                DB::raW('estatus(tfo.Idestatus) as EstatusVuelta'),
                'tfo.Monto',
                'tfo.Viatico',
                DB::raw('nombreUsuario(tfo.IdSupervisor) as Autorizado'),
                'tfod.MontoFactura',
                'tfod.Serie as SerieFactura',
                'tfod.Folio as FolioFactura',
                'tfod.XML',
                'tfod.PDF'

            )
            ->where('tfod.Fecha', '>=', $dates['begin'])
            ->where('tfod.Fecha', '<=', $dates['end'])
            ->orderBy('tfo.Folio', 'asc')
            ->orderBy('tfo.Vuelta', 'asc');

        if (!$all) {
            $query->where('tfo.IdEstatus', 14);
        }

        return $query->get();
    }
}
