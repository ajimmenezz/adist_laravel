<?php

namespace App\Models\Old\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movements extends Model
{
    use HasFactory;
    protected $table = 't_movimientos_inventario';
    protected $primaryKey = 'Id';
    protected $fillable = [
        'IdMovimientoEnlazado',
        'IdTipoMovimiento',
        'IdServicio',
        'IdAlmacen',
        'IdTipoProducto',
        'IdProducto',
        'IdEstatus',
        'IdUsuario',
        'Cantidad',
        'Serie',
        'Fecha',
        'NoTraspaso',
        'IdCliente',
        'IdInventario'
    ];
    public $timestamps = false;
}
