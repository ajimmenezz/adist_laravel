<?php

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SublinesByArea extends Model
{
    use HasFactory;
    protected $table = 'cat_v3_sublineas_x_area';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    public static function get(int $businessUnit, int $attentionArea){
        return self::join('cat_v3_sublineas_equipo', 'cat_v3_sublineas_equipo.Id', '=', 'cat_v3_sublineas_x_area.IdSublinea')
            ->join('cat_v3_lineas_equipo', 'cat_v3_lineas_equipo.Id', '=', 'cat_v3_sublineas_equipo.Linea')
            ->where('cat_v3_sublineas_x_area.IdUnidadNegocio', $businessUnit)
            ->where('cat_v3_sublineas_x_area.IdArea', $attentionArea)
            ->where('cat_v3_sublineas_x_area.Cantidad', '>', 0)
            ->where('cat_v3_sublineas_x_area.Flag', 1)
            ->select('cat_v3_sublineas_equipo.Id', 'cat_v3_sublineas_equipo.Nombre as Name', 'cat_v3_lineas_equipo.Nombre as LineName', 'cat_v3_sublineas_x_area.Cantidad as Quantity')
            ->orderBy('cat_v3_sublineas_equipo.Nombre')
            ->get();
    }
}
