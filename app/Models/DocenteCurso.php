<?php

namespace App\Models;

use App\Models\Curso;
use App\Util\RuleManager;
use Illuminate\Database\Eloquent\Model;

class DocenteCurso extends Model
{
    protected $table = 'docente_curso';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'docente_id',
        'curso_id',
        'estado',
    ];

    protected $hidden = [
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

    public function docente()
    {
        return $this->belongsTo(Docente::class, 'docente_id', 'id');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id', 'id');
    }
}
