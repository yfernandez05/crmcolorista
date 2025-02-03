<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallePago extends Model
{
    protected $table = 'detallepagos';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'pago_id', 
        'concepto_id', 
        'precio_unitario', 
        'descuento', 
        'nombre_numero_mensualidad', 
        'importe',
        'created_usr',
        'updated_usr',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function pago()
    {
        return $this->belongsTo(Pago::class, 'pago_id', 'id');
    }
    
    public function conceptopago()
    {
        return $this->belongsTo(ConceptoPago::class, 'concepto_id', 'id');
    }
}
