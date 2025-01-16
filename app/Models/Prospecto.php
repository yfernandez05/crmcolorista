<?php

namespace App\Models;

use App\Models\Atencion;
use App\Util\RuleManager;
use Illuminate\Database\Eloquent\Model;

class Prospecto extends Model
{
    protected $table = 'prospectos';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellido',
        'fecha_nac',
        'telefono',
        'correo',
        'procedencia',
        'fecha_registro',
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
        'fecha_nac' => 'datetime:Y-m-d',
    ];
    protected $dates = [
        'fecha_nac',
    ];


    protected $appends = [
        'isactive',
        'statename',
        'fechanacimiento',
    ];

    public function getIsactiveAttribute(){
        return RuleManager::getIsActive($this->estado);
    }

    public function getStatenameAttribute(){
        return RuleManager::getStateName($this->estado);
    }

    public function getFechanacimientoAttribute()
    {
        return optional($this->fecha_nac)->format('d-m-Y');
    }

    public function ultimaatencion()
    {
        return $this->hasOne(Atencion::class, 'prospecto_id', 'id')
            ->withDefault([
                'prospecto_id'=>'',
                'idtipoatencion'=>1,
                'comentario'=> '',
                'tipoatencion'=>[
                    'idtipoatencion' => 1,
                    'tipoatencion' => 'Sin Atender',
                    "backgroundColor"=>'#ff0000',
                    "textColor"=>'#ffffff',
                    "estado"=>'A',
                    "isactive"=>true,
                    "statename"=>'Activo'
                ],
                'user'=>[
                    'name' => '',
                    'email' => '',
                    'email_verified_at' => '',
                    'idrol' => 3,
                    'idcuenta' => 2,
                ],
                'etiquetatelefonica'=>[
                    'etiquetatele' => 'Sin Atender',
                    'idetiquetatele' => 1,
                    "backgroundColor"=>'#ff0000',
                    "textColor"=>'#ffffff',
                    "estado"=>'A',
                    "isactive"=>true,
                    "statename"=>'Activo'
                ],
            ])
            ->with('tipoatencion','etiquetatelefonica','user')
            ->orderBy('idatencion', 'desc');
    }

}
