<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use App\Models\DocenteCurso;
use App\Util\LogErrorManager;
use App\Util\ResultManager;
use App\Util\RuleManager;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocenteController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $this->getFilters($request, new Docente());
        $perpage = $this->getLimitPagination($request);

        $docentes = Docente::where($filters)
            ->with('user','detalles.curso')
            ->orderBy('id', 'DESC')
            ->paginate($perpage);

        return $docentes;
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

            $docente = $this->setModel(new Docente(), $request);
            $docente->created_usr  = $this->user->email;
            $docente->save();

            $docenteCursos = [];

            foreach ($request->detalles as $detalle) {
                $docentesaula = new DocenteCurso();
                $docentesaula->docente_id = $docente->docente_id;
                $docentesaula->curso_id = $detalle['curso_id'];
                $docenteCursos[] = $docentesaula;
            }

            $docente->detalles()->saveMany($docenteCursos);


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
     * @param  \App\Docente  $docente
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $planes = Docente::with('user','detalles.curso')->find($id);
        return $planes;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Docente  $docente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = "";
        DB::beginTransaction();
        try {
            $docente = $this->setModel(Docente::findOrFail($id), $request);
            $docente->updated_usr = $this->user->email;
            $docente->update();

            $detallesExistentes = $docente->detalles()->get();

            $detallesRecibidosIds = collect($request->detalles)->pluck('id')->filter();

            // Eliminar los detalles que no están en la solicitud (detalles que fueron eliminados)
            foreach ($detallesExistentes as $detalleExistente) {
                if (!in_array($detalleExistente->id, $detallesRecibidosIds->toArray())) {
                    // Eliminar el detalle que no está en la solicitud
                    $detalleExistente->delete();
                }
            }

            foreach ($request->detalles as $detalle) {
                // updateOrCreate actualizar o crear 
                DocenteCurso::updateOrCreate(
                    ['id' => $detalle['id'] ?? null],
                    [
                        'curso_id' => $detalle['curso_id'],
                        'docente_id' => $docente->id,
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
     * @param  \App\Docente  $docente
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = "";
        try {

            $docente = Docente::findOrFail($id);
            $docente->estado = RuleManager::DISABLED_STATE;
            $docente->update();

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

    private function setModel(Docente $docente, Request $request): Docente
    {
        $docente->usuario_id = $request->usuario_id;
        return $docente;
    }

}
