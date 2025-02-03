<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Importacion;
use App\Util\ResultManager;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Util\LogErrorManager;
use App\Imports\ClientesImport;
use App\Imports\ProspectoImport;
use App\Models\Evento;
use App\Models\Prospecto;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\QueryException;

class ImportacionController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $this->getFilters($request, new Importacion());
        $perpage = $this->getLimitPagination($request);

        $queryImportaciones = Importacion::where($filters)
            ->with('user');
            

        if ($request->exists('fechadesde')){
            $fechadesde = Carbon::createFromFormat('d-m-Y', $request->fechadesde)->toDateString();
            $queryImportaciones->whereDate('fecharegistro','>=', $fechadesde);
        }

        if ($request->exists('fechahasta')) {
            $fechahasta = Carbon::createFromFormat('d-m-Y', $request->fechahasta)->toDateString();           
            $queryImportaciones->whereDate('fecharegistro','<=', $fechahasta);
        }

        $importaciones = $queryImportaciones-> orderBy('idimportacion', 'DESC')
            ->paginate($perpage);

        return $importaciones;
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
            
            $file = $request->file('file');

            $impotacion = new Importacion();
            $impotacion->nombrearchivo = $file->getClientOriginalName(); 
            $impotacion->fecharegistro = Carbon::now();
            $impotacion->iduser = $this->user->id; 
            $impotacion->uuidimportacion = Str::uuid();

            $prospectoImport = new ProspectoImport($impotacion);
            Excel::import($prospectoImport, $file);

            /* return dd($prospectoImport->errors); */
            
            // Verificar errores
            if (!empty($prospectoImport->errors)) {
                $errorMessages = '';
                foreach ($prospectoImport->errors as $error) {
                    $errorMessages .= 'Fila ' . $error['row'] . ': ' . $error['message'] . "</br>";
                }
                return ResultManager::warningMessage("<span class='text-dark font-weight-bold'>Registros no importados corregir en:</span></br> <span class='text-dark'>" . $errorMessages);
            }
            
            $impotacion->cantregistros = $prospectoImport->getRowCount(); 
            $impotacion->save();

            $result = ResultManager::genericSuccessMessage();
        
         } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            //dd($e);
            $duplicateEntry = 1062; // registro duplicado

            if (count($e->errorInfo)) {
                if ($e->errorInfo[1] == $duplicateEntry) {
                    $result = ResultManager::warningMessage('No se puede importar datos duplicados en el sistema, verficar el <strong>email ó celular</strong>.');
                } else {
                    $result = ResultManager::errorMessage('Intente nuevamente más tarde.');
                }

            } else {
                $result = ResultManager::errorMessage('Intente nuevamente más tarde.');
            }

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            //dd($e);
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        //
    }
}
