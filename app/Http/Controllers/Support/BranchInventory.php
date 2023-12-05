<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Models\Censos\TDeviceAccesories;
use Illuminate\Http\Request;
use App\Models\Old\Censos;
use App\Models\Old\AttentionAreas;
use App\Models\Old\DeviceModels;
use App\Models\Old\Status;
use App\Models\Old\DeviceComponents;
use App\Models\Old\SublinesByArea;
use App\Models\Inventory\Catalogs\CInventoryFeaturesByLine;
use App\Models\Censos\TDeviceFeatures;
use App\Exports\Inventories\Branch as BranchExport;
use Maatwebsite\Excel\Facades\Excel;

class BranchInventory extends Controller
{
    public function index()
    {
        $table_headers = [
            ['label' => '', 'classes' => ['all']],
            ['label' => __('Id'), 'classes' => ['never']],
            ['label' => __('Sucursal'), 'classes' => ['all align-middle']],
            ['label' => __('Atiende'), 'classes' => ['desktop align-middle']],
            ['label' => __('Estado'), 'classes' => ['all align-middle']],
            ['label' => __('Fecha de creación'), 'classes' => ['desktop align-middle']]
        ];

        return view('support.branch_inventory.index', [
            'title_content' => $this->title_content('support.branch_inventory.index', [
                'title' => 'Censos'
            ]),
            'table_headers' => $table_headers
        ]);
    }

    public function one($id)
    {
        $service = Censos::getService($id);

        return view("support.branch_inventory.one", [
            'title_content' => $this->title_content('support.branch_inventory.one', [
                'title' => 'Censo ',
                'branch' => $service->Branch
            ]),
            'service' => $service,
            'attention_areas' => AttentionAreas::get(null, $service->CustomerId, $service->BusinessUnitId, 1),
            'points_by_area' => Censos::getPointsByArea($id),
            'items_by_area' => Censos::getItemsByArea($id),
        ]);
    }

    public function area(Request $request, $id, $area)
    {
        $service = Censos::getService($id);
        $area = AttentionAreas::where('Id', $area)->first();
        $kit_sublines = SublinesByArea::get($service->BusinessUnitId, $area->Id);

        $title_content = $this->title_content('support.branch_inventory.area', [
            'title' => 'Censo ',
            'branch' => $service->Branch,
            'area' => $area->Nombre,
            'serviceId' => $service->Id,
            'point' => $request->input('point') ?? ''
        ]);

        $point_devices = $request->input('point') ? Censos::getPointDevices($id, $area->Id, $request->input('point')) : [];
        $point_devices = $this->addLineFeaturesToDevices($point_devices);
        $point_devices = $this->addFeaturesValuesToDevices($point_devices);
        $point_devices = $this->addAccesoriesToDevices($point_devices);
        $point_devices = $this->addAccesoriesQuantityToDevices($point_devices);

        $models = DeviceModels::compact(null, 1);
        $device_status = Status::censo();

        $kit_sublines = $this->compareDevicesWithKit($point_devices, $kit_sublines);


        return view("support.branch_inventory.area", [
            'inputs' => $request->all(),
            'title_content' => $title_content,
            'service' => $service,
            'area' => $area,
            'points_by_area' => Censos::getPointsByArea($id, $area->Id),
            'models' => $models,
            'point_devices' => $point_devices,
            'device_status' => $device_status,
            'kit_sublines' => $kit_sublines,
            'new_device_form' => view('support.branch_inventory.new_device_form', [
                'serviceId' => $service->Id,
                'areaId' => $area->Id,
                'point' => $request->input('point') ?? '',
                'models' => $models,
                'device_status' => $device_status
            ]),
        ]);
    }

    private function addLineFeaturesToDevices($point_devices)
    {
        foreach ($point_devices as $device) {
            $device->features = CInventoryFeaturesByLine::getFeaturesByLine($device->LineId, $device->SublineId);
        }
        return $point_devices;
    }

    private function addFeaturesValuesToDevices($point_devices)
    {
        foreach ($point_devices as $device) {
            $values = [];

            $values_assigned = TDeviceFeatures::getFeaturesById($device->Id);
            foreach ($values_assigned as $value) {
                $values[$value->FeatureId] = $value->Value;
            }

            $device->features_values = $values;
        }

        return $point_devices;
    }

    private function addAccesoriesToDevices($point_devices)
    {
        foreach ($point_devices as $device) {
            $device->accesories = DeviceComponents::getAccesoriesByModel($device->ModelId);
        }
        return $point_devices;
    }

    private function addAccesoriesQuantityToDevices($point_devices)
    {
        foreach ($point_devices as $device) {
            $values = [];

            $values_assigned = TDeviceAccesories::getAccesoriesByDevice($device->Id);
            foreach ($values_assigned as $value) {
                $values[$value->AccesoryId] = $value->Quantity;
            }

            $device->accesories_values = $values;
        }

        return $point_devices;
    }

    private function compareDevicesWithKit($devices, $kit)
    {
        foreach ($kit as $k => $subline) {
            foreach ($devices as $device) {
                if ($device->SublineId == $subline->Id) {
                    $subline->Quantity--;
                }
            }

            if ($subline->Quantity <= 0) {
                unset($kit[$k]);
            }
        }
        return $kit;
    }

    public function xlsExport($id)
    {
        try{
            $service = Censos::getService($id);
            $datetime = date('Ymd_His');

            return (new BranchExport($id))->download("Censo_{$service->Branch}_{$datetime}.xlsx");
        }catch(\Exception $e){
            return $this->error(500, "No se ha podido generar el archivo excel",[
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
        }
    }
}
