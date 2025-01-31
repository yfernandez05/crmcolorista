<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use App\Models\Ciclo;
use App\Models\Turno;
use App\Models\Carrera;
use App\Models\Periodo;
use App\Models\Condicion;
use App\Util\RuleManager;
use App\Models\DetalleMatricula;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Matricula extends Model
{
    protected $table = 'matriculas';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'detalle',
        'fecha',
        'ciclo_id',
        //'periodo_id',
        //'condicion_id',
        'turno_id',
        'alumno_id',
        'carrera_id',
        'user_id',
        'estado',
    ];

    protected $hidden = [
        'created_usr',
        'updated_usr',
        'created_at',
        'updated_at',
    ];

    protected $dates = [

    ];


    protected $casts = [
        'fecha' => 'datetime',
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

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'alumno_id', 'id');
    }

    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'carrera_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function ciclo()
    {
        return $this->belongsTo(Ciclo::class, 'ciclo_id', 'id');
    }
    public function turno()
    {
        return $this->belongsTo(Turno::class, 'turno_id', 'id');
    }

    public function scopeFecha(Builder $query,$fecha){
        $query->whereDate('fecha', Carbon::createFromFormat('d-m-Y', $fecha)->toDateString());
    }

    public function detalles()
    {
        return $this->hasMany(DetalleMatricula::class, 'matricula_id', 'id');
    }

}
