<?php

namespace App\Http\Controllers;

use App\Models\Condicion;
use App\Util\LogErrorManager;
use App\Util\ResultManager;
use App\Util\RuleManager;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CondicionController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $this->getFilters($request, new Condicion());
        $perpage = $this->getLimitPagination($request);

        $query = Condicion::where($filters);

        $condiciones = $query-> orderBy('id', 'DESC')
            ->paginate($perpage);

        return $condiciones;
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

            $condicion = $this->setModel(new Condicion(), $request);
            $condicion->created_usr = $this->user->email;
            $condicion->save();

            $result = ResultManager::genericSuccessMessage();
        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);

            $result = ResultManager::errorMessage('Es posible que hay una condicion registrado con el mismo nombre.');

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);

            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Condicion  $condicion
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Condicion::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Condicion  $condicion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = "";
        try {

            $condicion = $this->setModel(Condicion::findOrFail($id), $request);
            $condicion->updated_usr = $this->user->email;
            $condicion->update();

            $result = ResultManager::genericSuccessMessage();
        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::errorMessage('Es posible que hay una condicion registrada con el mismo nombre.');

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Condicion  $condicion
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = "";

        try {    

            $condicion = Condicion::findOrFail($id);
            $condicion->estado = RuleManager::DISABLED_STATE;
            $condicion->updated_usr = $this->user->email;
            $condicion->update();

            $result = ResultManager::successMessage('Cuenta eliminado correctamente.');

        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    private function setModel(Condicion $condicion, Request $request): Condicion
    {
        $condicion->nombre = $request->nombre;
        return $condicion;
    }
}
