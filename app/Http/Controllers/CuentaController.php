<?php

namespace App\Http\Controllers;

use App\Models\Campania;
use App\User;
use App\Models\Cuenta;
use App\Util\LogErrorManager;
use App\Util\ResultManager;
use App\Util\RuleManager;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CuentaController extends BaseController
{
    // public function __construct()
    // {
    //     //parent::__construct();
    // }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $this->getFilters($request, new Cuenta());
        $perpage = $this->getLimitPagination($request);

        $queryCuentas = Cuenta::where($filters);

        if ($request->exists('fechadesde')){
            $fechadesde = Carbon::createFromFormat('d-m-Y', $request->fechadesde)->toDateString();
            $queryCuentas->whereDate('fechainicio','>=', $fechadesde);
        }

        if ($request->exists('fechahasta')) {
            $fechahasta = Carbon::createFromFormat('d-m-Y', $request->fechahasta)->toDateString();           
            $queryCuentas->whereDate('fechainicio','<=', $fechahasta);
        }

        $cuentas = $queryCuentas-> orderBy('idcuenta', 'DESC')
            ->paginate($perpage);

        return $cuentas;
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

            $cuenta = $this->setModel(new Cuenta(), $request);
            $cuenta->userinsert = $this->user->email;
            $cuenta->save();

            $result = ResultManager::genericSuccessMessage();
        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);

            $result = ResultManager::errorMessage('Es posible que hay una cuenta registrado con el mismo nombre.');

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
        return Cuenta::find($id);
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

            $cuenta = $this->setModel(Cuenta::findOrFail($id), $request);
            $cuenta->userupdate = $this->user->email;
            $cuenta->update();

            $result = ResultManager::genericSuccessMessage();
        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);

            $result = ResultManager::errorMessage('Es posible que hay una cuenta registrado con el mismo nombre.');

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
        $errorMsg = 'No se puede eliminar. hay usuarios que hacen uso de esta Cuenta';

        try {
            $numberDependents = User::where(['idcuenta' => $id, 'estado' => RuleManager::ACTIVE_STATE])->count();

            if ($numberDependents == 0) {

                $cuenta = Cuenta::findOrFail($id);
                $cuenta->estado = RuleManager::DISABLED_STATE;
                $cuenta->userupdate = $this->user->email;
                $cuenta->update();

                $result = ResultManager::successMessage('Cuenta eliminado correctamente.');
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

    private function setModel(Cuenta $cuenta, Request $request): Cuenta
    {

        $cuenta->empresa = $request->empresa;
        $cuenta->ruc = $request->ruc;
        $cuenta->razonsocial = $request->razonsocial;
        $cuenta->contrato = $request->contrato;
        $cuenta->fechainicio = Carbon::createFromFormat('d-m-Y', $request->fechainicio);
        $cuenta->mensual = $request->mensual;

        return $cuenta;
    }


    public function select(Request $request)
    {
        $filters = $this->getFilters($request, new Cuenta());

        $cuentas = Cuenta::where($filters)
            -> orderBy('idcuenta', 'DESC')
            ->get();

        return $cuentas;
    }

    public function getecampania(Request $request){
        $query = Campania::where('idcuenta', $request->idcuenta)->get();
        return $query;
        /* return dd('obtener evento',$request->idcampania); */
    }
}
