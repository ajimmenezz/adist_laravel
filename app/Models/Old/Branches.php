<?php

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branches extends Model
{
    use HasFactory;
    protected $table = 'cat_v3_sucursales';
    protected $primaryKey = 'Id';
    public $timestamps = false;
}
