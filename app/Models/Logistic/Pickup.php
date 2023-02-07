<?php

namespace App\Models\Logistic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pickup extends Model
{
    use HasFactory;
    protected $table = 'l_pickups';
    protected $primaryKey = 'Id';
    protected $fillable = [
        'BranchId',
        'UserId',
        'StatusId',
    ];
    public $timestamps = true;

    public static function getPickups(int $id = null)
    {
        $base = DB::table('l_pickups as p')
            ->join('cat_v3_sucursales as s', 's.Id', '=', 'p.BranchId')
            ->join('cat_v3_usuarios as u', 'u.Id', '=', 'p.UserId')
            ->join('cat_v3_estatus as e', 'e.Id', '=', 'p.StatusId');
        if ($id) {
            $base = $base->where('p.Id', $id);
        }

        return $base->select(
            'p.Id',
            'p.BranchId',
            's.Nombre as BranchName',
            'p.UserId',
            DB::RAW('nombreUsuario(p.UserId) as UserName'),
            'p.StatusId',
            'e.Nombre as StatusName',
            'p.created_at',
            'p.updated_at'
        )->get();
    }
}
