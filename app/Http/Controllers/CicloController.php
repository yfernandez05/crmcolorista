<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Ciclo;
use App\Models\DetalleCiclo;
use App\Util\RuleManager;
use App\Util\ResultManager;
use Illuminate\Http\Request;
use App\Util\LogErrorManager;
use App\Models\DetalleMatricula;
use App\Models\Matricula;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

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
        DB::beginTransaction();
        try {

            $ciclo = $this->setModel(new Ciclo(), $request);
            $ciclo->created_usr  = $this->user->email;
            $ciclo->save();

            $detalleCiclos = [];

            foreach ($request->detalles as $detalle) {
                $detalleCiclo = new DetalleCiclo();
                $detalleCiclo->ciclo_id = $ciclo->id;
                $detalleCiclo->carrera_id = $detalle['carrera_id'];
                $detalleCiclos[] = $detalleCiclo;
            }

            $ciclo->detalles()->saveMany($detalleCiclos);

            DB::commit();

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
        return Ciclo::with('detalles')->find($id);
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
        DB::beginTransaction();
        try {

            $ciclo = $this->setModel(Ciclo::findOrFail($id), $request);
            $ciclo->created_usr = $this->user->email;
            $ciclo->update();

            $detallesExistentes = $ciclo->detalles()->get();

            $detallesRecibidosIds = collect($request->detalles)->pluck('id')->filter();

            // Eliminar los detalles que no están en la solicitud (detalles que fueron eliminados)
            foreach ($detallesExistentes as $detalleExistente) {
                if (!in_array($detalleExistente->id, $detallesRecibidosIds->toArray())) {
                    $detalleExistente->delete();
                }
            }

            // Ccrea los detalles
            foreach ($request->detalles as $detalle) {
                // updateOrCreate actualizar o crear el detalle
                DetalleCiclo::updateOrCreate(
                    ['id' => $detalle['id'] ?? null], // Si existe 'id', actualiza; si no, crea un nuevo detalle
                    [
                        'ciclo_id' => $ciclo->id,
                        'carrera_id' => $detalle['carrera_id'],
                    ]
                );
            }

            DB::commit();
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
            $numberDependents = Matricula::where(['ciclo_id'=>$id, 'estado'=> 'A'])->count();

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
            dd($e);
            $result = ResultManager::errorMessage($errorMsg);

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            dd($e);
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

    public function selectFilter(Request $request)
    {
        $ciclos = Ciclo::with('detalles')->where('estado','=','A')
        ->whereHas('detalles', function ($query) use ($request) {
            $query->where('carrera_id', $request->carrera_id);
        })
        ->get();

        return $ciclos;
    }
}
