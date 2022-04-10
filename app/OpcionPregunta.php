<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OpcionPregunta extends Model
{
    
    protected $table = "opciones_preguntas";
    protected $primaryKey = 'id_opcion_pregunta';

    protected $fillable = [
        'id_pregunta',
        'opcion',  
        'tipo',
        'cant_coincidencias', 
       
    ];
    
}
