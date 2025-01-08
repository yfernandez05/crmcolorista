<?php

namespace App\Util;

use App\Models\LogError;

class LogErrorManager {

    public static function saveInDB($classUsed, $methodUsed, $exceptionUsed)
    {

        $logError = new LogError();

        $logError->classname = get_class($classUsed);
        $logError->methodname = $methodUsed;
        $logError->errormessage = $exceptionUsed->getMessage();
        $logError->save();

        return $logError;
    }
}