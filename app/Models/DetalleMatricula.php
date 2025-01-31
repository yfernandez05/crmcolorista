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

    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function matricula()
    {
        return $this->belongsTo(Matricula::class, 'matricula_id', 'id');
    }
}
