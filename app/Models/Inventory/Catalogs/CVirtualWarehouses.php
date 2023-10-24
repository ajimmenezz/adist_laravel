<?php

namespace App\Models\Inventory\Catalogs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CVirtualWarehouses extends Model
{
    use HasFactory;

    protected $table = 'cat_v3_almacenes_virtuales';
    protected $primaryKey = 'Id';
    protected $fillable = [
        'Id',
        'IdTipoAlmacen',
        'IdReferenciaAlmacen',
        'IdResponsable',
        'Nombre',
        'Flag'
    ];
    public $timestamps = false;
}
