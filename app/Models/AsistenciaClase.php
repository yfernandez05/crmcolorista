<?php

namespace App\Models;

use App\Models\Alumno;
use App\User;
use App\Util\RuleManager;
use Illuminate\Database\Eloquent\Model;

class AsistenciaClase extends Model
{
    protected $table = 'asistencias_clases';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'alumno_id',
        'docente_id',
        'curso_id',
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
        'fechaasis',
    ];

    public function getIsactiveAttribute(){
        return RuleManager::getIsActive($this->estado);
    }

    public function getStatenameAttribute(){
        return RuleManager::getStateName($this->estado);
    }
    public function getFechaasisAttribute()
    {
        return optional($this->fecha_asistencia)->format('d-m-Y H:i:s');
    }

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'docente_id', 'id');
    }
    
}
