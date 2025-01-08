<?php

namespace App\Models;

use App\Util\RuleManager;
use Illuminate\Database\Eloquent\Model;

class EventoCliente extends Model
{
    protected $table = 'evento_cliente';
    protected $primaryKey = 'ideventocliente';
    public $timestamps = false;

    protected $fillable = [
        //'ideventocliente',
        'idevento', 
        'idcliente',

        'nombres', 
        'apellidopaterno', 
        'apellidomaterno', 
        'email', 
        'telefono', 
        'dni', 
        'fecharegistro', 
        'coddistrito', 
        'procedencia',
        'utm', 
        'campaign_content',
        'campaign_content',
        'campaign_medium',
        'campaign_name',
        'campaign_source',
        'campaign_term',
        'grado',
        'edad',
        'carrera', 
        'origen',
        'tipoparticipante',
        'colegio', 
        'anioegreso', 
        'uuidimportacion', 
        'idtipoatencion',

        'asistencia',
        'fechaasistencia',
    ];

    protected $hidden = [
        'userinsert', 
        'dateinsert', 
        'userupdate', 
        'dateupdate',
    ];

    protected $casts = [
        'fecharegistro' => 'datetime:Y-m-d H:i:s',
        'fechaasistencia'=> 'datetime:Y-m-d H:i:s',
       ];
   
    protected $appends = [
        'isactive',
        'statename',
        'fecha',
        'isassistance',
        'asistencianame',
        'fechapresencia'
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
    public function getfechapresenciaAttribute()
    {
        //return optional($this->fechaasistencia)->format('d-m-Y');
        return (int)optional($this->fechaasistencia)->format('Y') > 1970 ? 
        optional($this->fechaasistencia)->format('d-m-Y') : '';
    }

    public function getIsassistanceAttribute()
    {
        $isassistance = false;

        if($this->asistencia) $isassistance = true; 
        
        return $isassistance;
    }
    
    public function getAsistencianameAttribute()
    {
        $asistencianame = "No";

        if($this->asistencia) $asistencianame = "Si"; 
        
        return $asistencianame;
    }

    public function evento(){
        return $this->belongsTo(Evento::class, 'idevento','idevento')
        ->with('campania')
        ->whereHas('campania');
    }

    public function distrito(){
        return $this->belongsTo(Ubigeo::class, 'coddistrito', 'pkubigeo')
            ->withDefault([
                'pkubigeo'=>'',
                'fkubigeo'=>'',
                'nombreubigeo'=> '',
                'nivel' => -1,
                'nombrecompleto'=>'',
            ]);
    }

}
