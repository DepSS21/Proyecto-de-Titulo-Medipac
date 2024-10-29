<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medico extends Model
{
    protected $table = 'Medico';
    protected $primaryKey = 'id_medico';

    protected $fillable = [
        'rut',
        'nombre',
        'especialidad'
    ];

    public $timestamps = false;

    // Relación con recetas
    public function recetas()
    {
        return $this->hasMany(Receta::class, 'id_medico', 'id_medico');
    }
}