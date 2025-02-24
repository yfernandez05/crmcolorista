<?php

namespace App\Models;
use App\Models\Alumno;
use App\Models\Matricula;
use App\Models\File;
use App\Util\RuleManager;
use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    protected $table = 'contratos';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'alumno_id', 
        'matricula_id', 
        'domicilio', 
        'fecha_inscripcion', 
        'monto_promocional', 
        'monto_preabonado', 
        'importe_restante', 
        'fecha_limitepago', 
        'duracion_modulo', 
        'cantidadveces', 
        'duracionhoras', 
        'file_id', 
        'estado', 
    ];

    protected $hidden = [
        'userinsert', 
        'dateinsert', 
        'userupdate', 
        'dateupdate',
    ];
    
    protected $casts = [
        'fecha_inscripcion' => 'datetime:Y-m-d H:i:s',
    ];

    protected $appends = [
        'isactive',
        'statename',
        'fecharegistro',
    ];

    public function getIsactiveAttribute(){
        return RuleManager::getIsActive($this->estado);
    }

    public function getStatenameAttribute(){
        return RuleManager::getStateName($this->estado);
    }

    public function getFecharegistroAttribute()
    {
        return optional($this->fecha_inscripcion)->format('d-m-Y');
    }

    public function alumno(){
        return $this->belongsTo(Alumno::class, 'alumno_id', 'id');
    }
    public function matricula(){
        return $this->belongsTo(Matricula::class, 'matricula_id', 'id');
    }

    public function file()
    {
        return $this->belongsTo(File::class, 'file_id');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleContrato::class, 'contrato_id', 'id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'matricula_id', 'matricula_id')
                    ->with(['detalles' => function ($query) {
                        $query->where('concepto_id', 1);
                    }]);
    }
}
