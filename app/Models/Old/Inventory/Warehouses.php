<?php

namespace App\Models\Old\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Old\Users;

class Warehouses extends Model
{
    use HasFactory;

    protected $table = 'cat_v3_almacenes_virtuales';
    protected $primaryKey = 'Id';
    protected $fillable = [
        'IdTipoAlmacen',
        'IdReferenciaAlmacen',
        'IdResponsable',
        'Nombre',
        'Flag'
    ];

    public $timestamps = false;

    public static function one($id)
    {
        return DB::table('cat_v3_almacenes_virtuales as cav')
            ->leftJoin('cat_v3_usuarios as cu', function ($join) {
                $join->on('cav.IdReferenciaAlmacen', '=', 'cu.Id')
                    ->on('cav.IdTipoAlmacen', '=', DB::raw(1));
            })
            ->leftJoin('cat_v3_usuarios as cu2', function ($join) {
                $join->on('cav.IdResponsable', '=', 'cu2.Id')
                    ->on('cav.IdTipoAlmacen', '=', DB::raw(4));
            })
            ->select([
                'cav.Id as WarehouseId',
                'cav.IdTipoAlmacen as WarehouseTypeId',
                'cav.Nombre as WarehouseName',
                DB::raw('if(cav.IdTipoAlmacen = 1, cu.Id, if(cav.IdTipoAlmacen = 4, cu2.Id, null)) as ResponsibleId'),
                'cav.Flag as Active',
            ])
            ->where('cav.Id', $id)->first();
    }

    public static function getByUser($userId)
    {
        $record = self::where('IdTipoAlmacen', 1)->where('IdReferenciaAlmacen', $userId)->first();
        if (!$record) {
            $record = self::create([
                'IdTipoAlmacen' => 1,
                'IdReferenciaAlmacen' => $userId,
                'Nombre' => 'Almacén de ' . Users::fullName($userId),
                'Flag' => 1
            ]);
        }

        return self::one($record->Id);
    }
}
