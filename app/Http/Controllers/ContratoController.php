<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Contrato;
use App\Models\DetalleContrato;
use App\Util\LogErrorManager;
use App\Util\ResultManager;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ContratoController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $this->getFilters($request, new Contrato());
        $perpage = $this->getLimitPagination($request);

        $contrato = Contrato::where($filters)
            ->with('alumno','matricula')
            ->orderBy('id', 'DESC')
            ->paginate($perpage);

        return $contrato;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return dd($request);
        $result = "";
        try {

            $contrato = $this->setModel(new Contrato(), $request);
            $contrato->created_usr  = $this->user->email;
            $contrato->save();

            if (isset($request->detalles) && is_array($request->detalles)) {
                foreach ($request->detalles as $detalle) {
                    $detalleContrato = new DetalleContrato();
                    $detalleContrato->contrato_id = $contrato->id; // Relacionar con el contrato
                    $detalleContrato->preciomes = $detalle['preciomes'];
                    $detalleContrato->fechapago = $detalle['fechapago'];
                    $detalleContrato->save();
                }
            }

            $result = ResultManager::genericSuccessMessage();

        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            dd($e);
            $result = ResultManager::errorMessage('Es posible que haya un contrato de pago registrado con el mismo nombre.');

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
     * @param  \App\Contrato  $contrato
     * @return \Illuminate\Http\Response
     */
    public function show(Contrato $contrato)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contrato  $contrato
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contrato $contrato)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contrato  $contrato
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contrato $contrato)
    {
        //
    }

    public function selectsearch(Request $request)
    {
        $search = $request->input('search');

        $alumnos = Alumno::where('estado', 'A')
            ->select('id', 'nombre', 'apellido', 'dni', 'direccion', 'distrito', 'estado')
            ->whereRaw("concat(nombre, ' ', apellido, ' ', dni) like ?", ["%{$search}%"])
            ->with(['matriculas','matriculas.carrera','matriculas.ciclo','matriculas.detalles']) // Carga las matrículas y sus detalles
            ->orderBy('id', 'DESC')
            ->limit(15)
            ->get();

        return $alumnos;
    }

    private function setModel(Contrato $contrato, Request $request): Contrato
    {
        $contrato->alumno_id = $request->alumno_id;
        $contrato->matricula_id = $request->matricula_id;
        $contrato->domicilio = $request->domicilio;
        $contrato->fecha_inscripcion = Carbon::createFromFormat('d-m-Y', $request->fecha_inscripcion);
        $contrato->monto_promocional = $request->monto_promocional;
        $contrato->monto_preabonado = $request->monto_preabonado;
        $contrato->importe_restante = $request->importe_restante;
        $contrato->fecha_limitepago = Carbon::createFromFormat('d-m-Y', $request->fecha_limitepago);
        $contrato->duracion_modulo = $request->duracion_modulo;
        $contrato->cantidadveces = $request->cantidadveces;
        $contrato->duracionhoras = $request->duracionhoras;
        return $contrato;
    }


    public function pdfcontrato($id){
        $contrato = Contrato::find($id);
        /* return dd($contrato); */
        $pdf = Pdf::loadView('contrato.contrato', ['contrato' => $contrato]);
        return $pdf->stream('Contrato.pdf');
    }

}
