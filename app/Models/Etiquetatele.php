<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Util\RuleManager;

class Etiquetatele extends Model
{
    protected $table = 'etiquetatelefonica';
    protected $primaryKey ='idetiquetatele';
    public $timestamps = false;

    protected $fillable = [
        'etiquetatele',
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
