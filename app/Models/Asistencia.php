<?php

namespace App\Models;

use App\Util\RuleManager;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $table = 'asistencias';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'alumno_id',
        'fecha_asistencia',
        'estado',
    ];

    protected $hidden = [
        'created_usr',
        'updated_usr',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'fecha_asistencia' => 'datetime:Y-m-d H:i:s',
    ];
    protected $dates = [
      //  'fecha_nac',
    ];


    protected $appends = [
        'isactive',
        'statename',
        'fecha',
    ];

    public function getIsactiveAttribute(){
        return RuleManager::getIsActive($this->estado);
    }

    public function getStatenameAttribute(){
        return RuleManager::getStateName($this->estado);
    }
    public function getFechaAttribute()
    {
        return optional($this->fecha_asistencia)->format('d-m-Y H:i:s');
    }
}
