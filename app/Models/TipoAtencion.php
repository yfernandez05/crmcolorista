<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Util\RuleManager;

class TipoAtencion extends Model
{
    protected $table = 'tipoatenciones';
    protected $primaryKey ='idtipoatencion';
    public $timestamps = false;

    protected $fillable = [
        'tipoatencion',
        'backgroundColor',
        'textColor',
        'estado',
    ];

    protected $hidden = [
        'userinsert', 
        'dateinsert', 
        'userupdate', 
        'dateupdate',
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
