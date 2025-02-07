<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagoFile extends Model
{
    protected $table = 'pago_file';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'pago_id',
        'file_id',
    ];

    protected $hidden = [
        'userinsert',
        'dateinsert',
        'userupdate',
        'dateupdate',
    ];
}
