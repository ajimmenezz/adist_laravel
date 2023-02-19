<?php

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Termwind\Components\Raw;

class Users extends Model
{
    use HasFactory;
    protected $table = 'cat_v3_usuarios';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    public static function fullUser(int $id)
    {
        return self::baseQuery()->where('cu.Id', $id)->first();
    }

    public static function userByApiToken(string $api_token)
    {
        return self::baseQuery()->where('cu.Token', $api_token)->first();
    }

    private static function baseQuery()
    {
        $base = DB::table('cat_v3_usuarios as cu')
            ->join('cat_perfiles as cp', 'cp.Id', '=', 'cu.IdPerfil')
            ->select(
                'cu.Id',
                DB::RAW('nombreUsuario(cu.Id) as User_name'),
                'cp.Nombre as User_profile',
                'cu.Email as Email_1',
                'cu.EmailCorporativo as Email_2',
                'cu.Token'
            );

        return $base;
    }

    public static function subordinatesIds(int $id)
    {
        $ids = [];
        $boss = [$id];
        do {
            $subordinates = DB::table('cat_v3_usuarios as cu')
                ->whereIn('cu.IdJefe', $boss)
                ->whereNotIn('cu.Id', $ids)
                ->select('cu.Id')
                ->get();
            $boss = [];
            if (!empty($subordinates)) {
                foreach ($subordinates as $subordinate) {
                    array_push($ids, $subordinate->Id);
                    array_push($boss, $subordinate->Id);
                }
            }
        } while (!empty($boss));

        return $ids;
    }
}
