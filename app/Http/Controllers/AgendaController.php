<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Prospecto;
use App\Models\Atencion;
use App\Util\RuleManager;
use App\Util\ResultManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use phpDocumentor\Reflection\Types\Null_;

class AgendaController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $filters = $this->getFilters($request, new Prospecto());
        $perpage = $this->getLimitPagination($request);

        $queryagenda = Prospecto::where($filters)
            ->with('ultimaatencion');

        $agendas = $queryagenda->orderBy('id', 'DESC')
            ->paginate($perpage);

        return $agendas;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
            $agenda = Atencion::find($id);
            $agenda->fechaagenda = NULL;

            $agenda->update();

            $result = ResultManager::successMessage('agenda ' . RuleManager::getStateName($agenda->fechaagenda). ' correctamente');
        } catch (QueryException $e) {
         $result = ResultManager::gerericErrorMessage();
        } catch (Exception $e) {
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }
}
