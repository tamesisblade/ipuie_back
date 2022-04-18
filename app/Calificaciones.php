<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Calificaciones extends Model
{
    protected $table = "calificaciones";
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_evaluacion', 'id_estudiante', 'calificacion', 'grupo'
    ];
	
}
