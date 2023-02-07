<?php

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DeviceModels extends Model
{
    use HasFactory;
    protected $table = 'cat_v3_modelos_equipo';
    public $timestamps = false;

    public static function compact($id = null, $flag = null)
    {
        $result = DB::table('cat_v3_modelos_equipo as cme')
            ->join('cat_v3_marcas_equipo as cmae', 'cme.Marca', '=', 'cmae.Id')
            ->join('cat_v3_sublineas_equipo as cse', 'cmae.Sublinea', '=', 'cse.Id')
            ->join('cat_v3_lineas_equipo as cle', 'cse.Linea', '=', 'cle.Id')
            ->whereNotIn('cle.Id', [29, 31, 32, 33, 37, 39, 40, 41, 42, 43])
            ->select(
                'cme.Id',
                DB::raw('modelo(cme.Id) as ModelCompact'),
            );

        if (!is_null($id))
            $result->where('cme.Id', $id);

        if (!is_null($flag))
            $result->where('cme.Flag', $flag);

        $result->orderBy('ModelCompact');

        return $result->get();
    }
}
