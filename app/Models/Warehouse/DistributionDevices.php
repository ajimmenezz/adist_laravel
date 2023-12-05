<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributionDevices extends Model
{
    use HasFactory;
    protected $table = 'adl_warehouse_distribution_devices';
    protected $fillable = [
        'DistributionId',
        'BranchId',
        'InventoryId',
        'AreaId',
        'StatusId',
        'CurrentTransfer',
    ];
    protected $primaryKey = 'Id';
}
