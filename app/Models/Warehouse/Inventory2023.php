<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory2023 extends Model
{
    use HasFactory;
    protected $table = 'inventory2023';
    protected $primaryKey = 'Id';
    protected $fillable = [
        'Id',
        'WarehouseKey',
        'Warehouse',
        'ItemKey',
        'Item',
        'ItemLine',
        'Measure',
        'Quantity',
        'ValidatedQuantity',
        'LastUpdateUser',
    ];

    
}
