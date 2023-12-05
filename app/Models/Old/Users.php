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
            ->leftJoin('t_telegram_users as ttu', 'ttu.UserId', '=', 'cu.Id')
            ->select(
                'cu.Id',
                DB::RAW('nombreUsuario(cu.Id) as User_name'),
                'cp.Nombre as User_profile',
                'cu.Email as Email_1',
                'cu.EmailCorporativo as Email_2',
                'cu.Token',
                'ttu.ChatId'
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

    public static function technicians()
    {
        return self::baseQuery()
            ->leftJoin('cat_v3_departamentos_siccob as cd', 'cd.Id', '=', 'cp.IdDepartamento')
            ->leftJoin('cat_v3_areas_siccob as ca', 'ca.Id', '=', 'cd.IdArea')
            ->where('ca.Id', 8)
            ->where('cu.Flag', 1)
            ->addSelect('cd.Nombre as Department_name')
            ->orderBy('User_name', 'asc')
            ->get();
    }

    public static function fullName(int $id)
    {
        try {
            $user = self::select(DB::raw('nombreUsuario(' . $id . ') as FullName'))->first();
            return $user->FullName;
        } catch (\Exception $e) {
            return 'Usuario no encontrado';
        }
    }
}
