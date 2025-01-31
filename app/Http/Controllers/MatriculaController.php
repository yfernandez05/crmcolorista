<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Alumno;
use App\Models\Matricula;
use App\Util\RuleManager;
use App\Util\ResultManager;
use Illuminate\Http\Request;
use App\Util\LogErrorManager;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DetalleMatricula;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

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
        DB::beginTransaction();
        try {
            // Guardar la matrícula
            $matricula = $this->setModel(new Matricula(), $request);
            $matricula->created_usr = $this->user->email;
            $matricula->save();

            // Guardar los detalles de la matrícula
            $detalleMatriculas = [];
            foreach ($request->detalles as $detalle) {
                $detalleMatricula = new DetalleMatricula();
                $detalleMatricula->nombre = $detalle['nombre'];
                $detalleMatricula->duracion = $detalle['duracion'];
                $detalleMatricula->preciomes = $detalle['preciomes'];
                $detalleMatricula->fechapago = Carbon::createFromFormat('d-m-Y', $detalle['fechapago'])->format('Y-m-d');
                $detalleMatricula->matricula_id = $matricula->id;
                $detalleMatriculas[] = $detalleMatricula;  // Agregar al array
            }

            // Guardar todos los detalles de una vez usando saveMany
            $matricula->detalles()->saveMany($detalleMatriculas);

            DB::commit();
            $result = ResultManager::genericSuccessMessage();

        } catch (QueryException $e) {
            DB::rollBack();
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            dd($e);
            $result = ResultManager::errorMessage('Es posible que haya una matrícula registrada con el mismo correo.');

        } catch (Exception $e) {
            DB::rollBack();
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
        $matriculas = Matricula::with('detalles')->find($id);
        return $matriculas;
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
        DB::beginTransaction();
        try {

            // Obtener la matrícula a actualizar
            $matricula = Matricula::findOrFail($id);
            $matricula->updated_usr = $this->user->email;
            $matricula->detalle = $request->detalle;
            $matricula->fecha = Carbon::createFromFormat('d-m-Y', $request->fecha);
            $matricula->alumno_id = $request->alumno_id;
            $matricula->carrera_id = $request->carrera_id;
            $matricula->ciclo_id = $request->ciclo_id;
            $matricula->turno_id = $request->turno_id;
            $matricula->save();

            // Eliminar los detalles existentes
            DetalleMatricula::where('matricula_id', $matricula->id)->delete();

            // Ahora creamos los nuevos detalles
            foreach ($request->detalles as $detalle) {
                $detalleMatricula = new DetalleMatricula();
                $detalleMatricula->nombre = $detalle['nombre'];
                $detalleMatricula->duracion = $detalle['duracion'];
                $detalleMatricula->preciomes = $detalle['preciomes'];
                $detalleMatricula->fechapago = Carbon::createFromFormat('d-m-Y', $detalle['fechapago'])->format('Y-m-d');
                $detalleMatricula->matricula_id = $matricula->id;
                $detalleMatricula->save();
            }

            // Confirmar la transacción si todo fue exitoso
            DB::commit();
            $result = ResultManager::genericSuccessMessage();
        } catch (QueryException $e) {
            DB::rollBack(); // Revertir en caso de error
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::errorMessage('Es posible que haya una matrícula registrada con el mismo correo.');
        } catch (Exception $e) {
            DB::rollBack(); // Revertir en caso de error
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
        //dd($search);

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
