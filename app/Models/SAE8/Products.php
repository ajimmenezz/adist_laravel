<?php

namespace App\Models\SAE8;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    protected $connection = 'sqlsrv';
    protected $table = 'dbo.INVE03';
    protected $primaryKey = 'CVE_ART';
    protected $fillable = [
        'CVE_ART',
        'DESCR',
        'LIN_PROD',
        'CON_SERIE',
        'UNI_MED',
        'UNI_EMP',
        'CTRL_ALM',
        'TIEM_SURT',
        'STOCK_MIN',
        'STOCK_MAX',
        'TIP_COSTEO',
        'NUM_MON',
        'FCH_ULTCOM',
        'COMP_X_REC',
        'FCH_ULTVTA',
        'PEND_SURT',
        'EXIST',
        'COSTO_PROM',
        'ULT_COSTO',
        'CVE_OBS',
        'TIPO_ELE',
        'UNI_ALT',
        'FAC_CONV',
        'APART',
        'CON_LOTE',
        'CON_PEDIMENTO',
        'PESO',
        'VOLUMEN',
        'CVE_ESQIMPU',
        'CVE_BITA',
        'VTAS_ANL_C',
        'VTAS_ANL_M',
        'COMP_ANL_C',
        'COMP_ANL_M',
        'PREFIJO',
        'TALLA',
        'COLOR',
        'CUENT_CONT',
        'CVE_IMAGEN',
        'BLK_CST_EXT',
        'STATUS',
        'MAN_IEPS',
        'APL_MAN_IMP',
        'CUOTA_IEPS',
        'APL_MAN_IEPS',
        'UUID',
        'VERSION_SINC',
        'VERSION_SINC_FECHA_IMG',
        'CVE_PRODSERV',
        'CVE_UNIDAD',
        'DISPONIBLE_PUBL',
        'MAT_PELI',
        'TITULO_ML',
        'CATEG_ML',
        'CAMPOS_CATEG_ML',
        'ID_CATALOGO',
        'ENVIO_ML',
        'EDO_PUBL_ML',
        'CVE_CATE_ML',
        'EN_CATALOGO',
        'IMAGEN_ML',
        'LAST_UPDATE_ML',
        'F_CREA_ML',
        'TIPO_PUBL_ML',
        'CVE_PUBL_ML',
        'CONDICION_ML',
        'ANCHO_ML',
        'MODO_ENVIO_ML',
        'LARGO_ML',
        'ALTO_ML'
    ];

    public $timestamps = false;
}
