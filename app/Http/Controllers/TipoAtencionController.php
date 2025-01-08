<?php

namespace App\Http\Controllers;
use App\Models\Atencion;
use App\Models\TipoAtencion;
use App\Util\ResultManager;
use App\Util\RuleManager;
use App\Util\LogErrorManager;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TipoAtencionController extends BaseController
{

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $this->getFilters($request, new TipoAtencion());
        $perpage = $this->getLimitPagination($request);

        $tipoatencions = TipoAtencion::where($filters)
            ->where('idtipoatencion','>=',RuleManager::SYSTEM_ADMIN_ROLE)
            ->orderBy('idtipoatencion','DESC')
            ->paginate($perpage);

        return $tipoatencions;
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
            $tipoAtencion = $this->setModel(new TipoAtencion(),$request);
            $tipoAtencion->save();

            $result = ResultManager::genericSuccessMessage();
        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this,__FUNCTION__, $e);

            $result = ResultManager::errorMessage('Es posible que haya un Tipo de Atencion registrado con el mismo nombre.');
        
        } catch (Exception $e){
            LogErrorManager::saveInDB($this,__FUNCTION__, $e);

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
        return TipoAtencion::find($id);
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
            $tipoatencion = $this->setModel(TipoAtencion::findOrFail($id), $request);
            $tipoatencion->save();

            $result = ResultManager::genericSuccessMessage();
        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);

            $result = ResultManager::errorMessage('Es posible que haya un Tipo de Atencion registrado con el mismo nombre.');

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
        $errorMsg='No se puede eliminar. Hay una Atencion que hace uso de este Tipo de Atencion.';
        try {
        $numberDependents = Atencion::where(['idtipoatencion'=>$id,'estado'=> RuleManager::ACTIVE_STATE])->count();

         if($numberDependents == 0){

            $tipoatencion=TipoAtencion::findOrFail($id);
            $tipoatencion->estado=RuleManager::DISABLED_STATE;
            $tipoatencion->update();

            $result = ResultManager::successMessage('Tipo de Atencion eliminado correctamente.');
         }else{
             $result=ResultManager::errorMessage($errorMsg);
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

    private function setModel(TipoAtencion $tipoatencion, Request $request): TipoAtencion 
    {
        $tipoatencion->tipoatencion = $request->tipoatencion;
        $tipoatencion->backgroundColor = $request->backgroundColor;
        $tipoatencion->textColor = $request->textColor;
        return $tipoatencion;
    }

    public function select(Request $request)
    {
        $filters = $this->getFilters($request, new TipoAtencion());

        $queryTipoatenciones = TipoAtencion::where($filters);
                   
        if ($request->exists('istosave')){
            $queryTipoatenciones->where('idtipoatencion','>',RuleManager::UNATTENDED_STATE_TYPE);
        }

        $tipoatencions = $queryTipoatenciones->orderBy('idtipoatencion', 'DESC')
            ->get();
        return $tipoatencions;

    }

}
