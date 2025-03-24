<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Asistencia;
use App\Models\AsistenciaClase;
use App\Models\Curso;
use App\Models\Docente;
use App\Models\DocenteCurso;
use App\User;
use App\Util\LogErrorManager;
use App\Util\ResultManager;
use App\Util\RuleManager;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class AsistenciaDocenteAlumno extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $this->getFilters($request, new Alumno());
        $usuarioId = auth()->user()->id; // ID del usuario autenticado

        $perpage = $this->getLimitPagination($request);
        $cursoId = $request->curso_id;
        $hoy = Carbon::today();

        $esAdministrador = in_array($usuarioId, RuleManager::ADMINISTRATORS_ACCESS);

        if (!$cursoId) {
            return ResultManager::warningMessage('Seleccione un CURSO');
        }

        $cursosProfesor = collect([$cursoId]);

        // Obtener alumnos que tienen matrícula en el curso seleccionado
        $alumnos = Alumno::where($filters)
            ->whereHas('matriculas', function ($query) use ($cursosProfesor) {
                $query->whereHas('carrera.planEstudios.detalles', function ($query) use ($cursosProfesor) {
                    $query->whereIn('curso_id', $cursosProfesor);
                });
            })
            ->with([
                'matriculas' => function ($query) use ($cursosProfesor) {
                    $query->whereHas('carrera.planEstudios.detalles', function ($query) use ($cursosProfesor) {
                        $query->whereIn('curso_id', $cursosProfesor);
                    })
                    ->with([
                        'carrera:id,nombre',
                        'carrera.planEstudios.detalles.curso:id,nombre'
                    ]);
                }
            ])
            ->select('id', 'nombre', 'apellido', 'dni')
            ->paginate($perpage);

        // Consultar asistencias de los alumnos en la fecha actual
        $queryAsistencias = AsistenciaClase::whereIn('alumno_id', $alumnos->pluck('id'))
            ->where('curso_id', $cursoId)
            //->where('docente_id', $usuarioId) // Se usa el usuario autenticado en lugar de `$docente->id`
            ->whereDate('fecha_asistencia', $hoy)
            /* ->pluck('alumno_id') */;

        if (!$esAdministrador) {
            $queryAsistencias->where('docente_id', $usuarioId);
        }
        $asistencias = $queryAsistencias->pluck('alumno_id');
            

        // Agregar campo `asistencia_hoy` a cada alumno
        $alumnos->transform(function ($alumno) use ($asistencias) {
            $alumno->asistencia_hoy = $asistencias->contains($alumno->id);
            return $alumno;
        });

        return $alumnos;
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
        $usuarioId = auth()->user()->id;
        ///return dd($usuarioId);
        try {

            // Verificar si ya existe una asistencia para el alumno en la fecha actual
            $asistenciaHoy = AsistenciaClase::where('alumno_id', $request->alumno_id)
            ->where('docente_id', $usuarioId)
            ->where('curso_id', $request->curso_id)
            ->whereDate('fecha_asistencia', Carbon::now()->toDateString())
            ->first();

            if ($asistenciaHoy) {
                return ResultManager::warningMessage('La asistencia para este alumno en este curso ya fue marcada hoy.');
            }

            AsistenciaClase::create([
                'alumno_id' => $request->alumno_id,
                'docente_id' => $usuarioId,
                'curso_id' => $request->curso_id,
                'fecha_asistencia' => Carbon::now(),
                'created_usr' => auth()->user()->email,
            ]);

            return $result = ResultManager::successMessage('Asistencia marcada correctamente.');


        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            dd($e);
            $result = ResultManager::gerericErrorMessage();

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getasistenciadetail(Request $request)
    {
        $usuarioId = auth()->id();

        if (in_array($usuarioId, RuleManager::ADMINISTRATORS_ACCESS)) {
            $asistencias = AsistenciaClase::where('alumno_id', $request->alumno_id)
            ->where('curso_id', $request->curso_id)
            ->orderByDesc('fecha_asistencia')
            ->with('user')
            ->get();

            return $asistencias;
        }

        // Obtener el docente autenticado a través de usuario_id
        $docente = Docente::where('usuario_id', $usuarioId)->first();

        // Obtener asistencias SOLO de los cursos del docente autenticado
        $asistencias = AsistenciaClase::where('alumno_id', $request->alumno_id)
            ->where('docente_id', $docente->usuario_id) // 💡 Cambié esto para usar el ID correcto
            ->where('curso_id', $request->curso_id)
            ->orderByDesc('fecha_asistencia')
            ->get();

        return $asistencias;
    }





}
