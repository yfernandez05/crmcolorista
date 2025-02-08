<?php

namespace App\Models;

use App\Models\Contrato;
use Illuminate\Database\Eloquent\Model;

class DetalleContrato extends Model
{
    protected $table = 'detalle_contratos';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'contrato_id',
        'preciomes',
        'fechapago',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id', 'id');
    }
}
