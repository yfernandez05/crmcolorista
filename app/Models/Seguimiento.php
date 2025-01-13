<?php

namespace App\Models;

use App\Models\Prospecto;
use App\Util\RuleManager;
use Illuminate\Database\Eloquent\Model;

class Seguimiento extends Model
{
    protected $table = 'seguimientos';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'atencion',
        'toque',
        'respuesta',
        'fecha',
        'prospecto_id',
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
    public function prospecto(){
        return $this->belongsTo(Prospecto::class, 'id', 'id');
    }
}
