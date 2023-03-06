<?php

namespace App\Models\Censos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TDeviceAccesories extends Model
{
    use HasFactory;

    protected  $table = 't_censos_device_accesories';
    protected $primaryKey = 'Id';
    protected $fillable = [
        'Id',
        'CensoId',
        'AccesoryId',
        'Quantity',
        'Active'
    ];

    public static function getAccesoriesByDevice($id)
    {
        return self::where('CensoId', $id)->where('Active', 1)->get();        
    }
}
