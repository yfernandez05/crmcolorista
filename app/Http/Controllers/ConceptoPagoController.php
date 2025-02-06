<?php

namespace App\Http\Controllers;

use Exception;
use App\Util\RuleManager;
use App\Models\DetallePago;
use App\Util\ResultManager;
use App\Models\ConceptoPago;
use Illuminate\Http\Request;
use App\Util\LogErrorManager;
use Illuminate\Database\QueryException;

class ConceptoPagoController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $this->getFilters($request, new ConceptoPago());
        $perpage = $this->getLimitPagination($request);

        $conceptopago = ConceptoPago::where($filters)
            ->orderBy('id', 'DESC')
            ->paginate($perpage);

        return $conceptopago;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = "";
        try {

            $conceptopago = $this->setModel(new ConceptoPago(), $request);
            $conceptopago->created_usr  = $this->user->email;
            $conceptopago->save();

            $result = ResultManager::genericSuccessMessage();

        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);

            $result = ResultManager::errorMessage('Es posible que haya un concepto de pago registrado con el mismo nombre.');

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);

            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ConceptoPago  $conceptoPago
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return ConceptoPago::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ConceptoPago  $conceptoPago
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = "";
        try {

            $conceptoPago = $this->setModel(ConceptoPago::findOrFail($id), $request);
            $conceptoPago->created_usr = $this->user->email;
            $conceptoPago->update();

            $result = ResultManager::genericSuccessMessage();

        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);

            $result = ResultManager::errorMessage('Es posible que hay un concepto de pago registrado con el mismo nombre.');

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);

            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ConceptoPago  $conceptoPago
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $result ="";
        $errorMsg='No se puede eliminar. Pago hace uso de este concepto.';

        try {
            $numberDependents = DetallePago::where(['id'=>$id, 'estado'=> 'A'])->count();

            if($numberDependents==0){

                $conceptoPago = ConceptoPago::findOrFail($id);
                $conceptoPago->estado = RuleManager::DISABLED_STATE;
                $conceptoPago->update();

                $result = ResultManager::successMessage('Concepto eliminado correctamente.');
            }else{
                $result=ResultManager::errorMessage($errorMsg);
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

    private function setModel(ConceptoPago $conceptoPago, Request $request): ConceptoPago
    {
        $conceptoPago->nombre = $request->nombre;
        $conceptoPago->descripcion = $request->descripcion;
        return $conceptoPago;
    }

    public function select(Request $request)
    {
        $filters = $this->getFilters($request, new ConceptoPago());

        $queryconcepto = ConceptoPago::where($filters);

        $conceptopago = $queryconcepto->orderBy('id', 'DESC')
            ->get();
        return $conceptopago;
    }

}
