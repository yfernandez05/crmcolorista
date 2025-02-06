<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Alumno;
use App\Models\Matricula;
use App\Util\RuleManager;
use App\Util\ResultManager;
use Illuminate\Http\Request;
use App\Mail\MessageReceived;
use App\Util\LogErrorManager;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\QueryException;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Intervention\Image\ImageManagerStatic as Image;

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
        $result ="";
        $errorMsg='No se puede eliminar. Matricula hace uso de este alumno.';

        try {
            $numberDependents = Matricula::where(['id'=>$id, 'estado'=> 'A'])->count();

            if($numberDependents==0){

                $alumno = Alumno::findOrFail($id);
                $alumno->estado = RuleManager::DISABLED_STATE;
                $alumno->updated_usr = $this->user->email;
                $alumno->update();

                $result = ResultManager::successMessage('alumno eliminado correctamente.');
            }else{
                $result=ResultManager::errorMessage($errorMsg);
            }
        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::errorMessage($errorMsg);

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
        $alumno->user_id = Auth()->user()->id;
       // $alumno->asesoracargo = $request->asesoracargo;
        $alumno->dni = $request->dni;
        $alumno->direccion = $request->direccion;
        $alumno->distrito = $request->distrito;
        $alumno->celular = $request->celular;
        $alumno->empresa = $request->empresa;
        $alumno->cargodesempenia = $request->cargodesempenia;
        // $alumno->colegiosegundario = $request->colegiosegundario;
        $alumno->inicioclases = $request->inicioclases;
        //$alumno->turno = $request->turno;
        //$alumno->pago = $request->pago;
        //$alumno->curso = $request->curso;
        $alumno->prospecto_id = $request->prospecto_id;
        if (!is_null($request->fecha_nac)) {
            $alumno->fecha_nac = Carbon::createFromFormat('d-m-Y', $request->fecha_nac);
        }
        $alumno->fecha_inscripcion = Carbon::now();
        $alumno->sexo = $request->sexo;
        $alumno->trabajo = $request->trabajo;
        $alumno->contactoemergencia = $request->contactoemergencia;
        $alumno->edad = $request->edad;

        return $alumno;
    }

    public function generatecard($id){
        $alumno = Alumno::find($id);

        $qryf =  $this->enviarCorreoConQR($alumno, true);

        //return dd($qryf);

        $qrCodeDataUri = 'data:image/svg+xml;base64,' . base64_encode($qryf);//local
        $pdf = Pdf::loadView('card.cardaccess', ['alumno' => $alumno, 'qryf' => $qrCodeDataUri]); //local

        //$qrCodeDataUri = 'data:image/png;base64,' . base64_encode($qryf);//prod
        //$pdf = Pdf::loadView('card.cardaccess', ['alumno' => $alumno, 'qryf' => $qrCodeDataUri]); //prod

        return $pdf->stream('Carnet - '.$alumno->nombre.' '.$alumno->apellido.'.pdf');
    }

    private function enviarCorreoConQR($alumno,$issendmail)
    {
        /* $datos = [
            "id" => $alumno->id,
            "nombre" => $alumno->nombre,
            "dni" => $alumno->dni,
        ]; */

        $dataalunos = $alumno->id .'-'.$alumno->dni;

        // Convertir los datos a formato JSON
        //$datosJSON = json_encode($datos);

        // "Cifrar" los datos utilizando base64_encode()
        //$datosCifrados = base64_encode($datosJSON);

        // Generar código QR con los datos "cifrados"
        //$qrCodePNG = QrCode::format('png')->size(400)->generate($datosCifrados); //prod
        //$qqWithPadding = $this->paddinQRpng($qrCodePNG, 50); //prod

        $qrCodePNG = QrCode::size(400)->generate($dataalunos); //local
        $qqWithPadding = $this->agregarPaddingSVG($qrCodePNG, '20px'); //local

        /* if (!$issendmail){
            Mail::to($alumno->email)->send(new MessageReceived($alumno, $qqWithPadding));
        } */

        if($issendmail){
            return $qqWithPadding;
        }

    }

    function paddinQRpng($img, $padding){

        if ($img instanceof HtmlString) {
            $img = (string) $img;
        }

        $qrImage = Image::make($img);

        // Calcular el tamaño del lienzo con padding
        $canvasWidth = $qrImage->width() + 2 * $padding;
        $canvasHeight = $qrImage->height() + 2 * $padding;

        // Crear un lienzo blanco
        $canvas = Image::canvas($canvasWidth, $canvasHeight, '#ffffff');

        $canvas->insert($qrImage, 'center');

        return $canvas->encode('png');

    }


    function agregarPaddingSVG($svgString, $padding) {
        return str_replace('<svg', '<svg style="padding: ' . $padding . '; background: #fff;"', $svgString);
    }

    public function select(Request $request)
    {
        $filters = $this->getFilters($request, new Alumno());

        $carrera = Alumno::where($filters)
            ->orderBy('id', 'DESC')
            ->get();

        return $carrera;
    }
}
