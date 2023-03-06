<?php

namespace App\Models\Inventory\Catalogs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CInventoryFeatures extends Model
{
    use HasFactory;
    protected  $table = 'c_inventory_features';
    protected $primaryKey = 'Id';
    protected $fillable = [
        'Id',
        'Name',
        'Active'
    ];
}
