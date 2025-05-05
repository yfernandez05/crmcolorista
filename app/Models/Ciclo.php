<?php

namespace App\Models;

use App\Util\RuleManager;
use App\Models\DetalleCiclo;
use Illuminate\Database\Eloquent\Model;

class Ciclo extends Model
{
    protected $table = 'ciclos';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'duracion',
        'preciomes',
        'descripcion',
        'estado',
    ];

    protected $hidden = [
        'created_usr',
        'created_at',
        'updated_usr',
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

    public function detalles()
    {
        return $this->hasMany(DetalleCiclo::class, 'ciclo_id', 'id');
    }
}
