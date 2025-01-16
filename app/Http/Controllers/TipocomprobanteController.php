<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Tipocomprobante;
use App\Models\Venta;
use App\Util\LogErrorManager;
use App\Util\ResultManager;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TipocomprobanteController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $this->getFilters($request, new Tipocomprobante());
        $perpage = $this->getLimitPagination($request);

        $tipocomprobante = Tipocomprobante::where($filters)
            ->orderBy('codcomprobante', 'DESC')
            ->paginate($perpage);

        return $tipocomprobante;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result;

        try {

            $tipocomprobante = $this->setModel(new Tipocomprobante(), $request);
            $tipocomprobante->userIng = $this->user->email;
            $tipocomprobante->save();

            $result = ResultManager::genericSuccessMessage();

        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::errorMessage('Es posible que haya un comprobante registrado con el mismo nombre.');

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Tipocomprobante::find($id);
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

        $result;

        try {

            $tipocomprobante = $this->setModel(Tipocomprobante::findOrFail($id), $request);
            $tipocomprobante->userUpd = $this->user->email;
            $tipocomprobante->update();

            $result = ResultManager::genericSuccessMessage();

        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::errorMessage('Es posible que haya un comprobante registrado con el mismo nombre.');

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result;
        $errorMsg = 'No se puede eliminar. Hay Compras y/o Ventas que hacen uso de este Tipo de Comprobante.';
        try {
            $numberDependentsSales = Venta::where(['codcomprobante' => $id, 'estado' => 'A'])->count();
            $numberDependentsPurchase = Compra::where(['codcomprobante' => $id, 'estado' => 'A'])->count();

            if ($numberDependentsSales == 0 && $numberDependentsPurchase == 0) {

                $tipocomprobante = Tipocomprobante::findOrFail($id);
                $tipocomprobante->estado = 'E';
                $tipocomprobante->userUpd = $this->user->email;
                $tipocomprobante->update();

                $result = ResultManager::successMessage('Tipo de Comprobante eliminado correctamente.');
            } else {
                $result = ResultManager::errorMessage($errorMsg);
            }

        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::errorMessage($errorMsg);

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    public function select(Request $request)
    {
        $filters = $this->getFilters($request, new Tipocomprobante());

        $tipocomprobante = Tipocomprobante::where($filters)
            // ->orderBy('codcomprobante', 'DESC')
            ->get();

        return $tipocomprobante;
    }

    private function setModel(Tipocomprobante $tipocomprobante, Request $request): Tipocomprobante
    {
        $tipocomprobante->nombrecomprobante = $request->nombrecomprobante;
        $tipocomprobante->codigosunat = $request->codigosunat;
        $tipocomprobante->serie = $request->serie;
        $tipocomprobante->agregarigv = $request->agregarigv;

        return $tipocomprobante;
    }
}
