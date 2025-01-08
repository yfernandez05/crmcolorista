<?php

namespace App\Models;

use App\Util\RuleManager;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
   
    protected $table = 'cursos';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nombre', 
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
}
