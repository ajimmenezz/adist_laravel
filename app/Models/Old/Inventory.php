<?php

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;
    protected $table = 't_inventario';
    protected $primaryKey = 'Id';
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
    public $timestamps = false;
}
