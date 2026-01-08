<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    // 1. Configuración de tabla
    protected $table = 'Proveedor';
    protected $primaryKey = 'idProveedor';
    public $timestamps = false;

    // 2. Campos permitidos (Asumiendo que 'nombre' es el principal)
    // Puedes agregar más si tu tabla tiene dirección, teléfono, etc.
    protected $fillable = [
        'nombre'
    ];

    // ==========================================
    // DEFINICIÓN DE LA RELACIÓN (1 a Muchos)
    // ==========================================
    // Esto se lee: "Un Proveedor TIENE MUCHOS Productos"
    public function productos()
    {
        // Param 1: El modelo relacionado
        // Param 2: La llave foránea en la otra tabla ('idProveedor' en Producto)
        // Param 3: La llave local ('idProveedor' en Proveedor)
        return $this->hasMany(Producto::class, 'idProveedor', 'idProveedor');
    }
}
