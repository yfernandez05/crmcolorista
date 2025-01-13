<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Util\LogErrorManager;
use App\Util\ResultManager;
use App\Util\RuleManager;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class AlumnoController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $this->getFilters($request, new Alumno());
        $perpage = $this->getLimitPagination($request);

        $query = Alumno::where($filters);

        $alumnos = $query-> orderBy('id', 'DESC')
            ->paginate($perpage);

        return $alumnos;
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

            $alumno = $this->setModel(new Alumno(), $request);
            $alumno->created_usr = $this->user->email;
            $alumno->save();

            $result = ResultManager::genericSuccessMessage();
        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            dd($e);
            $result = ResultManager::errorMessage('Es posible que hay un alumno registrado con el mismo correo.');

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            dd($e);
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Alumno  $alumno
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Alumno::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Alumno  $alumno
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = "";
        try {

            $alumno = $this->setModel(Alumno::findOrFail($id), $request);
            $alumno->updated_usr = $this->user->email;
            $alumno->update();

            $result = ResultManager::genericSuccessMessage();
        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::errorMessage('Es posible que hay un alumno registrada con el mismo correo.');

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Alumno  $alumno
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = "";

        try {

            $alumno = Alumno::findOrFail($id);
            $alumno->estado = RuleManager::DISABLED_STATE;
            $alumno->updated_usr = $this->user->email;
            $alumno->update();

            $result = ResultManager::successMessage('Alumno eliminado correctamente.');

        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    private function setModel(Alumno $alumno, Request $request): Alumno
    {
        $alumno->nombre = $request->nombre;
        $alumno->apellido = $request->apellido;
        //$alumno->fecha_nac = Carbon::createFromFormat('d-m-Y', $request->fecha_nac);
        $alumno->correo = $request->correo;
        $alumno->user_id = 1;
        $alumno->asesoracargo = $request->asesoracargo;
        $alumno->dni = $request->dni;
        $alumno->direccion = $request->direccion;
        $alumno->distrito = $request->distrito;
        $alumno->celular = $request->celular;
        $alumno->empresa = $request->empresa;
        $alumno->cargodesempenia = $request->cargodesempenia;
        $alumno->colegiosegundario = $request->colegiosegundario;
        $alumno->inicioclases = $request->inicioclases;
        $alumno->turno = $request->turno;
        $alumno->pago = $request->pago;
        $alumno->curso = $request->curso;
        if (!is_null($request->fecha_nac)) {
            $alumno->fecha_nac = Carbon::createFromFormat('d-m-Y H:i:s', $request->fecha_nac);
        }
        if (!is_null($request->fecha_inscripcion)) {
            $alumno->fecha_inscripcion = Carbon::createFromFormat('d-m-Y H:i:s', $request->fecha_inscripcion);
        }
        //$alumno->fecha_inscripcion = Carbon::createFromFormat('d-m-Y', $request->fecha_inscripcion);

        return $alumno;
    }

    public function generatecard($id){
        $alumno = Alumno::find($id);


        //$qrCodeDataUri = 'data:image/svg+xml;base64,' . base64_encode($qryf);//local
        //$pdf = Pdf::loadView('card.cardaccess', ['cliente' => $cliente, 'qryf' => $qrCodeDataUri]); //local

       /*  $qrCodeDataUri = 'data:image/png;base64,' . base64_encode($qryf); *///prod
        $pdf = Pdf::loadView('card.cardaccess', ['alumno' => $alumno]); //prod

        return $pdf->stream('Carnet - '.$alumno->nombre.' '.$alumno->apellido. '.pdf');
    }
}
