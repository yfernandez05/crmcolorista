<?php

namespace App\Models;

use App\Models\Matricula;
use App\Models\ConceptoPago;
use App\Util\RuleManager;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'matricula_id',
        'concepto_id',
        'monto',        
        'detalle',
        'user_id',
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

    public function getIsactiveAttribute(){
        return RuleManager::getIsActive($this->estado);
    }

    public function getStatenameAttribute(){
        return RuleManager::getStateName($this->estado);
    }

    public function matricula()
    {
        return $this->belongsTo(Matricula::class, 'matricula_id', 'id');
    }

    public function conceptopago()
    {
        return $this->belongsTo(ConceptoPago::class, 'concepto_id', 'id');
    }

}
