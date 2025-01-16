<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipocomprobante extends Model
{
    protected $table ='tipocomprobantes';
    protected $primaryKey ='codcomprobante';
    public $timestamps = false;



    protected $fillable=[
        'nombrecomprobante',
        'codigosunat',
        'serie',
        'correlativo',
        'agregarigv',
        'estado',
    ];

    protected $hidden = [
        'userIng',
        'fechaIng',
        'userUpd',
        'fechaUpd',
    ];
}
