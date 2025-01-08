<?php

namespace App\Models;

use App\Util\RuleManager;
use Illuminate\Database\Eloquent\Model;

class ButtonMessage extends Model
{
    protected $table = 'buttonmessages';
    protected $primaryKey ='idbutton';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'backgroundColor',
        'textColor',
        'descriptionwhatsapp',
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
