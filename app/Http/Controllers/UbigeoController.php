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
        
        $ubigeos = Ubigeo::whereNull('fkubigeo')
            ->orderBy('pkubigeo', 'DESC')
            ->get();

        return $ubigeos;

    }

    public function distritos(Request $request)
    {
        
        $ubigeos = Ubigeo::whereNull('fkubigeo')
            ->orderBy('pkubigeo', 'DESC')
            ->get();

        return $ubigeos;

    }

    public function distritosleccionados(){
        $ubigeos = Ubigeo::where('fkubigeo','1501')
            ->select('pkubigeo as coddistrito', 'fkubigeo as codprovincia', 'nombreubigeo as distrito', 'nombrecompleto')
            ->orderBy('pkubigeo', 'DESC')
            ->get();

        return $ubigeos;
    }
}
