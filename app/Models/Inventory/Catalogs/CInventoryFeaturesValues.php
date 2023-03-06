<?php

namespace App\Models\Inventory\Catalogs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CInventoryFeaturesValues extends Model
{
    use HasFactory;
    protected  $table = 'c_inventory_features_values';
    protected $primaryKey = 'Id';
    protected $fillable = [
        'Id',
        'FeatureId',
        'Value',
        'Active'
    ];
}
