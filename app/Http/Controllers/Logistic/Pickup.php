<?php

namespace App\Http\Controllers\Logistic;

use App\Http\Controllers\Controller;
use App\Models\Old\Branches;
use Illuminate\Http\Request;
use App\Models\Logistic\Pickup as PickupModel;
use App\Models\Old\Censos as CensosModel;
use App\Models\Old\DeviceModels as DeviceModelsModel;
use App\Models\Logistic\PickupBoxedItems as PickupBoxedItemsModel;

class Pickup extends Controller
{
    public function index()
    {
        $table_headers = [
            ['label' => '', 'classes' => ['all']],
            ['label' => __('Id'), 'classes' => ['never']],
            ['label' => __('Sucursal'), 'classes' => ['all align-middle']],
            ['label' => __('Usuario'), 'classes' => ['desktop align-middle']],
            ['label' => __('Estado'), 'classes' => ['all align-middle']],
            ['label' => __('Fecha de creación'), 'classes' => ['none']],
        ];

        return view('logistic.pickup.index', [
            'title_content' => $this->title_content('logistic.pickup.index'),
            'table_headers' => $table_headers,
            'pickupForm' => view('logistic.pickup.new_form', [
                'branches' => Branches::where('IdCliente', 1)
                    ->where('Flag', 1)
                    ->whereNotIn('IdUnidadNegocio', [12, 14])
                    ->select('Id', 'Nombre')
                    ->orderBy('Nombre')
                    ->get(),
            ])
        ]);
    }

    public function one($id)
    {
        $pickup = PickupModel::getPickups($id)[0];
        $censo = CensosModel::getLast($pickup->BranchId);
        $boxed = $this->boxedItems($id, $censo);
        $unboxed = $this->unboxedItems($id, $censo);
        $models = DeviceModelsModel::compact(null, 1);
        $extraItems = PickupBoxedItemsModel::extraItems($id);

        return view('logistic.pickup.one', [
            'id' => $id,
            'title_content' => $this->title_content('logistic.pickup.one', [
                'branch' => $pickup->BranchName,
            ]),
            'boxedItems' => $this->getBoxGroupedCenso($boxed['items']),
            'unboxedItems' => $this->getLineGroupedCenso($unboxed['items']),
            'boxSelectionForm' => view('logistic.pickup.box_selection_form', []),
            'notCensoItemForm' => view('logistic.pickup.not_censo_item_form', [
                'models' => $models,
            ]),
            'extraItems' => $extraItems,
        ]);
    }

    private function boxedItems($pickup_id, $censo)
    {
        $boxed = [
            'total' => 0,
            'items' => []
        ];
        $boxedIds = $this->getBoxedItemsIds($pickup_id);
        foreach ($censo as $item) {
            if (array_key_exists($item->Id, $boxedIds))
                $item->BoxNumber = $boxedIds[$item->Id];
            array_push($boxed['items'], $item);
        }
        return $boxed;
    }

    private function unboxedItems($pickup_id, $censo)
    {
        $unboxed = [
            'total' => 0,
            'items' => []
        ];
        $boxedIds = $this->getBoxedItemsIds($pickup_id);
        foreach ($censo as $item) {
            if (!array_key_exists($item->Id, $boxedIds))
                array_push($unboxed['items'], $item);
        }
        return $unboxed;
    }

    private function getBoxedItemsIds($pickup_id)
    {
        $ids = [];
        $records = PickupBoxedItemsModel::where('PickupId', $pickup_id)
            ->select('CensoId', 'BoxNumber')
            ->get();
        foreach ($records as $k => $record) {
            $ids[$record->CensoId] = $record->BoxNumber;
        }
        return $ids;
    }

    private function getLineGroupedCenso($censo)
    {
        $groupedCenso = [];
        foreach ($censo as $item) {
            if (!array_key_exists($item->Linea . ' - ' . $item->Sublinea, $groupedCenso))
                $groupedCenso[$item->Linea . ' - ' . $item->Sublinea] = [];

            array_push($groupedCenso[$item->Linea . ' - ' . $item->Sublinea], $item);
        }
        return $groupedCenso;
    }

    private function getBoxGroupedCenso($censo)
    {
        $groupedCenso = [];
        foreach ($censo as $item) {
            if (!array_key_exists($item->BoxNumber, $groupedCenso))
                $groupedCenso[$item->BoxNumber] = [];

            if ($item->BoxNumber > 0)
                array_push($groupedCenso[$item->BoxNumber], $item);
        }
        return $groupedCenso;
    }
}
