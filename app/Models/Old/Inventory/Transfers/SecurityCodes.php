<?php

namespace App\Models\Old\Inventory\Transfers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecurityCodes extends Model
{
    use HasFactory;
    protected $table = 't_inventario_traspasos_codigos';
    protected $primaryKey = 'Id';
    protected $fillable = [
        'IdTraspaso',
        'IdSucursalDestino',
        'Referencia',
        'Codigo',
        'FechaCodigo',
        'IdUsuarioCodigo',
        'FechaIngresoCodigo'
    ];
    public $timestamps = false;

    public static function code()
    {
        return self::randomLetter() . self::randomLetter() . self::randomNumber() . "-" . self::randomNumber() . self::randomLetter() . self::randomNumber();
    }

    private static function randomLetter()
    {
        return chr(rand(65, 90));
    }

    private static function randomNumber()
    {
        return rand(0, 9);
    }
}
