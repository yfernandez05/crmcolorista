<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use App\Util\LogErrorManager;
use App\Util\ResultManager;
use App\Util\RuleManager;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class InscripcionController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $this->getFilters($request, new Inscripcion());
        $perpage = $this->getLimitPagination($request);

        $inscripciones = Inscripcion::where($filters)
            ->with('user','carrera','alumno')
            ->orderBy('id', 'DESC')
            ->paginate($perpage);
            
        return $inscripciones;
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

            $inscripcion = $this->setModel(new Inscripcion(), $request);
            $inscripcion->created_usr  = $this->user->email;
            $inscripcion->save();

            $result = ResultManager::genericSuccessMessage();

        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            dd($e);
            $result = ResultManager::gerericErrorMessage();

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
     * @param  \App\Inscripcion  $inscripcion
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $inscripcion = Inscripcion::with('user','carrera','alumno')->find($id);
        return $inscripcion;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Inscripcion  $inscripcion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = "";
        try {

            $inscripcion = $this->setModel(Inscripcion::findOrFail($id), $request);
            $inscripcion->created_usr = $this->user->email;
            $inscripcion->update();

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
     * @param  \App\Inscripcion  $inscripcion
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = "";
        try {

            $inscripcion = Inscripcion::findOrFail($id);
            $inscripcion->estado = RuleManager::DISABLED_STATE;
            $inscripcion->update();

            $result = ResultManager::successMessage('Iscripción eliminado correctamente.');
            
        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    private function setModel(Inscripcion $inscripcion, Request $request): Inscripcion
    {
        $inscripcion->detalle = $request->detalle;
        $inscripcion->fecha = Carbon::createFromFormat('d-m-Y', $request->fecha);
        $inscripcion->alumno_id = $request->alumno_id;
        $inscripcion->carrera_id = $request->carrera_id;
        $inscripcion->user_id = Auth()->user()->id;
        return $inscripcion;
    }
}
