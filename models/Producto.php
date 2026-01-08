<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    // 1. Nombre de la tabla
    // Por defecto Eloquent busca "productos", pero es mejor ser explícito.
    protected $table = 'Producto';

    // 2. Clave Primaria
    // Por defecto Eloquent asume que se llama 'id', pero la tuya es 'idProducto'.
    protected $primaryKey = 'idProducto';

    // 3. Timestamps
    // Eloquent intenta llenar 'created_at' y 'updated_at' automáticamente.
    // Como tu tabla no tiene esas columnas, debemos desactivarlo para evitar errores.
    public $timestamps = false;

    // 4. Campos permitidos (Asignación Masiva)
    // Lista blanca de campos que se pueden guardar de golpe. ¡Seguridad!
    protected $fillable = [
        'nombre',
        'descripcion',
        'costo',
        'precio',
        'stock',
        'genero',
        'etapaEdad',
        'tipoProducto',
        'material',
        'categoria',
        'idProveedor'
    ];
    // ==========================================
    // DEFINICIÓN DE LA RELACIÓN (Muchos a 1)
    // ==========================================
    // Esto se lee: "Este Producto PERTENECE A un Proveedor"
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'idProveedor', 'idProveedor');
    }

// Cierre de la clase
}
