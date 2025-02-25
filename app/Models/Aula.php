<?php

namespace App\Models;

use App\Models\Carrera;
use App\Models\DetalleAulas;
use App\Util\RuleManager;
use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    protected $table = 'aulas';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'carrera_id',
        'estado',
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

    public function detalles()
    {
        return $this->hasMany(DetalleAulas::class, 'aula_id', 'id');
    }
}
