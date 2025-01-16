<?php

namespace App\Http\Controllers;

use App\Models\DetallePago;
use App\Models\Pago;
use App\Util\LogErrorManager;
use App\Util\ResultManager;
use App\Util\RuleManager;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagoController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $this->getFilters($request, new Pago());
        $perpage = $this->getLimitPagination($request);

        $pago = Pago::where($filters)
            ->with('matricula','conceptopago')
            ->orderBy('id', 'DESC')
            ->paginate($perpage);
            
        return $pago;
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
        DB::beginTransaction();
        try {

            $pago = $this->setModel(new Pago(), $request);
            $pago->created_usr  = $this->user->email;
            $pago->save();

            //dd($request->detalles);

            //details
            $detallePagos = [];

            foreach ($request->detalles as $detalle) {
                $detallePago = new DetallePago();
                $detallePago->concepto_id = $detalle['concepto_id'];
                $detallePago->precio_unitario = $detalle['precio_unitario'];
                $detallePago->descuento = isset($detalle['descuento']) ? $detalle['descuento'] : 0.00;
                $detallePago->importe = $detalle['subtotal'];
                $detallePago->created_usr = $this->user->email;

                $detallePago->pago_id = $pago->id;
                $detallePagos[] = $detallePago;  // Agregar al array
            }

            // Guardar todos los detalles a la vez usando saveMany
            $pago->detalles()->saveMany($detallePagos);

            DB::commit();
            $result = ResultManager::genericSuccessMessage();

        } catch (QueryException $e) {
            DB::rollBack(); 
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            dd($e);
            $result = ResultManager::gerericErrorMessage();

        } catch (Exception $e) {
            DB::rollBack(); 
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            dd($e);
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Pago  $pago
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pago = Pago::with('matricula','conceptopago')->find($id);
        return $pago;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pago  $pago
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = "";
        try {

            $pago = $this->setModel(Pago::findOrFail($id), $request);
            $pago->created_usr = $this->user->email;
            $pago->update();

            $result = ResultManager::genericSuccessMessage();

        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);

            $result = ResultManager::gerericErrorMessage();

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);

            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pago  $pago
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = "";
        try {

            $pago = Pago::findOrFail($id);
            $pago->estado = RuleManager::DISABLED_STATE;
            $pago->update();

            $result = ResultManager::successMessage('Pago eliminado correctamente.');
            
        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    private function setModel(Pago $pago, Request $request): pago
    {
        $pago->matricula_id = $request->matricula_id;
        $pago->subtotal = $request->subtotal;
        $pago->detalle = $request->detalle;
        $pago->user_id = Auth()->user()->id;
        return $pago;
    }

}
