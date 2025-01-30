<?php

namespace App\Models;

use App\User;
use App\Util\RuleManager;
use Illuminate\Database\Eloquent\Model;

class Importacion extends Model
{
    protected $table = 'importaciones';
    protected $primaryKey = 'idimportacion';
    public $timestamps = false;

    protected $fillable = [
            'nombrearchivo', 
            'fecharegistro', 
            'cantregistros', 
            'iduser', 
            'uuidimportacion',
            'estado', 
    ];

    protected $hidden = [
        'userinsert', 
        'dateinsert', 
        'userupdate', 
        'dateupdate',
    ];

    protected $casts = [
     'fecharegistro' => 'datetime:Y-m-d H:i:s',
    ];

    protected $appends = [
        'isactive',
        'statename',
        'fecha',
    ];

    public function getIsactiveAttribute(){
        return RuleManager::getIsActive($this->estado);
    }

    public function getStatenameAttribute(){
        return RuleManager::getStateName($this->estado);
    }
    
    public function getFechaAttribute()
    {
     return optional($this->fecharegistro)->format('d-m-Y');
    }
     
    public function user(){
        return $this->belongsTo(User::class, 'iduser', 'id');
    }
    
}
