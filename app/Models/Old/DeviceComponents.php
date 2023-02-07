<?php

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceComponents extends Model
{
    use HasFactory;
    protected $table = 'cat_v3_componentes_equipo';
    public $timestamps = false;


    public static function getComponentsByModel($model_id)
    {
        return self::where('IdModelo', $model_id)->where('Flag', 1)->orderBy('Nombre', 'asc')->get();
    }
}
