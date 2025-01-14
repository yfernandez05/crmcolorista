<?php

namespace App\Models;

use App\Models\Alumno;
use App\Models\Carrera;
use App\User;
use App\Util\RuleManager;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    protected $table = 'inscripciones';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'detalle',
        'fecha',
        'alumno_id',
        'carrera_id',
        'user_id',
        'estado'
    ];

    protected $hidden = [
        'created_usr', 
        'created_at', 
        'updated_usr', 
        'updated_at',
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

    public function scopeFecha(Builder $query,$fecha){
        $query->whereDate('fecha', Carbon::createFromFormat('d-m-Y', $fecha)->toDateString());
    }
}
