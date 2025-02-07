<?php

namespace App\Models;
use App\Models\Venta;
use App\Models\cotizar;
use App\Models\Producto;

use App\Models\Ventatienda;
use App\Models\ProductoTienda;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'files';
    protected $primaryKey = 'file_id';
    public $timestamps = false;

    protected $fillable = [
            'nombre',
            'url_relative',
            'url_patch',
            'fecharegistro',
            'estado',
    ];

    protected $hidden = [
        'userinsert',
        'dateinsert',
        'userupdate',
        'dateupdate',
    ];

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'product_file', 'file_id', 'codproducto');
    }

    public function ventas()
    {
        return $this->belongsToMany(Venta::class, 'venta_file', 'file_id', 'codventa');
    }

    public function actaconformidad_ventas_file()
    {
        return $this->belongsToMany(Venta::class, 'actaconformidad_files', 'file_id', 'codventa');
    }

    public function ordenservicio_ventas_file()
    {
        return $this->belongsToMany(Venta::class, 'ordenservicio_files', 'file_id', 'codventa');
    }

    public function productostienda()
    {
        return $this->belongsToMany(ProductoTienda::class, 'productotienda_file ', 'file_id', 'codproductotienda');
    }

    public function estadofactura_ventatienda_file()
    {
        return $this->belongsToMany(Ventatienda::class, 'estadofactura_file_ventatienda', 'file_id', 'codventatienda');
    }

    public function actaconformidad_ventatienda_file()
    {
        return $this->belongsToMany(Ventatienda::class, 'actaconformidad_file_ventatienda', 'file_id', 'codventatienda');
    }

    public function ordenservicio_ventatienda_file()
    {
        return $this->belongsToMany(Ventatienda::class, 'ordenservicio_file_ventatienda', 'file_id', 'codventatienda');
    }
}
