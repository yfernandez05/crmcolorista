<?php

namespace App\Http\Controllers;

use App\Models\Campania;
use App\Models\Evento;
use App\Models\EventoCliente;
use App\Util\RuleManager;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventoClienteController extends BaseController
{
    public function __construct()
    {
        parent::__construct(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $this->getFilters($request, new EventoCliente());
        //agregamos el modelo EVENTO adicionales en cliente + evento, hace el filtro de busqueda en Cliente + Evento 
        $filtersEvento = $this->getFilters($request, new Evento());
        $perpage = $this->getLimitPagination($request);

        $querycliente = EventoCliente::where($filters)
            ->with('evento','distrito')
            //agregamos filtro adicionales
             ->whereHas('evento', function ($query) use ($filtersEvento) {
                $query->where($filtersEvento);
            });

            /* if ($this->user->idcuenta != RuleManager::SYSTEM_COMPANY_ACCOUNT || !in_array($this->user->idrol, RuleManager::ADMINISTRATORS_ACCESS)) {
                //filtro mostrar usuarios que pertenecen a una cuenta
                $querycliente->whereHas('campania', function ($query) {
                    $query->where('idcuenta', '=', $this->user->idcuenta);
                });
            } */
    
            if ($request->exists('fechadesde')) {
                $fechadesde = Carbon::createFromFormat('d-m-Y', $request->fechadesde)->toDateString();
                $querycliente->whereDate('fecharegistro', '>=', $fechadesde);
            }
    
            if ($request->exists('fechahasta')) {
                $fechahasta = Carbon::createFromFormat('d-m-Y', $request->fechahasta)->toDateString();
                $querycliente->whereDate('fecharegistro', '<=', $fechahasta);
            }
    
            $cliente = $querycliente->orderBy('ideventocliente', 'DESC')
            ->paginate($perpage);

        return $cliente;
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
        return EventoCliente::with('evento','distrito')
        ->whereHas('evento')->find($id);
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
        //
    }
}
