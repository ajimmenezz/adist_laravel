<?php

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiciosTicket extends Model
{
    use HasFactory;
    protected $table = 't_servicios_tickets';
    protected $primaryKey = 'Id';
    protected $fillable = [
        'Ticket',
        'IdSolicitud',
        'IdTipoServicio',
        'IdSucursal',
        'IdEstatus',
        'Solicita',
        'Atiende',
        'FechaCreacion',
        'FechaInicio',
        'FechaConclusion',
        'Descripcion',
        'Firma',
        'NombreFirma',
        'CorreoCopiaFirma',
        'FechaFirma',
        'FechaTentativa',
        'IdTecnicoFirma',
        'FirmaTecnico',
        'IdValidaCinemex',
        'IdServicioOrigen',
        'IdUsuarioValida',
        'FechaValidacion',
        'CalendarId',
        'CalendarLink',
        'Autorizacion'
    ];
    public $timestamps = false;
}
