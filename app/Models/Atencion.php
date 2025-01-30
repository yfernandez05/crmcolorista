<?php

namespace App\Models;

use App\User;
use App\Util\RuleManager;
use App\Models\Etiquetatele;
use App\Models\TipoAtencion;
use Illuminate\Database\Eloquent\Model;

class Atencion extends Model
{
    protected $table = 'atenciones';
    protected $primaryKey = 'idatencion';
    public $timestamps = false;

    protected $fillable = [
        'iduser',
        'idcliente',
        'fechaatencion',
        'comentario',
        'idtipoatencion',
        'fechaagenda',
        'estado'
    ];

    protected $hidden = [
        'userinsert',
        'dateinsert',
        'userupdate',
        'dateupdate',
    ];
    protected $casts = [
        'fechaatencion' => 'datetime:Y-m-d H:i:s',
        'fechaagenda' => 'datetime:Y-m-d H:i:s',
    ];

    protected $appends = [
        'isactive',
        'statename',
        'fecha',
        'isattended',
        'fechaagendada',
    ];

    public function getIsactiveAttribute(){
        return RuleManager::getIsActive($this->estado);
    }

    public function getStatenameAttribute(){
        return RuleManager::getStateName($this->estado);
    }

    public function getIsattendedAttribute(){
        $attended = false;

        if($this->idatencion != null && $this->idatencion != '')
            $attended = true;

        return $attended;
    }

    public function getFechaAttribute()
    {
        return optional($this->fechaatencion)->format('d-m-Y');
    }

    public function user(){
        return $this->belongsTo(User::class, 'iduser', 'id');
    }
    public function tipoatencion(){
        return $this->belongsTo(TipoAtencion::class, 'idtipoatencion', 'idtipoatencion');
    }
    public function cliente(){
        return $this->belongsTo(Cliente::class, 'idcliente', 'idcliente');
    }

    public function etiquetatelefonica(){
        return $this->belongsTo(Etiquetatele::class, 'idetiquetatele', 'idetiquetatele');
    }
    public function getFechaagendadaAttribute()
    {
        return optional($this->fechaagenda)->format('d-m-Y H:i:s');
    }

}
