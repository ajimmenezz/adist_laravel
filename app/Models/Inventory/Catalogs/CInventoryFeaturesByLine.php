<?php

namespace App\Models\Inventory\Catalogs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CInventoryFeaturesByLine extends Model
{
    use HasFactory;
    protected $table = 'c_inventory_features_by_lines';
    protected $primaryKey = 'Id';
    protected $fillable = [
        'FeatureId',
        'LineId',
        'SubLineId',
        'Active'
    ];

    public static function getFeaturesByLine($line, $subline = null)
    {
        $result = DB::table('c_inventory_features_by_lines as fbl')
            ->join('c_inventory_features as f', 'fbl.FeatureId', '=', 'f.Id')
            ->select('f.Id', 'f.Name')
            ->where('fbl.Active', 1)
            ->where('f.Active', 1);

        $result->where(function ($query) use ($line, $subline) {
            $query->where(function ($query2) use ($line) {
                $query2->where('fbl.LineId', $line)->where('fbl.SubLineId', null);
            });

            if (!is_null($subline)) {
                $query->orWhere(function ($query2) use ($line, $subline) {
                    $query2->where('fbl.LineId', $line)->where('fbl.SubLineId', $subline);
                });
            }
        });

        $features = $result->get();

        foreach ($features as $feature) {
            $feature->Values = self::getValuesByFeatureId($feature->Id);
        }

        return $features;
    }

    public static function getValuesByFeatureId($featureId)
    {
        return DB::table('c_inventory_features_values as fv')
            ->select('fv.Id', 'fv.Value')
            ->where('fv.Active', 1)
            ->where('fv.FeatureId', $featureId)
            ->orderBy('fv.Value', 'asc')
            ->get();
    }
}
