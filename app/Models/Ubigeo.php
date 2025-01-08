<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ubigeo extends Model
{
    protected $table = 'ubigeos';
    protected $primaryKey = 'pkubigeo';
    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'fkubigeo',
        'nombreubigeo',
        'nivel',
        'nombrecompleto'
    ];
}
