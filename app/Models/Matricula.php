<?php

namespace App\Models;

use App\Util\RuleManager;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    protected $table = 'matriculas';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'detalle',
        'fecha',
        'inscripcion_id',
        'ciclo_id',        
        'periodo_id',
        'condicion_id',
        'turno_id',
        'user_id',
        'estado',
    ];

    protected $hidden = [
        'created_usr',
        'updated_usr',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        
    ];
    protected $dates = [
       
    ];


    protected $appends = [
        'isactive',
        'statename',
    ];

    public function getIsactiveAttribute(){
        return RuleManager::getIsActive($this->estado);
    }

    public function getStatenameAttribute(){
        return RuleManager::getStateName($this->estado);
    }

    
}
