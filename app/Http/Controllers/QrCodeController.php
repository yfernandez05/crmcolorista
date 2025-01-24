<?php

namespace App\Http\Controllers;

use App\Mail\MessageReceived;
use App\Models\Alumno;
use App\Models\Asistencia;
use App\Models\Cliente;
use App\Models\EventoCliente;
use App\Util\LogErrorManager;
use App\Util\ResultManager;
use App\Util\RuleManager;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{

    public function decryptQRCode(Request $qrCodeData)
    {        

        // El dato escaneado se espera como algo similar a "17-78775577"
        $data = $qrCodeData->qrCode;

        // Dividir la cadena usando el guion como delimitador
        $parts = explode('-', $data);

        // Tomar la segunda parte después del guion
        $afterHyphen = $parts[1] ?? null;

        // Verificar si la parte después del guion es válida
        if (!$afterHyphen) {
            return ResultManager::warningMessage('QR no válido');
        }

        // Usar la parte después del guion para continuar con la lógica
        return $this->authLogin($afterHyphen);
    }

    public function authLogin($dataQR)
    {
        $result = '';

        //return $result = $this->readingAssistanceQR($dataQR);
        
        if(Auth()->user()->rol_id == RuleManager::PERSONAL_ATTENDANCE_ACCESS || in_array(Auth()->user()->rol_id, RuleManager::ADMINISTRATORS_ACCESS)){
            $result = $this->readingAssistanceQR($dataQR);
        }

        if(Auth()->user()->rol_id == RuleManager::PERSONAL_STAND_ACCESS || Auth()->user()->rol_id == RuleManager::PERSONAL_STAND_SALE_ACCESS){
            $result = $this->readingStandQR($dataQR);
        }

        if(empty($result)){
            $result = ResultManager::errorMessage("No se pudo leer el QR. Intentelo nuevamente.");
        }

        return $result;
    }

    public function readingAssistanceQR($dataQR)
    {
        $result = '';
        $errorMsg = 'Error al leer el QR, intentelo nuevamente';

        try {
            
            /* $clientAsisten = Cliente::where(['email' => $dataQR['correo'], 'idcampania' => $dataQR['idcampania'], 'estado' => RuleManager::ACTIVE_STATE])->first();

            if($clientAsisten->asistencia){

                $anioEgreso = (int) $clientAsisten->anioegreso;

                if ($anioEgreso <= 0) {
                    return ResultManager::warningMessage('EL QR YA FUE USADO');
                } else {
                    $anioEgreso = (int) $clientAsisten->anioegreso;
                    $dataQR['config'] = RuleManager::getQRConfigColor($anioEgreso);
                    $config = $dataQR['config'];
                
                    $textColor = $config['color'] === 'white' ? 'black' : 'white';
                    $borderColor = $config['color'] === 'white' ? 'gray' : $config['color']; 
                
                    return ResultManager::warningMessage('EL QR YA FUE USADO 
                        <h4 style="
                            text-transform: uppercase;
                            background-color: ' . $config['color'] . ';
                            padding: 10px;
                            margin-top: 1em;
                            color: ' . $textColor . ';
                            border: 2px solid ' . $borderColor . ';
                            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.2);">
                            ' . $config['description'] . '
                        </h4>'
                    );
                }

                if ($anioAsistencia < 1970) {
                    $clientAsisten->fechaasistencia = Carbon::now()->format('y-m-d H:i:s');
                }
    
                $anioEgreso = (int) $clientAsisten->anioegreso;
                $dataQR['config'] = RuleManager::getQRConfigColor($anioEgreso);
                $clientAsisten->update();
    
                $result = ResultManager::successMessageData('El QR si es valido', $dataQR); */
    



            $alumno = Alumno::select('id','dni','nombre','apellido')->where('dni', $dataQR)->first();
            //return dd($alumno);
            $asistenciaHoy = Asistencia::where('alumno_id', $alumno->id)
            ->whereDate('fecha_asistencia', Carbon::today())
            ->first();

            if ($asistenciaHoy) {
                return ResultManager::warningMessage('YA MARCO ASISTENCIA');
            }

            Asistencia::create([
                'alumno_id' => $alumno->id,
                'fecha_asistencia' => Carbon::now()->format('Y-m-d H:i:s'),
                'fecha' => Carbon::now()->startOfDay()->format('Y-m-d H:i:s'),

            ]);
            
            $result = ResultManager::successMessageData('El QR si es valido', $alumno);

        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::errorMessage($errorMsg);
            /* dd($e); */
        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::warningMessage($errorMsg);
            /* dd($e); */
        }

        return $result;
    }

    public function readingStandQR($dataQR)
    {
        $result = '';
        $errorMsg = 'Error al leer el QR, intentelo nuevamente';
        $idevento = Auth::user()->idevento;

        try {
            DB::beginTransaction();

            $clientAsisten = Cliente::where([
                'email' => $dataQR['correo'],
                'idcampania' => $dataQR['idcampania'],
                'estado' => RuleManager::ACTIVE_STATE
            ])->firstOrFail();

            $existsClientEvent = EventoCliente::where('idcliente', $clientAsisten->idcliente)
                           ->where('idevento', $idevento)
                           ->exists();

            if ($existsClientEvent) {
                return ResultManager::warningMessage('EL PARTICIPANTE YA SE ENCUENTRA REGISTRADO');
            }

            if ($clientAsisten->asistencia < 1) {
                return ResultManager::warningMessage('EL ASISTENTE <b>'.$dataQR['nombre'].'</b> NO MARCO SU ASISTENCIA EN LA ENTRADA.');
            }

            EventoCliente::create([
                'idevento' => $idevento,
                'idcliente' => $clientAsisten['idcliente'],
                'nombres' => $clientAsisten['nombres'],
                'apellidopaterno' => $clientAsisten['apellidopaterno'],
                'apellidomaterno' => $clientAsisten['apellidomaterno'],
                'email' => $clientAsisten['email'],
                'telefono' => $clientAsisten['telefono'],
                'edad' => $clientAsisten['edad'],
                'dni' => $clientAsisten['dni'],
                'fecharegistro' => $clientAsisten['fecharegistro'],
                'coddistrito' => $clientAsisten['coddistrito'],
                'utm' => $clientAsisten['utm'],
                'campaign_content' => $clientAsisten['campaign_content'],
                'campaign_medium' => $clientAsisten['campaign_medium'],
                'campaign_name' => $clientAsisten['campaign_name'],
                'campaign_source' => $clientAsisten['campaign_source'],
                'campaign_term' => $clientAsisten['campaign_term'],
                'procedencia' => $clientAsisten['procedencia'],
                'grado' => $clientAsisten['grado'],
                'carrera' => $clientAsisten['carrera'],
                'origen' => $clientAsisten['origen'],
                'tipoparticipante' => $clientAsisten['tipoparticipante'],
                'colegio' => $clientAsisten['colegio'],
                'anioegreso' => $clientAsisten['anioegreso'],
                'uuidimportacion' => $clientAsisten['uuidimportacion'],
                'asistencia' => $clientAsisten['asistencia'],
                'fechaasistencia' => Carbon::now()->format('y-m-d H:i:s'),
                'estado' => $clientAsisten['estado'],
            ]);

            DB::commit();

            $result = ResultManager::successMessageData('PARTICIPANTE REGISTRADO', $dataQR);

        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                $result = ResultManager::errorMessage('El participante ya está registrado en este stand.');
            } else {
                LogErrorManager::saveInDB($this, __FUNCTION__, $e);
                $result = ResultManager::errorMessage($errorMsg);
            }            
            DB::rollBack();
            //dd($e);
        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::warningMessage($errorMsg);            
            DB::rollBack();
            //dd($e); 
        }

        return $result;
    }


    public function saveStandSaleManual($data)
    {

        /* return dd($data); */

        $result = '';
        $errorMsg = 'Error al registrarlo, intentelo nuevamente';
        $idevento = Auth::user()->idevento;

        try {
            DB::beginTransaction();

            $clientAsisten = Cliente::where([
                'email' => $data->email,
                'idcampania' => $data->idcampania,
                'estado' => RuleManager::ACTIVE_STATE
            ])->firstOrFail();

            $existsClientEvent = EventoCliente::where('idcliente', $clientAsisten->idcliente)
                           ->where('idevento', $idevento)
                           ->exists();

            if ($existsClientEvent) {
                return ResultManager::warningMessage('EL PARTICIPANTE YA SE ENCUENTRA REGISTRADO EN ESTE STAND');
            }

            if ($clientAsisten->asistencia < 1) {
                return ResultManager::warningMessage('EL ASISTENTE <strong>'.$clientAsisten->nombres.'</strong> NO MARCO SU ASISTENCIA EN LA ENTRADA.');
            }

            EventoCliente::create([
                'idevento' => $idevento,
                'idcliente' => $clientAsisten['idcliente'],
                'nombres' => $clientAsisten['nombres'],
                'apellidopaterno' => $clientAsisten['apellidopaterno'],
                'apellidomaterno' => $clientAsisten['apellidomaterno'],
                'email' => $clientAsisten['email'],
                'telefono' => $clientAsisten['telefono'],
                'edad' => $clientAsisten['edad'],
                'dni' => $clientAsisten['dni'],
                'fecharegistro' => $clientAsisten['fecharegistro'],
                'coddistrito' => $clientAsisten['coddistrito'],
                'utm' => $clientAsisten['utm'],
                'campaign_content' => $clientAsisten['campaign_content'],
                'campaign_medium' => $clientAsisten['campaign_medium'],
                'campaign_name' => $clientAsisten['campaign_name'],
                'campaign_source' => $clientAsisten['campaign_source'],
                'campaign_term' => $clientAsisten['campaign_term'],
                'procedencia' => $clientAsisten['procedencia'],
                'grado' => $clientAsisten['grado'],
                'carrera' => $clientAsisten['carrera'],
                'origen' => $clientAsisten['origen'],
                'tipoparticipante' => $clientAsisten['tipoparticipante'],
                'colegio' => $clientAsisten['colegio'],
                'anioegreso' => $clientAsisten['anioegreso'],
                'uuidimportacion' => $clientAsisten['uuidimportacion'],
                'asistencia' => $clientAsisten['asistencia'],
                'fechaasistencia' => Carbon::now()->format('y-m-d H:i:s'),
                'estado' => $clientAsisten['estado'],
            ]);

            $clientAsistenget = Cliente::where([
                'email' => $data->email,
                'idcampania' => $data->idcampania,
                'estado' => RuleManager::ACTIVE_STATE
            ])->with('eventoCliente')->firstOrFail();

            DB::commit();
            

            $result = ResultManager::successMessageData('SE REGISTRO EL ALUMNO EN EL STAND EXITOSAMENTE.', $clientAsistenget);

        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                $result = ResultManager::warningMessage('El participante ya está registrado en este stand.');
            } else {
                LogErrorManager::saveInDB($this, __FUNCTION__, $e);
                $result = ResultManager::errorMessage($errorMsg);
            }            
            DB::rollBack();
            dd($e);
        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::warningMessage($errorMsg);            
            DB::rollBack();
            dd($e); 
        }

        return $result;
    }


    public function markAttendanceEntry(Request $request)
    {

        $result = '';
        $errorMsg = 'No se registro la asistencia, intente realizar la busqueda nuevamente.';

        try {
            
            if(Auth::user()->idrol == RuleManager::PERSONAL_STAND_SALE_ACCESS){
    
                return $result = $this->saveStandSaleManual($request);
            }

            $clientAsisten = Cliente::where(['idcliente' => $request->idcliente, 'idcampania' => $request->idcampania])->first();
            
            $clientAsisten->asistencia = 1;

            $anioAsistencia = (int)optional($clientAsisten->fechaasistencia)->format('Y');

            if ($anioAsistencia < 1970) {
                $clientAsisten->fechaasistencia = Carbon::now()->format('y-m-d H:i:s');
            }

            $clientAsisten->update();

            $result = ResultManager::successMessageData('Asistencia Registrada Exitosamente.', $clientAsisten);

        } catch (QueryException $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::errorMessage($errorMsg);
        } catch (Exception $e) {
            LogErrorManager::saveInDB($this, __FUNCTION__, $e);
            $result = ResultManager::errorMessage($errorMsg);
        }

        return $result;
    }

    
}
