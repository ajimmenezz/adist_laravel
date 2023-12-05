<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributionDevicesHistory extends Model
{
    use HasFactory;
    protected $table = 'adl_warehouse_distribution_devices_history';
    protected $fillable = [
        'DistributionDeviceId',
        'StatusId',
        'WarehouseId',
        'TransferId',
        'UserId',
    ];

    protected $primaryKey = 'Id';
}
