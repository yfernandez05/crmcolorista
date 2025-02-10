<?php

namespace App\Http\Controllers;

use App\Models\Ubigeo;
use Illuminate\Http\Request;

class UbigeoController extends BaseController
{
    public function index(Request $request)
    {

        $ubigeos = Ubigeo::all()
            ->orderBy('pkubigeo', 'DESC')
            ->get();

        return $ubigeos;

    }

    public function departamentos()
    {

        $ubigeos = Ubigeo::whereNull('fkubigeo')
            ->orderBy('pkubigeo', 'DESC')
            ->get();

        return $ubigeos;

    }

    public function provincias(Request $request)
    {
        $departamentoId = $request->input('departamento_id');
        $ubigeos = Ubigeo::where('fkubigeo', $departamentoId)
            ->orderBy('pkubigeo', 'DESC')
            ->get();

        return $ubigeos;
    }

    public function distritos(Request $request)
    {
        $provinciaId = $request->input('provincia_id');
        $ubigeos = Ubigeo::where('fkubigeo', $provinciaId)
            ->orderBy('pkubigeo', 'DESC')
            ->get();

        return $ubigeos;
    }

    public function distritosleccionados(){
        $ubigeos = Ubigeo::whereIn('fkubigeo',['1501', '0701', '1301'])
            ->select('pkubigeo as coddistrito', 'fkubigeo as codprovincia', 'nombreubigeo as distrito', 'nombrecompleto')
            ->orderBy('pkubigeo', 'DESC')
            ->get();

        return $ubigeos;
    }
}
