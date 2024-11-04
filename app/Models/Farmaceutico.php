<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farmaceutico extends Model
{
    use HasFactory;

    protected $table = 'Farmaceutico'; // Especificar la tabla Farmaceutico

    protected $fillable = [
        'nombre',
        'rut_farmaceutico',
    ];

    public $timestamps = false; // Deshabilitar las marcas de tiempo automáticas
}