<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Alumno;
use App\Util\RuleManager;
use App\Models\Asistencia;
use App\Util\ResultManager;
use Illuminate\Http\Request;
use App\Util\LogErrorManager;
use Illuminate\Support\Facades\DB;
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
        $perpage = $this->getLimitPagination($request) ?? 10; // Default to 10 if not provided

        $query = DB::table('alumnos as al')
            ->leftJoin('asistencias as asi', function($join) use ($request) {
                $fecha = $request->input('fecha', Carbon::now()->format('Y-m-d')); // Use today's date if not provided
                $join->on('asi.alumno_id', '=', 'al.id')
                    ->whereRaw("asi.fecha = STR_TO_DATE(?, '%Y-%m-%d')", [$fecha]);
        })
        ->select('al.*', 'asi.fecha', DB::raw("IF(ISNULL(asi.fecha), 'Pendiente', 'Asistencia Marcada') as Asistencia"))
        ->where('al.estado', 'A');

            // Apply additional filters
            if ($request->has('nombre')) {
                $query->where('al.nombre', 'like', '%' . $request->input('nombre') . '%');
            }
            if ($request->has('apellido')) {
                $query->where('al.apellido', 'like', '%' . $request->input('apellido') . '%');
            }
            if ($request->has('curso')) {
                $query->where('al.curso', 'like', '%' . $request->input('curso') . '%');
            }

            $asistencia = $query->orderBy('al.id', 'DESC')
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
                'fecha' => $request->fecha,

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
        $asistencias = Asistencia::where('alumno_id', $alumno_id)
        ->orderBy('fecha_asistencia', 'DESC')
        ->get();
        return response()->json(['asistencias' => $asistencias]);
    }

    public function select(Request $request)
    {
        // $filters = $this->getFilters($request, new Asistencia());

        // $queryasistencia = Asistencia::where($filters);

        // // Agregar filtro para la fecha de hoy
        // $hoy = date('Y-m-d');
        // $queryasistencia->whereDate('fecha_asistencia', $hoy);

        // $asistencia = $queryasistencia->orderBy('id', 'DESC')->get();
        // return $asistencia;




    }

}
