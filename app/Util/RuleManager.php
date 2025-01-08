<?php

namespace App\Util;

class RuleManager
{

    const SYSTEM_ADMIN_ROLE = 1;
    const SYSTEM_COMPANY_ACCOUNT = 1;
    const FIELD_NAME_STATE = 'estado';
    const ACTIVE_STATE = 'A';
    const DISABLED_STATE = 'E';
    const ACTIVE_STATE_NAME = 'Activo';
    const DISABLED_STATE_NAME = 'Eliminado';
    const ADMINISTRATORS_ACCESS = [1, 2];
    const UNATTENDED_STATE_TYPE = 1;
    const STANDARD_ACCESS = [3];
    const REPORT_ACCESS = [4];
    const ASESOR_ACCESS = [5];
    const PERSONAL_ATTENDANCE_ACCESS = 6;
    const PERSONAL_STAND_ACCESS = 7;
    const PERSONAL_STAND_SALE_ACCESS = 8;

    const YEAR_CONFIG_QR = [
        2025 => ['color' => 'orange', 'description' => 'Cuarto año de secundaria', 'border' => '2px solid orange'],
        2024 => ['color' => 'blue', 'description' => 'Quinto año de secundaria', 'border' => '2px solid blue'],
        'default' => [
            'color' => 'white',
            'description' => 'Egresado',
            'border' => '2px solid gray',
            'textColor' => 'black'
        ],
    ];
    

    public static function getStateName($state = self::DISABLED_STATE)
    {
        return $state == self::ACTIVE_STATE ? self::ACTIVE_STATE_NAME : self::DISABLED_STATE_NAME;
    }

    public static function getIsActive($state = self::DISABLED_STATE)
    {
        return $state == self::ACTIVE_STATE ? true : false;
    }

    public static function getQRConfigColor(int $anioEgreso): array {
        return self::YEAR_CONFIG_QR[$anioEgreso] ?? self::YEAR_CONFIG_QR['default'];
    }

}
