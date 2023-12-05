<?php

namespace App\Models\Old\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;
    protected $table = 't_inventario';
    protected $fillable = [
        'IdCliente',
        'IdAlmacen',
        'IdTipoProducto',
        'IdProducto',
        'IdEstatus',
        'Cantidad',
        'Serie',
        'IdAltaInicial',
        'IdEquipoDeshuesado',
        'Bloqueado',
        'IdEstatusAux',
        'IdSucursalOrigen',
        'ReferenciaOrigen'
    ];

    protected $primaryKey = 'Id';
    public $timestamps = false;
}
