<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    // 1. Apuntamos a la tabla correcta
    protected $table = 'Persona';

    // 2. Definimos la clave primaria
    protected $primaryKey = 'idPersona';

    // 3. Desactivamos timestamps automáticos
    public $timestamps = false;

    // 4. Campos que se pueden llenar (según lo que vi en tu código anterior)
    protected $fillable = [
        'nombre',
        'apellidoP',
        'apellidoM',
        'telefono',
        'direccion'
        // Agrega otros campos si tu tabla tiene más columnas
    ];
}
