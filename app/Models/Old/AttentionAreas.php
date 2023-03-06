<?php

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttentionAreas extends Model
{
    use HasFactory;
    protected  $table = 'cat_v3_areas_atencion';
    protected $primaryKey = 'Id';
    public $timestamps = false;
    protected $filable = [
        'IdCliente',
        'Nombre',
        'ClaveCorta',
        'Descripcion',
        'Flag'
    ];

    public static function get(int $id = null, int $customer_id = null, int $business_id = null, int $active = null)
    {
        $result = self::select(
            'cat_v3_areas_atencion.Id',
            'cat_v3_areas_atencion.Nombre',
            'cat_v3_areas_atencion.ClaveCorta',
            'cat_v3_areas_atencion.Descripcion',
            'cat_v3_areas_atencion.Flag'
        );

        if (!is_null($id))
            $result->where('cat_v3_areas_atencion.Id', $id);

        if (!is_null($customer_id))
            $result->where('cat_v3_areas_atencion.IdCliente', $customer_id);

        if (!is_null($active))
            $result->where('cat_v3_areas_atencion.Flag', $active);

        if (!is_null($business_id)) {
            $result->join('cat_v3_areas_x_unidad as caxu', 'caxu.IdArea', '=', 'cat_v3_areas_atencion.Id')
                ->where('caxu.IdUnidadNegocio', $business_id);
        }


        return $result->orderBy('cat_v3_areas_atencion.Nombre', 'asc')->get();
    }
}
