<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class User extends Model {
    protected $table = 'users'; // Asegúrate que tu tabla se llame 'users' en la BD
    public $timestamps = false;
    // Si tu clave primaria no es 'id', agrégalo aquí (ej: protected $primaryKey = 'idUser';)
}
