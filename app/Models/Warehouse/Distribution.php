<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Distribution extends Model
{
    use HasFactory;
    protected $table = 'adl_warehouse_distributions';
    protected $fillable = [
        'CreatedById',
        'CustomerId',
        'Project'
    ];

    protected $primaryKey = 'Id';

    public static function findDuplicate($customerId, $project)
    {
        $duplicated = self::where('CustomerId', $customerId)
            ->where('Project', $project)
            ->first();
        return $duplicated;
    }

    public static function baseQuery(int $id = null, string $project = null, int $customer = null)
    {

        $base = self::select(
            'adl_warehouse_distributions.Id',
            'adl_warehouse_distributions.Project',
            DB::raw('nombreUsuario(adl_warehouse_distributions.CreatedById) as CreatedBy'),
            DB::raw('cliente(adl_warehouse_distributions.CustomerId) as Customer'),
            'adl_warehouse_distributions.CustomerId',
            'adl_warehouse_distributions.created_at'
        );

        if (!is_null($project)) {
            $base = $base->where('adl_warehouse_distributions.Project', 'like', '%' . $project . '%');
        }

        if (!is_null($customer)) {
            $base = $base->where('adl_warehouse_distributions.CustomerId', $customer);
        }

        if (!is_null($id)) {
            return $base->where('adl_warehouse_distributions.Id', $id)->first();
        } else {
            return $base->orderBy('adl_warehouse_distributions.created_at', 'desc')->get();
        }
    }
}
