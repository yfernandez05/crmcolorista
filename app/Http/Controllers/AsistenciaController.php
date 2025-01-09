<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Util\RuleManager;
use App\Models\Asistencia;
use App\Util\ResultManager;
use Illuminate\Http\Request;
use App\Util\LogErrorManager;
use Illuminate\Database\QueryException;

class AsistenciaController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $this->getFilters($request, new Asistencia());
        $perpage = $this->getLimitPagination($request);

        $query = Asistencia::where($filters);

        $asistencia = $query-> orderBy('id', 'DESC')
            ->paginate($perpage);

        return response()->json($asistencia);
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

            // Verificar si ya existe una asistencia para el alumno en la fecha actual
            $asistenciaHoy = Asistencia::where('alumno_id', $request->alumno_id)
            ->whereDate('fecha_asistencia', date('Y-m-d', strtotime($request->fecha_asistencia)))
            ->first();

            if ($asistenciaHoy) {
                return response()->json(['status' => false, 'message' => 'La asistencia para el alumno ya fue marcada hoy.'], 400);
            }

            $asistencia = Asistencia::create([
                'alumno_id' => $request->alumno_id,
                'fecha_asistencia' => $request->fecha_asistencia,
            ]);

            return response()->json(['status' => true, 'message' => 'Asistencia marcada correctamente.', 'data' => $asistencia], 201);

        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            dd($e);
            $result = ResultManager::errorMessage('Es posible que hay un alumno registrado con el mismo correo.');

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
        return Asistencia::find($id);
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
       //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Alumno  $alumno
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       //
    }

    public function getAsistenciasByAlumno($alumno_id)
    {
        $asistencias = Asistencia::where('alumno_id', $alumno_id)->orderBy('fecha_asistencia', 'DESC')->get();
        return response()->json(['asistencias' => $asistencias]);
    }

}
