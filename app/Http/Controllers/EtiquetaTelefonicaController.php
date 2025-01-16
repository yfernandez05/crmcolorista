<?php

namespace App\Http\Controllers;

use App\Util\RuleManager;
use App\Util\ResultManager;
use App\Models\Etiquetatele;
use Illuminate\Http\Request;
use App\Util\LogErrorManager;
use Illuminate\Database\QueryException;

class EtiquetaTelefonicaController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $this->getFilters($request, new Etiquetatele(), []);
        $perpage = $this->getLimitPagination($request);

        $etiquetatelefonica = Etiquetatele::where($filters)
            ->where('idetiquetatele','>=',RuleManager::SYSTEM_ADMIN_ROLE)
            ->orderBy('idetiquetatele','DESC')
            ->paginate($perpage);

        return $etiquetatelefonica;
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
            $etiquetatelefonica = $this->setModel(new Etiquetatele(),$request);
            $etiquetatelefonica->save();

            $result = ResultManager::genericSuccessMessage();
        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this,__FUNCTION__, $e);

            $result = ResultManager::errorMessage('Es posible que haya un Etiqueta Telefonica registrado con el mismo nombre.');

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
        return Etiquetatele::find($id);
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
            $etiquetatelefonica = $this->setModel(Etiquetatele::findOrFail($id), $request);
            $etiquetatelefonica->save();

            $result = ResultManager::genericSuccessMessage();
        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);

            $result = ResultManager::errorMessage('Es posible que haya un Etiqueta Telefonica registrado con el mismo nombre.');

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
        try {
            $etiquetatelefonica = Etiquetatele::find($id);
            $etiquetatelefonica->estado = $etiquetatelefonica->estado == RuleManager::DISABLED_STATE ? RuleManager::ACTIVE_STATE : RuleManager::DISABLED_STATE;

            $etiquetatelefonica->update();

            $result = ResultManager::successMessage('Etiqueta telefonica ' . RuleManager::getStateName($etiquetatelefonica->estado). ' correctamente');

        } catch (QueryException $e) {
            $result = ResultManager::gerericErrorMessage();
        } catch (Exception $e) {
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }
    private function setModel(Etiquetatele $etiquetatelefonica, Request $request): Etiquetatele
    {
        $etiquetatelefonica->etiquetatele = $request->etiquetatele;
        $etiquetatelefonica->backgroundColor = $request->backgroundColor;
        $etiquetatelefonica->textColor = $request->textColor;

        return $etiquetatelefonica;
    }

    public function select(Request $request)
    {
        $filters = $this->getFilters($request, new Etiquetatele());

        $queryEtiquetatelefonicas = Etiquetatele::where($filters);

        if ($request->exists('istosave')){
            $queryEtiquetatelefonicas->where('idetiquetatele','>',RuleManager::UNATTENDED_STATE_TYPE);
        }


        $etiquetatele = $queryEtiquetatelefonicas->orderBy('idetiquetatele', 'DESC')
            ->get();
        return $etiquetatele;

    }
}
