<?php

namespace App\Models\SAE8;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    protected $connection = 'sqlsrv';
    protected $table = 'dbo.MULT03';
    protected $fillable = [
        'CVE_ART',
        'CVE_ALM',
        'STATUS',
        'CTRL_ALM',
        'EXIST',
        'STOCK_MIN',
        'STOCK_MAX',
        'COMP_X_REC',
        'UUID',
        'VERSION_SINC'
    ];

    public $timestamps = false;
}
