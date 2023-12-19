<?php

namespace App\Models\SAE8;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouses extends Model
{
    use HasFactory;
    protected $connection = 'sqlsrv';
    protected $table = 'dbo.ALMACENES03';
    protected $primaryKey = 'CVE_ALM';
    protected $fillable = [
        'CVE_ALM',
        'DESCR',
        'DIRECCION',
        'ENCARGADO',
        'TELEFONO',
        'LISTA_PREC',
        'CUEN_CONT',
        'CVE_MENT',
        'CVE_MSAL',
        'STATUS',
        'LAT',
        'LON',
        'UUID',
        'VERSION_SINC',
        'UBI_DEST'
    ];

    public $timestamps = false;
}
