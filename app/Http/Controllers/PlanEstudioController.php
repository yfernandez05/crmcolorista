<?php

namespace App\Http\Controllers;

use App\Models\PlanEstudio;
use App\Util\LogErrorManager;
use App\Util\ResultManager;
use App\Util\RuleManager;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class PlanEstudioController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $this->getFilters($request, new PlanEstudio());
        $perpage = $this->getLimitPagination($request);

        $cursos = PlanEstudio::where($filters)
            ->with('carrera','ciclo')
            ->orderBy('id', 'DESC')
            ->paginate($perpage);
            
        return $cursos;
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

            $planestudio = $this->setModel(new PlanEstudio(), $request);
            $planestudio->created_usr  = $this->user->email;
            $planestudio->save();

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
     * Display the specified resource.
     *
     * @param  \App\PlanEstudio  $planEstudio
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return PlanEstudio::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PlanEstudio  $planEstudio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = "";
        try {

            $planestudio = $this->setModel(PlanEstudio::findOrFail($id), $request);
            $planestudio->created_usr = $this->user->email;
            $planestudio->update();

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
     * @param  \App\PlanEstudio  $planEstudio
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = "";
        try {

            $planestudio = PlanEstudio::findOrFail($id);
            $planestudio->estado = RuleManager::DISABLED_STATE;
            $planestudio->update();

            $result = ResultManager::successMessage('Plan de estudio eliminado correctamente.');
            
        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    private function setModel(PlanEstudio $planestudio, Request $request): PlanEstudio
    {
        $planestudio->costo = $request->costo;
        $planestudio->duracion_meses = $request->duracion_meses;
        $planestudio->carrera_id = $request->carrera_id;
        $planestudio->ciclo_id = $request->ciclo_id;
        return $planestudio;
    }
    
}
