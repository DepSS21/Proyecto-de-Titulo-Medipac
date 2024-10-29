<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table = 'Paciente';
    protected $primaryKey = 'id_paciente'; 

    protected $fillable = [
        'rut_paciente',
        'edad',
        'nombre',
        'apellido',
        'fecha_nacimiento',
        'condicion_medica',
        'sexo',
        'numero_serie'
    ];

    
    public $timestamps = false;

    // Relación con recetas
    public function recetas()
    {
        return $this->hasMany(Receta::class, 'id_paciente', 'id_paciente');
    }
}