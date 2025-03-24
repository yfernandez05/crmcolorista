<?php

namespace App\Models;

use App\Models\Carrera;
use App\Models\Ciclo;
use App\Models\DetallePlanes;
use App\Util\RuleManager;
use Illuminate\Database\Eloquent\Model;

class PlanEstudio extends Model
{
    protected $table = 'plan_estudios';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'costo',
        'duracion_meses',
        'carrera_id',
        'ciclo_id',
        'estado'
    ];

    protected $hidden = [
        'created_usr',
        'updated_usr',
        'created_at',
        'updated_at',
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

    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'carrera_id', 'id');
    }

    public function ciclo()
    {
        return $this->belongsTo(Ciclo::class, 'ciclo_id', 'id');
    }

    public function detalles()
    {
        return $this->hasMany(DetallePlanes::class, 'plan_id', 'id')->with('curso');
    }
}
