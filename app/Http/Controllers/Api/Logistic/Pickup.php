<?php

namespace App\Http\Controllers\Api\Logistic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Logistic\Pickup as PickupModel;
use Illuminate\Support\Facades\DB;
use App\Models\Old\Censos as CensosModel;
use App\Models\Logistic\PickupBoxedItems as PickupBoxedItemsModel;
use Fpdf\Fpdf;

class Pickup extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return $this->success(__('Lista de recolecciones'), [
                'Pickups' => PickupModel::getPickups()
            ]);
        } catch (\Exception $e) {
            return $this->error(500, __('Error al obtener la lista de recolecciones'), [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            if (PickupModel::where('BranchId', $request->branch)
                ->whereNotIn('StatusId', [4, 6])->exists()
            ) {
                return $this->error(500, __('Ya existe una recolección en proceso para esta sucursal'));
            }

            $pickup = PickupModel::create([
                'BranchId' => $request->branch,
                'StatusId' => 1,
                'UserId' => $request->user->Id
            ]);

            return $this->success(__('Espere, en breve será redirigido a la recolección'), [
                'Pickup' => $pickup
            ]);
        } catch (\Exception $e) {
            return $this->error(500, __('Error al guardar la recolección'), [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function storeBoxedCensoItems(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $items = [];
            foreach ($request->input('items') as $item) {
                $censo = CensosModel::getOne($item);
                PickupBoxedItemsModel::create([
                    'PickupId' => $id,
                    'UserId' => $request->user->Id,
                    'BoxNumber' => $request->input('box'),
                    'CensoId' => $censo->Id,
                    'Quantity' => 1,
                    'ModelId' => $censo->IdModelo,
                    'SerialNumber' => $censo->Serie
                ]);

                array_push($items, $censo);
            }

            DB::commit();
            return $this->success(__('Items guardados correctamente'), [
                'Items' => $items
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error(500, __('Error al guardar los items de la recolección'), [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
        }
    }

    public function deleteBoxedCensoItems(Request $request, $id)
    {
        try {
            PickupBoxedItemsModel::where('PickupId', $id)
                ->where('CensoId', $request->input('censoId'))
                ->delete();

            return $this->success(__('El equipo se ha sacado de la caja correctamente'));
        } catch (\Exception $e) {
            return $this->error(500, __('Error al sacar el equipo de la caja'), [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
        }
    }

    public function storeExtraItems(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            if ($request->input('type') == "d") {
                PickupBoxedItemsModel::create([
                    'PickupId' => $id,
                    'UserId' => $request->user->Id,
                    'BoxNumber' => $request->input('box'),
                    'CensoId' => null,
                    'Quantity' => 1,
                    'ModelId' => $request->input('model'),
                    'SerialNumber' => ($request->input('serial') != "" ? $request->input('serial') : 'ILEGIBLE')
                ]);
            } else {
                foreach ($request->input('components') as $item) {
                    PickupBoxedItemsModel::create([
                        'PickupId' => $id,
                        'UserId' => $request->user->Id,
                        'BoxNumber' => $request->input('box'),
                        'CensoId' => null,
                        'Quantity' => $item['quantity'],
                        'ModelId' => $request->input('model'),
                        'ComponentId' => $item['id'],
                        'SerialNumber' => 'ILEGIBLE'
                    ]);
                }
            }

            DB::commit();

            $items = PickupBoxedItemsModel::extraItems($id, $request->input('box'));

            return $this->success(
                __('Item guardados correctamente'),
                [
                    'Items' => $items[$request->input('box')]
                ]
            );
        } catch (\Exception $e) {
            return $this->error(500, __('Error al guardar el item de la recolección'), [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
        }
    }

    public function deleteExtraItem(Request $request, $id, $rid)
    {
        try {
            PickupBoxedItemsModel::where('PickupId', $id)
                ->where('Id', $rid)
                ->delete();

            return $this->success(__('El equipo se ha sacado de la caja correctamente'));
        } catch (\Exception $e) {
            return $this->error(500, __('Error al sacar el equipo de la caja'), [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
        }
    }

    public function exportPdf(Request $request, $id)
    {
        try {
            $pdf = new BoxPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(40, 10, 'Hello World!');
            $pdf->Output('F', public_path('storage/Logistic/Pickups/' . $id . '.pdf'));
        } catch (\Exception $e) {
            return $this->error(500, __('Error al exportar el PDF'), [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
        }
    }
}

class BoxPDF extends Fpdf
{
    public function __construct(
        $orientation = 'L',
        $unit = 'mm',
        $size = 'letter'
    ) {
        parent::__construct($orientation, $unit, $size);
    }
}
