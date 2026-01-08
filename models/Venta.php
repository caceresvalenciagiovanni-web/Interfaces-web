<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'Venta';
    protected $primaryKey = 'idVenta';
    public $timestamps = false;

    // Campos que permitiremos guardar masivamente
    protected $fillable = [
        'fecha',
        'idCliente',
        'idEmpleado',
        'idMetodoPago',
        'total'
    ];

    // RELACIÓN: Una Venta tiene muchos Detalles
    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class, 'idVenta', 'idVenta');
    }
}
