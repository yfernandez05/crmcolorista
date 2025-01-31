<?php

namespace App\Models;

use App\Models\Matricula;
use Illuminate\Database\Eloquent\Model;

class DetalleMatricula extends Model
{
    protected $table = 'detalle_matriculas';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'matricula_id',
        'nombre',
        'duracion',
        'preciomes',
        'fechapago',

    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'fecha' => 'datetime',
    ];
    public function matricula()
    {
        return $this->belongsTo(Matricula::class, 'matricula_id', 'id');
    }

    public function scopeFecha(Builder $query,$fecha){
        $query->whereDate('fecha', Carbon::createFromFormat('d-m-Y', $fecha)->toDateString());
    }
}
