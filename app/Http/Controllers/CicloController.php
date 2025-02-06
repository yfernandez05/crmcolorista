<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Ciclo;
use App\Util\RuleManager;
use App\Util\ResultManager;
use Illuminate\Http\Request;
use App\Util\LogErrorManager;
use App\Models\DetalleMatricula;
use Illuminate\Database\QueryException;

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

        $result ="";
        $errorMsg='No se puede eliminar. Matricula hace uso de este Modulo.';

        try {
            $numberDependents = DetalleMatricula::where(['id'=>$id, 'estado'=> 'A'])->count();

            if($numberDependents==0){

                $ciclo = Ciclo::findOrFail($id);
                $ciclo->estado = RuleManager::DISABLED_STATE;
                $ciclo->update();

                $result = ResultManager::successMessage('Ciclo eliminado correctamente.');
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

    private function setModel(Ciclo $ciclo, Request $request): Ciclo
    {
        $ciclo->nombre = $request->nombre;
        $ciclo->duracion = $request->duracion;
        $ciclo->preciomes = $request->preciomes;
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
