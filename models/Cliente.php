<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model {
    protected $table = 'Cliente';
    protected $primaryKey = 'idPersona';
    public $timestamps = false;
}
