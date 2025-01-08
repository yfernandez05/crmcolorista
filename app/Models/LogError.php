<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogError extends Model
{
    protected $table ='logerrors';
    protected $primaryKey ='id';
    public $timestamps = false;

    protected $fillable=[
        'classname',
        'methodname',
        'errormessage',
        'dateoccurred'
    ];
    protected $casts = [
        'fechainicio' => 'datetime:Y-m-d H:i:s',
    ];
    protected $appends = [
        'fecharegistro',
    ];

       public function getFecharegistroAttribute()
       {
           return optional($this->fechainicio)->format('d-m-Y');
       }
       public function getHoraregistroAttribute()
       {
           return optional($this->fechainicio)->format('H:i:s');
       }
   
}
