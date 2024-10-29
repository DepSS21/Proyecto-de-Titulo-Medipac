<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receta extends Model
{
    protected $table = 'Receta';
    protected $primaryKey = 'id_receta';

    protected $fillable = [
        'fecha_creacion',
        'Diagnostico',
        'comentarios',
        'id_medico',
        'id_paciente'
    ];

    public $timestamps = false;

    // Relación con paciente
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'id_paciente', 'id_paciente');
    }

    // Relación con médico
    public function medico()
    {
        return $this->belongsTo(Medico::class, 'id_medico', 'id_medico');
    }

    
}

