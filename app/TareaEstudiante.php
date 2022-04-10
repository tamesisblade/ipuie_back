<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TareaEstudiante extends Model
{
    protected $table = "tareas_estudiante";
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_tarea', 'id_estudiante', 'archivo', 'url', 'comentario', 'descripcion','nota','fecha_create'
    ];
}
