<?php

namespace App\Util;

class ResultManager {

    public static function createMessage($message, $status = false, $warning = false, $dataaditional = null)
    {
        return array("message"  => $message, "status"   => self::getStatus($status), "warning" => $warning, "dataaditional"  => $dataaditional);
    }

    public static function getMessage($result)
    {
        return self::getFirstItem($result);
    }

    public static function gerericErrorMessage()
    {
        return self::createMessage('Ocurrió un error inesperado. Si este problema continúa comunícate con asistencia técnica.');
    }

    public static function genericSuccessMessage()
    {
        return self::createMessage('Los datos ingresados se guardaron correctamente.', true);
    }

    public static function errorMessage($msg)
    {
       return self::createMessage('Ocurrió un error inesperado. '.$msg);
    }

    public static function successMessage($msg)
    {
        return self::createMessage($msg, true);
    }    
    public static function warningMessage($msg)
    {
        return self::createMessage($msg, false, true);
    }
    public static function successMessageData($msg,  $dtaaditional)
    {
        return self::createMessage($msg, true, false, $dtaaditional);
    }
    
    public static function getFirstItem(Array $array)
    {
        if(count($array)){
            return json_decode(json_encode($array), true)[0];
        }else{
            return json_encode((object)[]);
        }

    }

    private static function getStatus($status) : bool
    {
        $resultStatus ;

        if (is_bool($status)) 
            $resultStatus = (bool) $status;
        else
            $resultStatus = filter_var($status, FILTER_VALIDATE_BOOLEAN);

        return $resultStatus;
    }

}