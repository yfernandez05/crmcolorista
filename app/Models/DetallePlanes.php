<?php

namespace App\Models;

use App\Models\Curso;
use App\Models\PlanEstudio;
use Illuminate\Database\Eloquent\Model;

class DetallePlanes extends Model
{
    protected $table = 'detalle_planes';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'plan_id',
        'curso_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function planestudio()
    {
        return $this->belongsTo(PlanEstudio::class, 'plan_id', 'id');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id', 'id');
    }
}
