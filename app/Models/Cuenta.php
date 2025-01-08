<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Util\RuleManager;

class Cuenta extends Model
{
    protected $table = 'cuentas';
    protected $primaryKey = 'idcuenta';
    public $timestamps = false;

    protected $fillable = [
        'empresa', 
        'ruc', 
        'razonsocial', 
        'contrato', 
        'fechainicio', 
        'mensual', 
        'estado', 
    ];

    protected $hidden = [
        'userinsert', 
        'dateinsert', 
        'userupdate', 
        'dateupdate',
    ];

    protected $casts = [
        'fechainicio' => 'datetime:Y-m-d H:i:s',
    ];
    
    protected $appends = [
        'isactive',
        'statename',
        'fecharegistro',
        'horaregistro',
    ];

    public function getIsactiveAttribute(){
        return RuleManager::getIsActive($this->estado);
    }

    public function getStatenameAttribute(){
        return RuleManager::getStateName($this->estado);
    }
    
    public function getFecharegistroAttribute()
    {
        return optional($this->fechainicio)->format('d-m-Y');
    }
    public function getHoraregistroAttribute()
    {
        return optional($this->fechainicio)->format('H:i:s');
    }

}
