<?php

namespace App\Models\Logistic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PickupBoxedItems extends Model
{
    use HasFactory;

    protected $table = 'l_pickup_boxed_items';
    protected $primaryKey = 'Id';
    public $timestamps = true;
    protected $fillable = [
        'PickupId',
        'UserId',
        'BoxNumber',
        'CensoId',
        'Quantity',
        'ModelId',
        'ComponentId',
        'SerialNumber',
        'Comments',
    ];

    public static function extraItems($pickup_id, $box = null)
    {
        $result = DB::table('l_pickup_boxed_items as items')
            ->join('cat_v3_modelos_equipo as cme', 'cme.Id', '=', 'items.ModelId')
            ->join('cat_v3_marcas_equipo as cmae', 'cmae.Id', '=', 'cme.Marca')
            ->join('cat_v3_sublineas_equipo as cse', 'cse.Id', '=', 'cmae.Sublinea')
            ->join('cat_v3_lineas_equipo as cle', 'cle.Id', '=', 'cse.Linea')
            ->leftJoin('cat_v3_componentes_equipo as cce', 'cce.Id', '=', 'items.ComponentId')
            ->where('items.PickupId', $pickup_id)
            ->where('items.CensoId', null)
            ->select(
                'items.Id',
                'cle.Nombre as Linea',
                'cse.Nombre as Sublinea',
                'cmae.Nombre as Marca',
                'cme.Nombre as Modelo',
                'cce.Nombre as Componente',
                'items.SerialNumber',
                'items.Quantity',
                'items.BoxNumber',
                'items.ComponentId'
            )
            ->orderBy('Linea', 'asc')
            ->orderBy('Sublinea', 'asc')
            ->orderBy('Marca', 'asc')
            ->orderBy('Modelo', 'asc');

        if (!is_null($box))
            $result->where('items.BoxNumber', $box);

        $result = $result->get();

        return self::divideExtraItems($result);
    }

    private static function divideExtraItems($items)
    {
        $extraItems = [];

        foreach ($items as $item) {
            if (!array_key_exists($item->BoxNumber, $extraItems))
                $extraItems[$item->BoxNumber] = ['d' => [], 'c' => []];

            if ($item->ComponentId == null)
                array_push($extraItems[$item->BoxNumber]['d'], $item);
            else
                array_push($extraItems[$item->BoxNumber]['c'], $item);
        }

        return $extraItems;
    }
}
