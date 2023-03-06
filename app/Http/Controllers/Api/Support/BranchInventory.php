<?php

namespace App\Http\Controllers\Api\Support;

use App\Http\Controllers\Controller;
use App\Models\Censos\TDeviceAccesories;
use Illuminate\Http\Request;
use App\Models\Old\Censos;


class BranchInventory extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $branch_inventories = Censos::getPendings($request->user->Id);
            return $this->success('Censos pendientes del usuario', [
                'BranchInventories' => $branch_inventories
            ]);
        } catch (\Exception $e) {
            return $this->error(500, 'No hemos podido obtener los censos activos de las sucursales', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
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
        //
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

    public function storePoint($id, $area)
    {
        try {
            $point = Censos::addPoint($id, $area);
            return $this->success('Punto agregado', [
                'point' => $point
            ]);
        } catch (\Exception $e) {
            return $this->error(500, 'No hemos podido agregar el punto al censo.', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
        }
    }

    public function updateModel(Request $request, $id)
    {
        try {
            Censos::updateModel($id, $request->input("model"));
            return $this->success('Modelo actualizado');
        } catch (\Exception $e) {
            return $this->error(500, 'No hemos podido actualizar el modelo del equipo.', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
        }
    }

    public function updateSerial(Request $request, $id)
    {
        try {
            $serial = $request->input("serial") && $request->input("serial") != '' ? $request->input("serial") : 'ILEGIBLE';
            Censos::updateSerial($id, $serial);
            return $this->success('Serie actualizada');
        } catch (\Exception $e) {
            return $this->error(500, 'No hemos podido actualizar el número de serie del equipo.', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            Censos::updateStatus($id, $request->input("status"));
            return $this->success('Estado actualizado');
        } catch (\Exception $e) {
            return $this->error(500, 'No hemos podido actualizar el estado del equipo.', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
        }
    }

    public function updateFeature(Request $request, $id)
    {
        try {
            Censos::updateFeature($id, $request->input("feature"), $request->input("value"));
            return $this->success('Característica actualizada');
        } catch (\Exception $e) {
            return $this->error(500, 'No hemos podido actualizar la característica del equipo.', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
        }
    }

    public function updateAccesory(Request $request, $id)
    {
        try {
            TDeviceAccesories::updateOrCreate([
                'CensoId' => $id,
                'AccesoryId' => $request->input("component")
            ], [
                'Quantity' => $request->input("quantity"),
                'Active' => 1
            ]);
            return $this->success('Accesorio actualizado');
        } catch (\Exception $e) {
            return $this->error(500, 'No hemos podido actualizar la cantidad del accesorio.', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
        }
    }

    public function storeDevice(Request $request, $id, $area, $point)
    {
        try {
            $device = Censos::addDevice($id, $area, $point, $request->input('model'), $request->input('serial'), $request->input('status'));
            return $this->success('Equipo agregado', [
                'device' => $device
            ]);
        } catch (\Exception $e) {
            return $this->error(500, $e->getMessage(), [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
        }
    }

    public function deleteDevice($id)
    {
        try {
            Censos::where('Id', $id)->delete();
            return $this->success('Equipo eliminado');
        } catch (\Exception $e) {
            return $this->error(500, 'No hemos podido eliminar el equipo.', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
        }
    }
}
