<?php

namespace App\Models;

use App\Models\Matricula;
use App\Util\RuleManager;
use App\Models\ConceptoPago;
use App\Models\Tipocomprobante;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'matricula_id',
        'subtotal',
        'detalle',
        'user_id',
        'codcomprobante',
        'serie',
        'numero',
        'estado',
    ];

    protected $hidden = [
        'created_usr',
        'updated_usr',
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'isactive',
        'statename',
    ];

    public function getIsactiveAttribute()
    {
        return RuleManager::getIsActive($this->estado);
    }

    public function getStatenameAttribute()
    {
        return RuleManager::getStateName($this->estado);
    }

    public function matricula()
    {
        return $this->belongsTo(Matricula::class, 'matricula_id', 'id');
    }

    public function detalles()
    {
        return $this->hasMany(DetallePago::class, 'pago_id', 'id')->with('conceptopago');
    }
    public function comprobante()
    {
        return $this->belongsTo(Tipocomprobante::class, 'codcomprobante', 'codcomprobante');
    }

}
