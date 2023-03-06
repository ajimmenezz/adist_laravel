<?php

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    protected  $table = 'cat_v3_estatus';
    protected $primaryKey = 'Id';
    public $timestamps = false;
    protected $fillable = [
        'Id',
        'Nombre',
        'Descripcion'
    ];

    public static function censo()
    {
        return self::whereIn('Id', [17, 22])->get();
    }
}
