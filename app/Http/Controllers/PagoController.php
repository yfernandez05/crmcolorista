<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Pago;
use Barryvdh\DomPDF\Facade as PDF;
use App\Util\RuleManager;
use App\Models\DetallePago;
use App\Util\ResultManager;
use Illuminate\Http\Request;
use App\Util\LogErrorManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

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
            ->with('matricula','matricula.alumno','comprobante')
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
            $pago->codcomprobante = $request->codcomprobante;
            $pago->serie = $request->serie;
            $pago->numero = $request->numero;
            $pago->save();

            $detallePagos = [];

            foreach ($request->detalles as $detalle) {
                $detallePago = new DetallePago();
                $detallePago->concepto_id = $detalle['concepto_id'];
                $detallePago->precio_unitario = $detalle['precio_unitario'];
                $detallePago->descuento = isset($detalle['descuento']) ? $detalle['descuento'] : 0.00;
                $detallePago->importe = $detalle['importe'];
                $detallePago->nombre_numero_mensualidad = $detalle['nombre_numero_mensualidad'];
                $detallePago->created_usr = $this->user->email;

                $detallePago->pago_id = $pago->id;
                $detallePagos[] = $detallePago;
            }

            $pago->detalles()->saveMany($detallePagos);

            DB::commit();
            $result = ResultManager::genericSuccessMessage();

        } catch (QueryException $e) {
            DB::rollBack();
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            //dd($e);
            $result = ResultManager::gerericErrorMessage();

        } catch (Exception $e) {
            DB::rollBack();
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            //dd($e);
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
       /* $pago = Pago::with('matricula','detalles','alumno')->find($id);
        return $pago;*/
        $pago = Pago::with(['matricula.alumno', 'detalles','matricula.detalles','matricula.carrera','matricula.ciclo','comprobante'])->find($id);
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
        DB::beginTransaction();
        try {
            $pago = Pago::findOrFail($id);
            $pago->updated_usr = $this->user->email;
            $pago->subtotal = $request->subtotal;
            $pago->matricula_id = $request->matricula_id;
            $pago->codcomprobante = $request->codcomprobante;
            $pago->serie = $request->serie;
            $pago->update();

            $detallesExistentes = $pago->detalles()->get();

            $detallesRecibidosIds = collect($request->detalles)->pluck('id')->filter();

            foreach ($detallesExistentes as $detalleExistente) {
                if (!in_array($detalleExistente->id, $detallesRecibidosIds->toArray())) {
                    $detalleExistente->delete();
                }
            }

            foreach ($request->detalles as $detalle) {
                DetallePago::updateOrCreate(
                    ['id' => $detalle['id'] ?? null],
                    [
                        'concepto_id' => $detalle['concepto_id'],
                        'precio_unitario' => $detalle['precio_unitario'],
                        'descuento' => $detalle['descuento'] ?? 0.00,
                        'importe' => $detalle['importe'],
                        'nombre_numero_mensualidad' => $detalle['nombre_numero_mensualidad'],
                        'updated_usr' => $this->user->email,
                        'pago_id' => $pago->id,
                    ]
                );
            }

            DB::commit();
            $result = ResultManager::genericSuccessMessage();
        } catch (QueryException $e) {
            DB::rollBack();
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();
        } catch (Exception $e) {
            DB::rollBack(); 
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

    public function comprobante($id)
    {
        if (empty($id)) {
            return redirect()->route('spa');
        }

        $comprobante = Pago::with(['matricula.alumno', 'detalles','matricula.carrera','matricula.ciclo','comprobante'])->find($id);

        $pdf = PDF::loadView('factura.factura', ['factura' => $comprobante]);

        $nombrearchivo= "PRESUPUESTO ". $comprobante->numero.".pdf";
        return $pdf->stream($nombrearchivo);
       // return $pdf->stream();
    }

}
