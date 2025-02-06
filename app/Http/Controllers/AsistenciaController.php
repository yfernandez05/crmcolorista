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

    public function selectsearch(Request $request)
    {
        $search = $request->input('search');
        $fecha = $request->input('fecha', Carbon::now()->format('Y-m-d'));

        $query = DB::table('alumnos as al')
        ->leftJoin('asistencias as asi', function ($join) use ($fecha) {
            $join->on('asi.alumno_id', '=', 'al.id')
                ->whereRaw("asi.fecha = STR_TO_DATE(?, '%Y-%m-%d')", [$fecha]);
        })
        ->select(
            'al.id',
            'al.correo',
            'al.nombre',
            'al.apellido',
            'al.dni',
            'al.fecha_inscripcion',
            'asi.fecha as asistencia_fecha',
            DB::raw("CONCAT(al.nombre, ' ', al.apellido) as nombrecompleto"),
            DB::raw("IF(ISNULL(asi.fecha), 'Pendiente', 'Asistencia Marcada') as asistencianame"),
            DB::raw("IF(ISNULL(asi.fecha), 0, 1) as isassistance")
        )
        ->where('al.estado', 'A')
        ->whereRaw("concat(al.nombre, ' ', al.apellido, ' ', al.dni) like ?", ["%{$search}%"])
        ->orderBy('al.id', 'DESC')
        ->limit(15) // Limita los resultados a 15 para evitar sobrecarga
        ->get()
        ->map(function ($item) {
            $item->isassistance = (int) $item->isassistance; // Convertir el valor a número
            return $item;
        });
    
        // Agregar matrículas y carreras para cada alumno
        $query->transform(function ($alumno) {
            $matriculas = DB::table('matriculas as m')
                ->join('carreras as c', 'm.carrera_id', '=', 'c.id')
                ->join('ciclos as ci', 'm.ciclo_id', '=', 'ci.id')
                ->select(
                    'm.id as matricula_id',
                    'm.detalle as detalle_matricula',
                    'm.fecha as fecha_matricula',
                    'c.nombre as nombre_carrera',
                    'ci.nombre as nombre_ciclo'
                )
                ->where('m.alumno_id', $alumno->id)
                ->where('m.estado', 'A') 
                ->orderBy('m.fecha', 'DESC')
                ->get();
    
            // Para cada matrícula, agregar detalles relacionados
            $matriculas->transform(function ($matricula) {
                $detalles = DB::table('detalle_matriculas as dm')
                    ->select(
                        'dm.id as detalle_id',
                        'dm.nombre as detalle_nombre',
                        'dm.fechapago as detalle_fechapago',
                        'dm.pagado as detalle_pagado'
                    )
                    ->where('dm.matricula_id', $matricula->matricula_id)
                    ->get();
                $matricula->detalles = $detalles;
    
                return $matricula;
            });

            $alumno->matriculas = $matriculas;
    
            return $alumno;
        });
    
        return $query;
    }

}
