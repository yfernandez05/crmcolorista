<?php

namespace App\Models;

use App\Models\Aula;
use Illuminate\Database\Eloquent\Model;

class DetalleAulas extends Model
{
    protected $table = 'detalle_aulas';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'aula_id', 
        'nombre_aula',
        'foro_maximo',
        'created_usr',
        'updated_usr',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function aula()
    {
        return $this->belongsTo(Aula::class, 'aula_id', 'id');
    }
}
