<?php

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    use HasFactory;
    protected $table = "cat_v3_clientes";
    protected $fillable = [
        "Nombre",
        "RazonSocial",
        "RFC",
        "IdPais",
        "IdEstado",
        "IdMunicipio",
        "IdColonia",
        "Calle",
        "NoExt",
        "NoInt",
        "Telefono1",
        "Telefono2",
        "Email",
        "Web",
        "Representante",
        "FechaRegistro",
        "Flag"
    ];
    protected $primaryKey = "Id";

    public $timestamps = false;
}
