<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farmaceutico extends Model
{
    use HasFactory;

    protected $table = 'Farmaceutico'; 

    protected $fillable = [
        'nombre',
        'rut_farmaceutico',
    ];

    public $timestamps = false; 
}