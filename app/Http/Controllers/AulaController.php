<?php

namespace App\Http\Controllers;

use App\Models\DetalleAulas;
use App\Models\Aula;
use App\Util\LogErrorManager;
use App\Util\ResultManager;
use App\Util\RuleManager;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AulaController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $this->getFilters($request, new Aula());
        $perpage = $this->getLimitPagination($request);

        $aulas = Aula::where($filters)
            ->with('carrera','detalles')
            ->orderBy('id', 'DESC')
            ->paginate($perpage);

        return $aulas;
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

            $aula = $this->setModel(new Aula(), $request);
            $aula->created_usr  = $this->user->email;
            $aula->save();

            $detalleAulas = [];

            foreach ($request->detalles as $detalle) {
                $detalleAula = new DetalleAulas();
                $detalleAula->aula_id = $aula->id;
                $detalleAula->nombre_aula = $detalle['nombre_aula'];
                $detalleAula->diapagofecha = $detalle['diapagofecha'];
                $detalleAulas[] = $detalleAula;
            }

            $aula->detalles()->saveMany($detalleAulas);


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
     * @param  \App\Aula  $aula
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $aula = Aula::with('carrera','detalles')->find($id);
        return $aula;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Aula  $aula
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   //return dd($request->detalles);
        $result = "";
        DB::beginTransaction();
        try {

            $aula = Aula::findOrFail($id);
            $aula->updated_usr = $this->user->email;
            $aula->carrera_id = $request->carrera_id;
            $aula->update();

            $detallesExistentes = $aula->detalles()->get();

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
                DetalleAulas::updateOrCreate(
                    ['id' => $detalle['id'] ?? null], // Si existe 'id', actualiza; si no, crea un nuevo detalle
                    [
                        'nombre_aula' => $detalle['nombre_aula'],
                        'foro_maximo' => $detalle['foro_maximo'],
                        'diapagofecha' => $detalle['diapagofecha'],
                        'aula_id' => $aula->id,
                    ]
                );
            }

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
     * Remove the specified resource from storage.
     *
     * @param  \App\Aula  $aula
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = "";
        try {

            $aula = Aula::findOrFail($id);
            $aula->delete(); 

            $result = ResultManager::successMessage('Aula eliminado correctamente.');

        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    private function setModel(Aula $aula, Request $request): Aula
    {
        $aula->carrera_id = $request->carrera_id;
        return $aula;
    }

    public function select(Request $request)
    {
        $query = Aula::with('carrera', 'detalles');

        $query->where('carrera_id', $request->carrera_id);

        return $query->get()->flatMap->detalles; 
    }
}
