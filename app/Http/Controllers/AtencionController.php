<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Atencion;
use App\Models\Prospecto;
use App\Util\RuleManager;
use App\Models\Institucion;
use App\Util\ResultManager;
use Illuminate\Http\Request;
use App\Util\LogErrorManager;
use Illuminate\Database\QueryException;

class AtencionController extends BaseController
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

        $queryAlumnos = Prospecto::where($filters)
        ->with('ultimaatencion');

        $Alumnos = $queryAlumnos->orderBy('id', 'DESC')
            ->paginate($perpage);

        return $Alumnos;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result;
        try {

            $atencion = $this->setModel(new Atencion(), $request);
            $atencion->iduser = $this->user->id;
            $atencion->userinsert = $this->user->email;

            $atencion->save();

            $result = ResultManager::genericSuccessMessage();
        } catch (QueryException $e) {
            dd($e);
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);

            $result = ResultManager::gerericErrorMessage();

        } catch (Exception $e) {
            dd($e);
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);

            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Atencion::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result;
        try {

            $atencion = $this->setModel(Atencion::findOrFail($id), $request);
            $atencion->iduser = $this->user->id;
            $atencion->userupdate = $this->user->email;

            $atencion->update();

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $result;
        try {
            $atencion = Atencion::find($id);
            $atencion->estado = RuleManager::DISABLED_STATE;

            $atencion->update();

            $result = ResultManager::successMessage('Atencion ' . RuleManager::getStateName($atencion->estado). ' correctamente');
        } catch (QueryException $e) {
            $result = ResultManager::gerericErrorMessage();
        } catch (Exception $e) {
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    public function detail($id)
    {
        return Atencion::where('prospecto_id', $id)
        ->with('tipoatencion')
        ->with('etiquetatelefonica')
        ->orderBy('idatencion', 'DESC')->get();
    }

    private function setModel(Atencion $atenciones, Request $request): Atencion
    {
        $atenciones->idtipoatencion = $request->idtipoatencion;
        $atenciones->idetiquetatele = $request->idetiquetatele;
        $atenciones->prospecto_id = $request->prospecto_id;
        $atenciones->fechaatencion = Carbon::createFromFormat('d-m-Y', $request->fechaatencion);
        $atenciones->comentario = $request->comentario;

        return $atenciones;
    }
}
