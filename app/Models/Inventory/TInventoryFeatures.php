<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TInventoryFeatures extends Model
{
    use HasFactory;
    protected  $table = 't_inventory_features';
    protected $primaryKey = 'Id';
    protected $fillable = [
        'Id',
        'InventoryId',
        'FeatureId',
        'Value',
        'Active'
    ];
}
