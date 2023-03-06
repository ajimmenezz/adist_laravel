<?php

namespace App\Models\Censos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TDeviceFeatures extends Model
{
    use HasFactory;
    protected  $table = 't_censos_device_features';
    protected $primaryKey = 'Id';
    protected $fillable = [
        'Id',
        'CensoId',
        'FeatureId',
        'Value',
        'Active'
    ];

    public static function getFeaturesById($id)
    {
        return self::where('CensoId', $id)->where('Active', 1)->get();
    }
}
