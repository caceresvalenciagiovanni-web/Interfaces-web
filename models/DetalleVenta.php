<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $table = 'DetalleVenta';
    public $timestamps = false;
    
    // Asumimos que tiene una clave primaria autoincremental, 
    // si no la tiene, Eloquent igual puede guardar datos.
    // protected $primaryKey = 'idDetalleVenta'; 

    protected $fillable = [
        'idVenta',
        'idProducto',
        'cantidad',
        'precioUnitario'
    ];

    // RELACIÓN: Un Detalle pertenece a un Producto
    // (Esto nos servirá para saber qué se vendió)
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'idProducto', 'idProducto');
    }
}
