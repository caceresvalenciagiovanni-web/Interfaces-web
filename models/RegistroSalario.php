<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RegistroSalario extends Model {
    protected $table = 'RegistroSalario';
    protected $primaryKey = 'idRegistro'; // Ajusta si tu PK es diferente
    public $timestamps = false;
    protected $fillable = ['idVendedor', 'fecha', 'salarioBase', 'comisiones'];
// Relación: Un registro de salario pertenece a un Vendedor (Persona)
    public function vendedor()
    {
        return $this->belongsTo(Persona::class, 'idVendedor', 'idPersona');
    }
}
