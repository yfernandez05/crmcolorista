<?php

namespace App\Http\Controllers;

use App\Models\Ciclo;
use App\Util\LogErrorManager;
use App\Util\ResultManager;
use App\Util\RuleManager;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CicloController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $this->getFilters($request, new Ciclo());
        $perpage = $this->getLimitPagination($request);

        $ciclo = Ciclo::where($filters)
            ->orderBy('id', 'DESC')
            ->paginate($perpage);
            
        return $ciclo;
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

            $ciclo = $this->setModel(new Ciclo(), $request);
            $ciclo->created_usr  = $this->user->email;
            $ciclo->save();

            $result = ResultManager::genericSuccessMessage();

        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);

            $result = ResultManager::errorMessage('Es posible que haya un ciclo registrado con el mismo nombre.');

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);

            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ciclo  $ciclo
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Ciclo::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ciclo  $ciclo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = "";
        try {

            $ciclo = $this->setModel(Ciclo::findOrFail($id), $request);
            $ciclo->created_usr = $this->user->email;
            $ciclo->update();

            $result = ResultManager::genericSuccessMessage();

        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);

            $result = ResultManager::errorMessage('Es posible que hay un ciclo registrado con el mismo nombre.');

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);

            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ciclo  $ciclo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = "";
        try {

            $ciclo = Ciclo::findOrFail($id);
            $ciclo->estado = RuleManager::DISABLED_STATE;
            $ciclo->update();

            $result = ResultManager::successMessage('Ciclo eliminado correctamente.');
            
        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    private function setModel(Ciclo $ciclo, Request $request): Ciclo
    {
        $ciclo->nombre = $request->nombre;
        $ciclo->descripcion = $request->descripcion;
        return $ciclo;
    }

    public function select(Request $request)
    {
        $filters = $this->getFilters($request, new Ciclo());

        $ciclo = Ciclo::where($filters)
            -> orderBy('id', 'DESC')
            ->get();

        return $ciclo;
    }
}
