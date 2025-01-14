<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Util\RuleManager;
use App\Models\Seguimiento;
use App\Util\ResultManager;
use Illuminate\Http\Request;
use App\Util\LogErrorManager;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\QueryException;

class SeguimientoController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $this->getFilters($request, new Seguimiento());
        $perpage = $this->getLimitPagination($request);

        $query = Seguimiento::where($filters)
            ->with('prospecto');


        $seguimientos = $query-> orderBy('id', 'DESC')
            ->paginate($perpage);

        return $seguimientos;
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

            $seguimiento = $this->setModel(new Seguimiento(), $request);
            $seguimiento->created_usr = $this->user->email;
            $seguimiento->save();

            $result = ResultManager::genericSuccessMessage();
        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            dd($e);
            $result = ResultManager::errorMessage('Es posible que hay un Seguimiento registrado con el mismo correo.');

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            dd($e);
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Seguimiento  $alumno
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Seguimiento::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Seguimiento  $alumno
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = "";
        try {

            $seguimiento = $this->setModel(Seguimiento::findOrFail($id), $request);
            $seguimiento->updated_usr = $this->user->email;
            $seguimiento->update();

            $result = ResultManager::genericSuccessMessage();
        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::errorMessage('Es posible que hay un seguimiento registrada con el mismo correo.');

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Seguimiento  $alumno
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = "";

        try {

            $seguimiento = Seguimiento::findOrFail($id);
            $seguimiento->estado = RuleManager::DISABLED_STATE;
            $seguimiento->updated_usr = $this->user->email;
            $seguimiento->update();

            $result = ResultManager::successMessage('Seguimiento eliminado correctamente.');

        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    private function setModel(Seguimiento $seguimiento, Request $request): Seguimiento
    {

        $seguimiento->atencion = $request->atencion;
        $seguimiento->toque = $request->toque;
        $seguimiento->respuesta = $request->respuesta;
        $seguimiento->prospecto_id = $request->prospecto_id;
        $seguimiento->fecha = Carbon::now();
        $seguimiento->user_id = 1;

        return $seguimiento;
    }
}
