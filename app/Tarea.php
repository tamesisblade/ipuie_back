<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    protected $table = "tareas";
    protected $primaryKey = 'id_tarea';
    protected $fillable = [
        'id_seccion', 'tarea', 'fecha_entrega', 'archivo', 'observaciones', 'estado'
    ];
	
}
