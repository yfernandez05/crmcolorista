<?php

namespace App\Models;

use App\Models\Carrera;
use Illuminate\Database\Eloquent\Model;

class DetalleCiclo extends Model
{
    protected $table = 'detalle_ciclos';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'ciclo_id', 
        'carrera_id',
        'created_usr',
        'updated_usr',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function aula()
    {
        return $this->belongsTo(Carrera::class, 'carrera_id', 'id');
    }
}
