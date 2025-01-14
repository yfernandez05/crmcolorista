<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use App\Util\LogErrorManager;
use App\Util\ResultManager;
use App\Util\RuleManager;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TurnoController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $this->getFilters($request, new Turno());
        $perpage = $this->getLimitPagination($request);

        $turnos = Turno::where($filters)
            ->orderBy('id', 'DESC')
            ->paginate($perpage);
            
        return $turnos;
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

            $turno = $this->setModel(new Turno(), $request);
            $turno->created_usr  = $this->user->email;
            $turno->save();

            $result = ResultManager::genericSuccessMessage();

        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);

            $result = ResultManager::errorMessage('Es posible que haya un tuno registrado con el mismo nombre.');

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);

            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Turno  $turno
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Turno::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Turno  $turno
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = "";
        try {

            $turno = $this->setModel(Turno::findOrFail($id), $request);
            $turno->created_usr = $this->user->email;
            $turno->update();

            $result = ResultManager::genericSuccessMessage();

        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);

            $result = ResultManager::errorMessage('Es posible que hay un turno registrado con el mismo nombre.');

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);

            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Turno  $turno
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = "";
        try {

            $turno = Turno::findOrFail($id);
            $turno->estado=RuleManager::DISABLED_STATE;
            $turno->update();

            $result = ResultManager::successMessage('Turno eliminado correctamente.');
            
        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    private function setModel(Turno $turno, Request $request): Turno
    {
        $turno->nombre = $request->nombre;
        $turno->descripcion = $request->descripcion;
        return $turno;
    }

    public function select(Request $request)
    {
        $filters = $this->getFilters($request, new Turno());

        $queryprospecto = Turno::where($filters);

        $prospecto = $queryprospecto->orderBy('id', 'DESC')
            ->get();
        return $prospecto;

    }

}
