<?php

namespace App\Http\Controllers;

use App\Models\Campania;
use App\Models\Cliente;
use App\Models\Evento;
use App\Util\LogErrorManager;
use App\Util\ResultManager;
use App\Util\RuleManager;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CampaniaController extends BaseController
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
        $filters = $this->getFilters($request, new Campania());
        $perpage = $this->getLimitPagination($request);

        $querycampanias = Campania::where($filters)
            ->with('evento','cuenta');

        if ($this->user->idcuenta != RuleManager::SYSTEM_COMPANY_ACCOUNT || !in_array($this->user->idrol, RuleManager::ADMINISTRATORS_ACCESS)) {
            //filtro mostrar campania  que pertenecen a una cuenta
            $querycampanias->where('idcuenta', '=', $this->user->idcuenta);
        }

        if ($request->exists('fechadesde')) {
            $fechadesde = Carbon::createFromFormat('d-m-Y', $request->fechadesde)->toDateString();
            $querycampanias->whereDate('fechainicio', '>=', $fechadesde);
        }

        if ($request->exists('fechahasta')) {
            $fechahasta = Carbon::createFromFormat('d-m-Y', $request->fechahasta)->toDateString();
            $querycampanias->whereDate('fechainicio', '<=', $fechahasta);
        }

        $campanias = $querycampanias->orderBy('idcampania', 'DESC')
            ->paginate($perpage);

        return $campanias;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result;
        try {

            $campania = $this->setModel(new Campania(), $request);
            $campania->userinsert = $this->user->email;
            $campania->save();

            $result = ResultManager::genericSuccessMessage();
        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);

            $result = ResultManager::errorMessage('Es posible que hay una Campaña registrado con el mismo nombre.');

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);

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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Campania::find($id);
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
        $result;
        try {

            $campania = $this->setModel(Campania::findOrFail($id), $request);
            $campania->userupdate = $this->user->email;
            $campania->update();

            $result = ResultManager::genericSuccessMessage();
        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);

            $result = ResultManager::errorMessage('Es posible que hay una Campaña registrado con el mismo nombre.');

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);

            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result;
        $errorMsg = 'No se puede eliminar. Hay Eventos que hacen uso de esta Campaña.';

        try {

            $numberDependents = Evento::where(['idcampania' => $id, 'estado' => RuleManager::ACTIVE_STATE])->count();
            if ($numberDependents == 0) {

                $campania = Campania::findOrFail($id);
                $campania->estado = RuleManager::DISABLED_STATE;
                $campania->userupdate = $this->user->email;
                $campania->update();

                $result = ResultManager::successMessage('Campaña eliminado correctamente.');
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

    private function setModel(Campania $campania, Request $request): Campania
    {

        $campania->nombrecampania = $request->nombrecampania;
        $campania->fechainicio = Carbon::createFromFormat('d-m-Y H:i:s', $request->fechainicio);
        $campania->fechafin = Carbon::createFromFormat('d-m-Y H:i:s', $request->fechafin);
        $campania->idcuenta = $request->idcuenta;

        return $campania;
    }

    public function select(Request $request)
    {
        $filters = $this->getFilters($request, new Campania());

        $querycampanias = Campania::where($filters);

        if ($this->user->idcuenta != RuleManager::SYSTEM_COMPANY_ACCOUNT || !in_array($this->user->idrol, RuleManager::ADMINISTRATORS_ACCESS)) {
            //filtro mostrar campania  que pertenecen a una cuenta
            $querycampanias->where('idcuenta', '=', $this->user->idcuenta);
        }

        $campanias = $querycampanias
            /*->where('idcampania','=',10)*/
            ->orderBy('idcampania', 'DESC')
            ->get();

        return $campanias;
    }
}
