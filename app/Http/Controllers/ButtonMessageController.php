<?php

namespace App\Http\Controllers;

use App\Models\ButtonMessage;
use App\Util\LogErrorManager;
use App\Util\ResultManager;
use App\Util\RuleManager;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ButtonMessageController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $this->getFilters($request, new ButtonMessage());
        $perpage = $this->getLimitPagination($request);

        $query = ButtonMessage::where($filters)
            ->orderBy('idbutton','DESC')
            ->paginate($perpage);

        return $query;
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

            $query = $this->setModel(new ButtonMessage(),$request);
            $query->save();

            $result = ResultManager::genericSuccessMessage();

        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this,__FUNCTION__, $e);
            $result = ResultManager::errorMessage('Es posible que haya un boton registrado con el mismo nombre.');
        } catch (Exception $e){
            LogErrorManager::saveInDB($this,__FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();
        }
        return $result;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ButtonMessage  $buttonMessage
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return ButtonMessage::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ButtonMessage  $buttonMessage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result ="";
        try {
            $query = $this->setModel(ButtonMessage::findOrFail($id), $request);
            $query->save();

            $result = ResultManager::genericSuccessMessage();
        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::errorMessage('Es posible que haya un boton registrado con el mismo nombre.');
        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ButtonMessage  $buttonMessage
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = "";
        try {

            $query = ButtonMessage::findOrFail($id);
            $query->estado = $query->estado == RuleManager::DISABLED_STATE ? RuleManager::ACTIVE_STATE : RuleManager::DISABLED_STATE;
            $query->update();

            if ($query->estado == RuleManager::ACTIVE_STATE) {
                $result = ResultManager::successMessage('Boton restaurado correctamente.');
            } else if($query->estado == RuleManager::DISABLED_STATE) {
                $result = ResultManager::warningMessage('Boton eliminado correctamente.');
            }
            
        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    private function setModel(ButtonMessage $buttonmessage, Request $request): ButtonMessage 
    {
        $buttonmessage->name = $request->name;
        $buttonmessage->backgroundColor = $request->backgroundColor;
        $buttonmessage->textColor = $request->textColor;
        $buttonmessage->descriptionwhatsapp = $request->descriptionwhatsapp;

        return $buttonmessage;
    }

    public function select(Request $request)
    {
        $filters = $this->getFilters($request, new ButtonMessage());

        $query = ButtonMessage::where($filters)
            ->orderBy('idbutton', 'DESC')
            ->get();

        return $query;
    }
}
