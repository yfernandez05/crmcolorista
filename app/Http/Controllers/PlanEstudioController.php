<?php

namespace App\Http\Controllers;

use Exception;
use App\Util\RuleManager;
use App\Models\PlanEstudio;
use App\Util\ResultManager;
use Illuminate\Http\Request;
use App\Models\DetallePlanes;
use App\Util\LogErrorManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class PlanEstudioController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $this->getFilters($request, new PlanEstudio());
        $perpage = $this->getLimitPagination($request);

        $cursos = PlanEstudio::where($filters)
            ->with('carrera','ciclo')
            ->orderBy('id', 'DESC')
            ->paginate($perpage);

        return $cursos;
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

            $planEstudio = $this->setModel(new PlanEstudio(), $request);
            $planEstudio->created_usr  = $this->user->email;
            $planEstudio->save();

            //details
            $detallePlanEstudios = [];


            foreach ($request->detalles as $detalle) {
                $detallePlanEstudio = new DetallePlanes();
                $detallePlanEstudio->curso_id = $detalle['curso_id'];
               // $detallePlanEstudio->updated_at = $this->user->email;

                $detallePlanEstudio->plan_id = $planEstudio->id;
                $detallePlanEstudios[] = $detallePlanEstudio;  // Add to array
            }

            // Save all details at once using saveMany
            $planEstudio->detalles()->saveMany($detallePlanEstudios);


            DB::commit();
            $result = ResultManager::genericSuccessMessage();

        } catch (QueryException $e) {
            DB::rollBack();
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            dd($e);
            $result = ResultManager::gerericErrorMessage();

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
     * @param  \App\PlanEstudio  $planEstudio
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $planes = PlanEstudio::with('carrera','ciclo','detalles')->find($id);
        return $planes;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PlanEstudio  $planEstudio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = "";
        DB::beginTransaction();
        try {
            // Obtener el plan a actualizar
            $plan = PlanEstudio::findOrFail($id);
            $plan->updated_usr = $this->user->email;
            $plan->carrera_id = $request->carrera_id;
            $plan->ciclo_id = $request->ciclo_id;
            $plan->update();

            // Obtener todos los detalles actuales de este plan
            $detallesExistentes = $plan->detalles()->get();

            // Obtener los IDs de los detalles enviados en la solicitud
            $detallesRecibidosIds = collect($request->detalles)->pluck('id')->filter();

            // Eliminar los detalles que no están en la solicitud (detalles que fueron eliminados)
            foreach ($detallesExistentes as $detalleExistente) {
                if (!in_array($detalleExistente->id, $detallesRecibidosIds->toArray())) {
                    // Eliminar el detalle que no está en la solicitud
                    $detalleExistente->delete();
                }
            }

            // Ahora actualizamos o creamos los detalles
            foreach ($request->detalles as $detalle) {
                // Usamos updateOrCreate para actualizar o crear el detalle
                DetallePlanes::updateOrCreate(
                    ['id' => $detalle['id'] ?? null], // Si existe 'id', actualiza; si no, crea un nuevo detalle
                    [
                        'curso_id' => $detalle['curso_id'],
                        'updated_usr' => $this->user->email,
                        'plan_id' => $plan->id, // Relacionar con el plan actualizado
                    ]
                );
            }

            // Confirmar la transacción si todo fue exitoso
            DB::commit();
            $result = ResultManager::genericSuccessMessage();
        } catch (QueryException $e) {
            DB::rollBack(); // Revertir en caso de error
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();
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
     * @param  \App\PlanEstudio  $planEstudio
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = "";
        try {

            $planestudio = PlanEstudio::findOrFail($id);
            $planestudio->estado = RuleManager::DISABLED_STATE;
            $planestudio->update();

            $result = ResultManager::successMessage('Plan de estudio eliminado correctamente.');

        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    private function setModel(PlanEstudio $planestudio, Request $request): PlanEstudio
    {
        $planestudio->costo = $request->costo;
        $planestudio->duracion_meses = $request->duracion_meses;
        $planestudio->carrera_id = $request->carrera_id;
        $planestudio->ciclo_id = $request->ciclo_id;
        return $planestudio;
    }

}
