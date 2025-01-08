<?php

namespace App\Http\Controllers;
use Intervention\Image\ImageManagerStatic as Image;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Exception;
use Carbon\Carbon;
use App\Models\Cliente;
use App\Util\RuleManager;
use App\Util\ResultManager;
use Illuminate\Http\Request;

use App\Util\LogErrorManager;
use App\Exports\ClientesExport;
use App\Exports\plantillaCliente;
use App\Mail\MessageReceived;
use App\Models\Campania;
use App\Models\EventoCliente;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Str;
use Intervention\Image\Exception\NotReadableException;
use ZipArchive;

class ClienteController extends BaseController
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
        $filters = $this->getFilters($request, new Cliente());
        //agregamos el modelo EVENTO adicionales en cliente + evento, hace el filtro de busqueda en Cliente + Evento 
        $filtersEvento = $this->getFilters($request, new EventoCliente());
        $filterCampania = $this->getFilters($request, new Campania());
        $perpage = $this->getLimitPagination($request);

        if($request->idevento)
        {
            $querycliente = Cliente::where($filters)
            ->with('eventoCliente','campania','distrito','ultimaatencion')
            //agregamos filtro adicionales
            ->whereHas('eventoCliente', function ($query) use ($filtersEvento) {
                $query->where($filtersEvento);
            }) /* 
            ->whereHas('evento')  */;

            if ($this->user->idcuenta != RuleManager::SYSTEM_COMPANY_ACCOUNT || !in_array($this->user->idrol, RuleManager::ADMINISTRATORS_ACCESS)) {
                //filtro mostrar usuarios que pertenecen a una cuenta
                $querycliente->whereHas('campania', function ($query) {
                    $query->where('idcuenta', '=', $this->user->idcuenta);
                });
            }

            if($request->exists('keyimportado')){
                $querycliente->whereNotNull('uuidimportacion');
            }
    
            if ($request->exists('fechadesde')) {
                $fechadesde = Carbon::createFromFormat('d-m-Y', $request->fechadesde)->toDateString();
                $querycliente->whereDate('fecharegistro', '>=', $fechadesde);
            }
    
            if ($request->exists('fechahasta')) {
                $fechahasta = Carbon::createFromFormat('d-m-Y', $request->fechahasta)->toDateString();
                $querycliente->whereDate('fecharegistro', '<=', $fechahasta);
            }
    
            $cliente = $querycliente->orderBy('idcliente', 'DESC')
            ->paginate($perpage);

        }else{

        $querycliente = Cliente::where($filters)
            ->with('eventoCliente','campania','distrito','ultimaatencion')
            //agregamos filtro adicionales
            ->whereHas('campania', function ($query) use ($filterCampania) {
                $query->where($filterCampania);
            }) /* 
            ->whereHas('evento')  */;

            if ($this->user->idcuenta != RuleManager::SYSTEM_COMPANY_ACCOUNT || !in_array($this->user->idrol, RuleManager::ADMINISTRATORS_ACCESS)) {
                //filtro mostrar usuarios que pertenecen a una cuenta
                $querycliente->whereHas('campania', function ($query) {
                    $query->where('idcuenta', '=', $this->user->idcuenta);
                });
            }

            if($request->exists('keyimportado')){
                $querycliente->whereNotNull('uuidimportacion');
            }

            if ($request->has('celinvalid')) {
                $querycliente->where('idtipoatencion', '!=', 3);
            }
    
            if ($request->exists('fechadesde')) {
                $fechadesde = Carbon::createFromFormat('d-m-Y', $request->fechadesde)->toDateString();
                $querycliente->whereDate('fecharegistro', '>=', $fechadesde);
            }
    
            if ($request->exists('fechahasta')) {
                $fechahasta = Carbon::createFromFormat('d-m-Y', $request->fechahasta)->toDateString();
                $querycliente->whereDate('fecharegistro', '<=', $fechahasta);
            }
    
            $cliente = $querycliente->orderBy('idcliente', 'DESC')
            /*cambiar moemntaneo*/
            /*->where('idcampania','=',10)*/
            ->paginate($perpage);
        }
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
        $result = "";
        try {
            $cliente = new Cliente();
            $cliente = $this->setModel($cliente, $request);
            $cliente->userinsert = $this->user->email;
            $cliente->save();

            $result = ResultManager::genericSuccessMessage();

        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);

            $duplicateEntry = 1062; // registro duplicado

            if (count($e->errorInfo)) {
                if ($e->errorInfo[1] == $duplicateEntry) {
                    $result = ResultManager::warningMessage('La dirección de correo electrónico que ha ingresado ya está registrada.');
                } else {
                    $result = ResultManager::errorMessage('Intente nuevamente más tarde.');
                }

            } else {
                $result = ResultManager::errorMessage('Intente nuevamente más tarde.');
            }

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);

            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //return Cliente::with('campania', 'distrito')->find($id);
        return Cliente::with('eventoCliente','campania', 'distrito')
        ->whereHas('campania')->find($id);

        // return Cliente::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $result = "";
        try {

            $cliente = $this->setModel(Cliente::findOrFail($id), $request);
            $cliente->userupdate = $this->user->email;
            $cliente->update();

            if($request->idevento != 0){
                EventoCliente::updateOrCreate(
                    ['idcliente' => $id],
                    ['idevento' => $request->idevento],
                );
            }else{
                EventoCliente::destroy($id);
            }

            $result = ResultManager::genericSuccessMessage();

        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);

            $result = ResultManager::errorMessage('Es posible que haya un alumno registrado con el mismo nombre.');

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);

            $result = ResultManager::gerericErrorMessage();
        }

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = "";

        try {
                $cliente = Cliente::findOrFail($id);
                $cliente->estado = $cliente->estado == RuleManager::DISABLED_STATE ? RuleManager::ACTIVE_STATE : RuleManager::DISABLED_STATE;
                $cliente->userupdate = $this->user->email;
                $cliente->update();

                if ($cliente->estado == RuleManager::ACTIVE_STATE) {
                    $result = ResultManager::successMessage('Participante restaurado correctamente.');
                } else if($cliente->estado == RuleManager::DISABLED_STATE) {
                    $result = ResultManager::warningMessage('Participante eliminado correctamente.');
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function physicallyRemove(Request $request)
    {
        $result = "";

        $id = $request->id;
        try {

            Cliente::destroy($id);
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

    private function setModel(Cliente $cliente, Request $request): Cliente
    {
        
        $cliente->nombres = $request->nombres;
        $cliente->apellidopaterno = $request->apellidopaterno;
        $cliente->apellidomaterno = $request->apellidomaterno;
        $cliente->telefono = $request->telefono;
        $cliente->dni = $request->dni;
        $cliente->carrera = $request->carrera;
        $cliente->grado = $request->grado;
        $cliente->edad = $request->edad;
        $cliente->tipoparticipante = $request->tipoparticipante;
        $cliente->origen = $request->origen;
        $cliente->colegio = $request->has('sede') ? $request->sede : $request->colegio;        
        $cliente->anioegreso = $request->anioegreso;
        $cliente->email = $request->email;
        $cliente->idcampania = $request->idcampania;
        $cliente->procedencia = $request->input('procedencia') ?: 'Orgánico';
        $cliente->utm = $request->utm;
        $cliente->campaign_content = $request->campaign_content;
        $cliente->campaign_medium = $request->campaign_medium;
        $cliente->campaign_name = $request->campaign_name;
        $cliente->campaign_source = $request->campaign_source;
        $cliente->campaign_term = $request->campaign_term;
        $cliente->grado = $request->grado;
        $cliente->coddistrito = $request->coddistrito;

        return $cliente;
    }

    public function exportarExcel(Request $request)
    {
        try {
            $name = 'Alumnos[' . Carbon::now('America/Lima')->format('d-m-Y H:i:s') . ' ].xlsx';
            return Excel::download(new ClientesExport($request), $name);
            
        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);

            throw new HttpException(500);
        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);

            throw new HttpException(500);
        }
    }

    public function descargarplantilla(Request $request)
    {
        try {
            $name = 'plantillaAlumno[' . Carbon::now('America/Lima')->format('d-m-Y H:i:s') . ' ].xlsx';
            return Excel::download(new plantillaCliente($request), $name);
            
        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);

            throw new HttpException(500);
        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);

            throw new HttpException(500);
        }
    }

    public function sedesUni(){
        $sedes = [
             ["sedesalumno" => 'Campus Villa'],
             ["sedesalumno" => 'Campus Norte'],
             ["sedesalumno" => 'Campus Ate'],
             ["sedesalumno" => 'Campus Aramburu'],
        ];
        return $sedes;
    }

    public function procedenciaAdsUtm(){
        $procedencia = [
             ["procedencia" => 'Orgánico'],
             ["procedencia" => 'Pauta'],
        ];
        return $procedencia;
    }

    public function saveClientQr(Request $request)
    {
        $result = '';

        DB::beginTransaction();
        try {

            $cliente = new Cliente();
            $cliente = $this->setModel($cliente, $request);
            $cliente->userinsert = $this->user->email;
            $cliente->save();                       

            $this->enviarCorreoConQR($cliente, false);

            $result = ResultManager::successMessage('Los datos ingresados se guardaron correctamente.');

            DB::commit();

        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            DB::rollback();
            //dd($e);
            $duplicateEntry = 1062; // registro duplicado

            if (count($e->errorInfo)) {
                if ($e->errorInfo[1] == $duplicateEntry) {
                    $result = ResultManager::warningMessage('La dirección de correo electrónico que ha ingresado ya está registrada.');
                } else {
                    $result = ResultManager::errorMessage('Ocurrió un error inesperado. Intente nuevamente más tarde');
                }

            } else {
                $result = ResultManager::errorMessage('Ocurrió un error inesperado. Intente nuevamente más tarde');
            }

        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            DB::rollback();
            //dd($e);
            $result = ResultManager::errorMessage('Ocurrió un error inesperado. Intente nuevamente más tarde');
        }
        return $result;
    }

    private function enviarCorreoConQR($cliente,$issendmail)
    {
        $datos = [
            "nombre" => $cliente->nombres,
            "correo" => $cliente->email,
            /* "dni" => $cliente->dni, */
            "idcampania" => $cliente->idcampania,
        ];

        // Convertir los datos a formato JSON
        $datosJSON = json_encode($datos);

        // "Cifrar" los datos utilizando base64_encode()
        $datosCifrados = base64_encode($datosJSON); 
        
        // Generar código QR con los datos "cifrados"
        $qrCodePNG = QrCode::format('png')->size(400)->generate($datosCifrados); //prod
        $qqWithPadding = $this->paddinQRpng($qrCodePNG, 50); //prod
        
        //$qrCodePNG = QrCode::size(400)->generate($datosCifrados); //local
        //$qqWithPadding = $this->agregarPaddingSVG($qrCodePNG, '20px'); //local      

        if (!$issendmail){
            Mail::to($cliente->email)->send(new MessageReceived($cliente, $qqWithPadding));
        }

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

    public function donwloadQR(Request $request)
    {
        try {
            $filters = $this->getFilters($request, new Cliente());
            $filterCampania = $this->getFilters($request, new Campania());
    
            $querycliente = Cliente::select('idcliente','nombres', 'email', 'idcampania','fecharegistro')
                ->where($filters)
                ->with('eventoCliente','campania','distrito')
                ->whereHas('campania', function ($query) use ($filterCampania) {
                    $query->where($filterCampania);
                });            
    
            if ($this->user->idcuenta != RuleManager::SYSTEM_COMPANY_ACCOUNT || !in_array($this->user->idrol, RuleManager::ADMINISTRATORS_ACCESS)) {
                $querycliente->whereHas('campania', function ($query) {
                    $query->where('idcuenta', '=', $this->user->idcuenta);
                });
            }
            
            if($request->exists('keyimportado')){
                $querycliente->whereNotNull('uuidimportacion');
            }
    
            if ($request->exists('fechadesde')) {
                $fechadesde = Carbon::createFromFormat('d-m-Y', $request->fechadesde)->toDateString();
                $querycliente->whereDate('fecharegistro', '>=', $fechadesde);
            }
    
            if ($request->exists('fechahasta')) {
                $fechahasta = Carbon::createFromFormat('d-m-Y', $request->fechahasta)->toDateString();
                $querycliente->whereDate('fecharegistro', '<=', $fechahasta);
            }
    
            $clientedata = $querycliente->orderBy('idcliente', 'DESC')->get();
        
            // Carpeta temporal para almacenar los códigos QR antes de comprimir
            $tempFolder = storage_path('app/public/temp_qrcodes');
        
            // Verificar y crear la carpeta temporal si no existe
            if (!file_exists($tempFolder)) {
                mkdir($tempFolder, 0777, true);
            }
        
            // Generar y almacenar los códigos QR para cada cliente
            foreach ($clientedata as $cliente) {
                if (!is_null($cliente->nombres) && !is_null($cliente->email) && !is_null($cliente->idcampania)) {

                    /* $qrCode = QrCode::size(400)->generate($qrDataJson); */

                    $nombreArchivo = $cliente->email . '_' . $cliente->idcliente . '.png'; //prod
                    //$nombreArchivo = $cliente->email . '_' . $cliente->idcliente . '.svg';
        
                    // Almacenar el código QR en la carpeta temporal
                    Storage::put('public/temp_qrcodes/' . $nombreArchivo, (string) $this->enviarCorreoConQR($cliente, true));

                }
            }
        
            // Crear un archivo ZIP para los códigos QR
            $zipFileName = 'qrcodes_' . date('Y-m-d_H-i-s') . '.zip';
            $zip = new ZipArchive;
            if ($zip->open(storage_path('app/public/') . $zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                // Agregar archivos a ZIP
                $files = Storage::files('public/temp_qrcodes');
                foreach ($files as $file) {
                    $fileName = basename($file);
                    $zip->addFile(storage_path('app/') . $file, $fileName);
                }
        
                // Cerrar y guardar en el ZIP
                $zip->close();
        
                // Eliminar los archivos temporales de códigos QR
                Storage::deleteDirectory('public/temp_qrcodes');
            }
        
            // Ruta completa del archivo ZIP
            $pathToFile = storage_path('app/public/') . $zipFileName;
        
            // Descargar el archivo ZIP y eliminarlo después de enviarlo
            $result = response()->download($pathToFile)->deleteFileAfterSend(true);
            
    
        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            //dd($e);
            $result = ResultManager::errorMessage('Error al generar los datos del QR.');
        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            //dd($e);
            $result = ResultManager::errorMessage('Error al procesar la solicitud.');
        }

        return $result;
    }

    public function downloadSingleQR(Request $request)
    {
        $result = null;
        try {
            $email = $request->input('email');
        
            // Validar el email del cliente
            if (!$email) {
                return ResultManager::errorMessage('no se puede generar el QR por falta del correo.');
            }

            // Obtener el cliente por email
            $cliente = Cliente::select('idcliente','nombres', 'email', 'idcampania', 'fecharegistro')
                ->where('email', $email)
                ->first();

            // Validar que se encontró el cliente
            if (is_null($cliente)) {
                return ResultManager::errorMessage('No se pudo identificar el registro al generar el QR.');
            }

            // Generar el código QR
            $qrContent = (string) $this->enviarCorreoConQR($cliente, true);

            // Nombre del archivo
            $fileName = $cliente->email . '_' . $cliente->idcliente . '.png'; //prod
            //$fileName = $cliente->email . '_' . $cliente->idcliente . '.svg'; //local

            // Retornar la respuesta con el archivo
            $result = response($qrContent, 200)
                ->header('Content-Type', 'image/png') //prod
                //->header('Content-Type', 'image/svg+xml') //local
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
            
        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            dd($e);
            $result = ResultManager::errorMessage('Error al generar los datos del QR.');
        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            dd($e);
            $result = ResultManager::errorMessage('Error al procesar la solicitud.');
        }

        return $result;

    }

    public function generatecard($id){
        $cliente = Cliente::find($id);

        $qryf =  $this->enviarCorreoConQR($cliente, true);

        //$qrCodeDataUri = 'data:image/svg+xml;base64,' . base64_encode($qryf);//local
        //$pdf = Pdf::loadView('card.cardaccess', ['cliente' => $cliente, 'qryf' => $qrCodeDataUri]); //local
        
        $qrCodeDataUri = 'data:image/png;base64,' . base64_encode($qryf);//prod
        $pdf = Pdf::loadView('card.cardaccess', ['cliente' => $cliente, 'qryf' => $qrCodeDataUri]); //prod

        return $pdf->stream('Carnet - '.$cliente->nombres.' '.$cliente->apellidopaterno.' '.$cliente->apellidomaterno  . '.pdf');
    }

    public function selectsearch(Request $request)
    {
        $authcampania = Auth()->user()->idcampania;
        $search = $request->input('search');

        $query = Cliente::where([['idcampania',$authcampania],['estado','A']])
            ->select('idcliente', 'email', 'nombres', 'apellidopaterno','apellidomaterno','telefono','asistencia', 'idcampania', 'fechaasistencia')
            ->whereRaw("concat(email,' ',telefono, ' ',nombres,' ',apellidopaterno,' ',apellidomaterno) like ?", "%{$search}%")
            ->with('eventoCliente')
            ->orderBy('idcliente', 'DESC')
            ->limit(15) // Limitar el número de resultados
            ->get();

        return $query;
    }


    
    
}
