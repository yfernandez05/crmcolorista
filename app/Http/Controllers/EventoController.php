<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Evento;
use App\Models\EventoCliente;
use App\Util\LogErrorManager;
use App\Util\ResultManager;
use App\Util\RuleManager;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class EventoController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $this->getFilters($request, new Evento());
        $perpage = $this->getLimitPagination($request);

        $queryevento = Evento::where($filters)
            ->with('campania');

        /* if ($this->user->idcuenta != RuleManager::SYSTEM_COMPANY_ACCOUNT || !in_array($this->user->idrol, RuleManager::ADMINISTRATORS_ACCESS)) {
            //filtro mostrar campania  que pertenecen a una cuenta
            $queryevento->where('idcampania', '=', $this->user->idcuenta);
        } */

        if ($request->exists('fechadesde')) {
            $fechadesde = Carbon::createFromFormat('d-m-Y', $request->fechadesde)->toDateString();
            $queryevento->whereDate('fechainicio', '>=', $fechadesde);
        }

        if ($request->exists('fechahasta')) {
            $fechahasta = Carbon::createFromFormat('d-m-Y', $request->fechahasta)->toDateString();
            $queryevento->whereDate('fechainicio', '<=', $fechahasta);
        }

        $eventos = $queryevento->orderBy('idevento', 'DESC')
            ->paginate($perpage);

        return $eventos;
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
            
            $evento = $this->setModel(new Evento(), $request);
            $evento->userinsert = $this->user->email;
            $evento->save();

            $result = ResultManager::genericSuccessMessage();

        } catch (QueryException $e) {

            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::errorMessage('Es posible que hay un Stand registrado con el mismo nombre.');

        } catch (Exception $e) {

            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();

        }

        return $result;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Evento::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = "";
        try {

            $evento = $this->setModel(Evento::findOrFail($id), $request);
            $evento->userupdate = $this->user->email;
            $evento->update();

            $result = ResultManager::genericSuccessMessage();

        } catch (QueryException $e) {

            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::errorMessage('Es posible que hay un Stand registrado con el mismo nombre.');

        } catch (Exception $e) {

            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();
            
        }

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = "";
        $errorMsg = 'No se puede eliminar. Hay Clientes que hacen uso de este Stand.';

        try {

            $numberDependents = EventoCliente::where(['idevento' => $id, 'estado' => RuleManager::ACTIVE_STATE])->count();
            if ($numberDependents == 0) {

                $evento = Evento::findOrFail($id);
                $evento->estado = $evento->estado == RuleManager::DISABLED_STATE ? RuleManager::ACTIVE_STATE : RuleManager::DISABLED_STATE;
                $evento->userupdate = $this->user->email;
                $evento->update();

                if ($evento->estado == RuleManager::ACTIVE_STATE) {
                    $result = ResultManager::successMessage('Stand activado correctamente.');
                }else if($evento->estado == RuleManager::DISABLED_STATE) {
                    $result = ResultManager::warningMessage('Stand eliminado correctamente.');
                }

            } else {
                $result = ResultManager::errorMessage($errorMsg);
            }

        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::errorMessage($errorMsg);

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    private function setModel(Evento $evento, Request $request): Evento
    {
        $evento->nombreevento = $request->nombreevento;
        $evento->fechainicio = Carbon::createFromFormat('d-m-Y H:i:s', $request->fechainicio);
        $evento->fechafin = Carbon::createFromFormat('d-m-Y H:i:s', $request->fechafin);
        $evento->idcampania = $request->idcampania;

        return $evento;
    }

    public function getevento(Request $request){
        $evento = Evento::where('idcampania', $request->idcampania)->get();
        return $evento;
        /* return dd('obtener evento',$request->idcampania); */
    }

    public function select(Request $request)
    {
        $filters = $this->getFilters($request, new Evento());

        $querycampanias = Evento::where($filters);

        $campanias = $querycampanias
            /*->where('idcampania','=',10)*/
            ->orderBy('idcampania', 'DESC')
            ->get();

        return $campanias;
    }

}
