<?php

namespace App\Models;

use App\User;
use App\Models\Matricula;
use App\Util\RuleManager;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    protected $table = 'alumnos';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellido',
        'fecha_nac',
        'correo',
        'user_id',
        'prospecto_id',
        //'asesoracargo',
        'dni',
        'direccion',
        'distrito',
        'celular',
        'empresa',
        'cargodesempenia',
        // 'colegiosegundario',
        //'inicioclases',
        //'turno',
        //'pago',
        'fecha_inscripcion',
        //'curso',
        'sexo',
        'trabajo',
        'contactoemergencia',
        'edad',
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
        'fecha_inscripcion' => 'datetime:Y-m-d',
    ];
    protected $dates = [
        'fecha_nac',
        'fecha_inscripcion',
    ];


    protected $appends = [
        'isactive',
        'statename',
        'fechanacimiento',
        'fechainscripcion',
        'nombrecompleto'
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

    public function getFechainscripcionAttribute()
    {
        return optional($this->fecha_nac)->format('d-m-Y');
    }

    public function getNombrecompletoAttribute()
    {
        $nombreCompleto = $this->nombre;

        if($this->apellido != null)
            $nombreCompleto = $nombreCompleto.' '.$this->apellido;

        return $nombreCompleto;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'alumno_id');
    }

}
