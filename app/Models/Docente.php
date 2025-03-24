<?php

namespace App\Models;

use App\Models\DocenteCurso;
use App\User;
use App\Util\RuleManager;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    protected $table = 'docente';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
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

    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'id');
    }

    public function detalles()
    {
        return $this->hasMany(DocenteCurso::class, 'docente_id', 'id')->with('curso');
    }
}
