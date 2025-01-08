<?php

namespace App\Http\Controllers;

use App\Models\Campania;
use App\Models\Cliente;
use App\Models\Evento;
use App\Util\RuleManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Client;

class ReporteController extends BaseController
{
    public function cantidadclientesevento(Request $request){  

        /* $eventos = Cliente::with('evento')->whereHas('evento')->select('eventos.idevento', 'eventos.nombreevento' ,DB::raw('count(clientes.idcliente) as total'));

        if ($this->user->idcuenta != RuleManager::SYSTEM_COMPANY_ACCOUNT || !in_array($this->user->idrol, RuleManager::ADMINISTRATORS_ACCESS)) {
            //filtro mostrar campania  que pertenecen a una cuenta
            $eventos->whereHas('evento.campania', function ($query)  {
                $query->where('idcuenta', '=', $this->user->idcuenta);
            });             
        }

        $clientefiltereventos = $eventos 
        ->join('eventos', 'clientes.idevento', '=', 'eventos.idevento')
        ->where('clientes.estado' , '=', RuleManager::ACTIVE_STATE)
        ->groupBy('eventos.idevento')
        ->get(); */

        $clientefiltereventos = DB::select(
            'CALL SP_REGSITROEVENTOSCAMPANIA_CLIENTES (?,?)',
            [
                $request->idevento,
                $request->idcampania,
            ]
        );
        

        return $clientefiltereventos;
    }

    public function registroclientes(Request $request)
    {

        if($request->asistencia == 0){
            $store = DB::select(
                'CALL SP_REGSITROSFECHAS_CLIENTES(?, ?, ?, ?, ?)',
                [
                    $request->idevento,
                    $request->idcampania,
                    $request->idcolegio,
                    $request->asistencia,
                    $request->import
                ]
            );
        }else{
            $store = DB::select(
                'CALL SP_REGSITROSFECHAS_CLIENTES_ASISTIDO(?, ?, ?, ?, ?)',
                [
                    $request->idevento,
                    $request->idcampania,
                    $request->idcolegio,
                    $request->asistencia,
                    $request->import
                ]
            );
        }
        
        

        $totalProspectos = collect($store)->sum('total');

        // agrupar los resultados por colegio
        $datasets = collect($store)->groupBy('colegio')->map(function ($items, $key) {
            return [
                'label' => $key,
                'data' => $items->map(function ($item) {
                    return [
                        'fechaGeneral' => Carbon::parse($item->fechaGeneral)->format('d-m H:i'),
                        'total' => $item->total,
                    ];
                })->values()->toArray(),
            ];
        })->values()->toArray();

        $datareport = [
            'cantidadprospectos' => $totalProspectos,
            'datasets' => $datasets,
        ];

        return response()->json($datareport);
    }

    public function registrosede(Request $request){
        $store = DB::select(
            'CALL SP_RP_REGISTROS_SEDES(?,?,?,?,?)',
                [
                    $request->idevento,
                    $request->idcampania,
                    $request->idcolegio,
                    $request->asistencia,
                    $request->import
                ]
        );

        return $store;

    }

    public function registroanioegreso(Request $request){
        $store = DB::select(
            'CALL SP_RP_REGISTROS_ANIOEGERSO(?,?,?,?,?)',
                [
                    $request->idevento,
                    $request->idcampania,
                    $request->idcolegio,
                    $request->asistencia,
                    $request->import
                ]
        );

        return $store;

    }

    public function registrocarrera(Request $request){
        //return dd($request);
        $store = DB::select(
            'CALL SP_RP_REGISTROS_CARRERAS(?,?,?,?,?)',
                [
                    $request->idevento,
                    $request->idcampania,
                    $request->idcolegio,
                    $request->asistencia,
                    $request->import
                    //$request->asistencia === "true" ? 1 : 0
                ]
        );

        return $store;

    }

    public function asistenciasedes(Request $request){
        $store = DB::select(
            'CALL SP_RP_ASISTENCIA_SEDE (?,?,?)',
                [
                    $request->idevento,
                    $request->idcampania,
                    $request->idcolegio,
                ]
            );
        return $store;
    }


    public function enviosinvitacionwtsp(Request $request){
        $store = DB::select(
            'CALL SP_RP_REGISTROS_ENVIONOTIFACION(?)',
                [
                    $request->idcampania
                ]
        );
        return $store;
    }


    public function registroprocedencia(Request $request){
        $store = DB::select(
            'CALL SP_RP_REGISTROS_PROCEDENCIAS(?,?,?,?,?)',
                [
                    $request->idevento,
                    $request->idcampania,
                    $request->idcolegio,
                    $request->asistencia,
                    $request->import
                ]
        );
        return $store;
    }


    public function registrocampainsource(Request $request){
        $store = DB::select(
            'CALL SP_RP_REGISTROS_CAMPAINRESOURCE(?,?,?,?,?)',
                [
                    $request->idevento,
                    $request->idcampania,
                    $request->idcolegio,
                    $request->asistencia,
                    $request->import
                ]
        );
        return $store;
    }



    public function cantidadasistenciastand(Request $request){
        $store = DB::select(
            'CALL SP_RP_CANTIDADASISTENCIASEDES(?,?)',
                [
                    $request->idcampania,
                    $request->asistencia,
                ]
        );
        return $store;
    }



    public function registrostandventa(Request $request){
        $store = DB::select(
            'CALL SP_RP_REGISTROSSTANDVENTA(?,?)',
                [
                    $request->idcampania,
                    $request->asistencia,
                ]
        );
        return $store;
    }



    public function registrostandbeca(Request $request){
        $store = DB::select(
            'CALL SP_RP_REGISTROSSTANDBECA(?,?)',
                [
                    $request->idcampania,
                    $request->asistencia,
                ]
        );
        return $store;
    }


}
