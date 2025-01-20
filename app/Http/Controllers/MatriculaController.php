<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Matricula;
use App\Util\LogErrorManager;
use App\Util\ResultManager;
use App\Util\RuleManager;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class MatriculaController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $this->getFilters($request, new Matricula());
        $perpage = $this->getLimitPagination($request);

        $query = Matricula::where($filters)
        ->with('user','carrera','alumno','ciclo','turno');

        if ($request->exists('fechadesde')) {
            $fechadesde = Carbon::createFromFormat('d-m-Y', $request->fechadesde)->toDateString();
            $query->whereDate('fecha', '>=', $fechadesde);
        }

        if ($request->exists('fechahasta')) {
            $fechahasta = Carbon::createFromFormat('d-m-Y', $request->fechahasta)->toDateString();
            $query->whereDate('fecha', '<=', $fechahasta);
        }

        $matricula = $query-> orderBy('id', 'DESC')
            ->paginate($perpage);

        return $matricula;
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

            $matricula = $this->setModel(new Matricula(), $request);
            $matricula->created_usr = $this->user->email;
            $matricula->save();

            $result = ResultManager::genericSuccessMessage();
        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            dd($e);
            $result = ResultManager::errorMessage('Es posible que hay un matricula registrado con el mismo correo.');

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
     * @param  \App\Alumno  $alumno
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Matricula::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Alumno  $alumno
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = "";
        try {

            $matricula = $this->setModel(Matricula::findOrFail($id), $request);
            $matricula->updated_usr = $this->user->email;
            $matricula->update();

            $result = ResultManager::genericSuccessMessage();
        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::errorMessage('Es posible que hay un matricula registrada con el mismo correo.');

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Alumno  $alumno
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = "";

        try {

            $matricula = Matricula::findOrFail($id);
            $matricula->estado = RuleManager::DISABLED_STATE;
            $matricula->updated_usr = $this->user->email;
            $matricula->update();

            $result = ResultManager::successMessage('matricula eliminado correctamente.');

        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    private function setModel(Matricula $matricula, Request $request): Matricula
    {

        $matricula->detalle = $request->detalle;
        $matricula->fecha = Carbon::createFromFormat('d-m-Y', $request->fecha);
        $matricula->ciclo_id = $request->ciclo_id;
        //$matricula->periodo_id = $request->periodo_id;
        //$matricula->condicion_id = $request->condicion_id;
        $matricula->turno_id = $request->turno_id;
        $matricula->alumno_id = $request->alumno_id;
        $matricula->carrera_id = $request->carrera_id;
        $matricula->user_id = Auth()->user()->id;

        return $matricula;
    }

    public function select(Request $request)
    {
        $filters = $this->getFilters($request, new Matricula());

        $matricula = Matricula::where($filters)
            ->orderBy('id', 'DESC')
            ->get();

        return $matricula;
    }

    public function selectsearch(Request $request)
    {
        $search = $request->input('search');

        $query = Matricula::where('estado', 'A')
            ->with('alumno','carrera','ciclo')
            ->whereHas('alumno', function ($query) use ($search) {
                $query->whereRaw("concat(dni, ' ', nombre, ' ', apellido) like ?", "%{$search}%");
            })
            ->select('id', 'detalle', 'ciclo_id','turno_id', 'alumno_id', 'carrera_id')
            ->orderBy('alumno_id', 'ASC')
            //->limit(15)
            ->get();

        return $query;
    }

}
