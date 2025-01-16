<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Prospecto;
use App\Util\RuleManager;
use App\Util\ResultManager;
use Illuminate\Http\Request;
use App\Util\LogErrorManager;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\QueryException;

class ProspectoController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $this->getFilters($request, new Prospecto());
        $perpage = $this->getLimitPagination($request);

        $query = Prospecto::where($filters);


        $prospectos = $query-> orderBy('id', 'DESC')
            ->paginate($perpage);

        return $prospectos;
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

            $prospecto = $this->setModel(new Prospecto(), $request);
            $prospecto->created_usr = $this->user->email;
            $prospecto->save();

            $result = ResultManager::genericSuccessMessage();
        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            dd($e);
            $result = ResultManager::errorMessage('Es posible que hay un Prospecto registrado con el mismo correo.');

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
     * @param  \App\Prospecto  $alumno
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Prospecto::find($id);
    }


 public function edit($id)
    {
        return Prospecto::find($id);
       // return Cliente::with('ubicaciones')->find($id);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Prospecto  $alumno
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = "";
        try {

            $prospecto = $this->setModel(Prospecto::findOrFail($id), $request);
            $prospecto->updated_usr = $this->user->email;
            $prospecto->update();

            $result = ResultManager::genericSuccessMessage();
        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::errorMessage('Es posible que hay un Prospecto registrada con el mismo correo.');

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Prospecto  $alumno
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = "";

        try {

            $prospecto = Prospecto::findOrFail($id);
            $prospecto->estado = RuleManager::DISABLED_STATE;
            $prospecto->updated_usr = $this->user->email;
            $prospecto->update();

            $result = ResultManager::successMessage('Prospecto eliminado correctamente.');

        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    private function setModel(Prospecto $prospecto, Request $request): Prospecto
    {
        $prospecto->nombre = $request->nombre;
        $prospecto->apellido = $request->apellido;
        $prospecto->correo = $request->correo;
        $prospecto->telefono = $request->telefono;
        $prospecto->procedencia = $request->procedencia;
        $prospecto->fecha_registro = Carbon::now();
        $prospecto->user_id = 1;
        if (!is_null($request->fecha_nac)) {
            $prospecto->fecha_nac = Carbon::createFromFormat('d-m-Y', $request->fecha_nac);
        }

        return $prospecto;
    }

    public function select(Request $request)
    {
        $filters = $this->getFilters($request, new Prospecto());

        $queryprospecto = Prospecto::where($filters);

        $prospecto = $queryprospecto->orderBy('id', 'DESC')
            ->get();
        return $prospecto;

    }
}
