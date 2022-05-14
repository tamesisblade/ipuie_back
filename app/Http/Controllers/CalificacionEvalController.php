<?php

namespace App\Http\Controllers;

 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;///instanciamos base de datos para poder hacer consultas con varias tablas
use App\Calificaciones;//modelo Calificaciones.php
use App\Evaluaciones;

class CalificacionEvalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Calificaciones::all();
            
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cantidadPreguntas = $request->cantidadPreguntas;
        $puntos = $request->puntos;
        $puntaje =$request->calificacion;
        $resultado = ($puntaje * $puntos) / $cantidadPreguntas;

        $calificacion = new Calificaciones();
        $calificacion->id_estudiante = $request->estudiante;
        $calificacion->id_evaluacion = $request->evaluacion;
        $calificacion->grupo = '1';
        $calificacion->calificacion = $resultado;

        $calificacion->save();

        return $calificacion;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }


    public function verifRespEvaluacion(Request $request)
    {
        $calificaciones = DB::SELECT("SELECT * FROM calificaciones WHERE id_evaluacion = $request->evaluacion AND id_estudiante = $request->estudiante");

        if($calificaciones){
            return $calificaciones;
        }else{
            return 0;
        }
    }


    
    public function modificarEvaluacion(Request $request)
    {
        $calificacion = DB::UPDATE("UPDATE `calificaciones` SET `calificacion`=$request->calificacion WHERE `id_evaluacion`=$request->evaluacion AND `id_estudiante`=$request->estudiante");

        $respuesta = DB::UPDATE("UPDATE `respuestas_preguntas` SET `puntaje`=$request->puntaje WHERE `id_respuesta_pregunta` = $request->id_respuesta");
    }



    public function guardarRespuesta(Request $request)
    {
        $cantidadPreguntas = $request->cantidadPreguntas;
        $puntos = $request->puntos;
        $puntaje =$request->puntaje;
        $resultado = ($puntaje * $puntos) / $cantidadPreguntas;

        $respuestas = DB::INSERT("INSERT INTO `respuestas_preguntas`(`id_evaluacion`, `id_pregunta`, `id_estudiante`, `respuesta`, `puntaje`) VALUES ($request->evaluacion, $request->pregunta, $request->estudiante, '$request->respuesta', $resultado)");  
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /*$calificacion = Calificaciones::find($id);
        $calificacion->id_estudiante = $request->estudiante;
        $calificacion->id_evaluacion = $request->evaluacion;
        $calificacion->calificacion = $request->calificacion;
        $calificacion->save();
        return $calificacion;*/
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $calificacion = Calificaciones::find($id);
        $calificacion->delete();
    }


     public function evaluacionEstudiante($id)
    {   
        $responder = DB::SELECT("SELECT *, now() as fecha_actual FROM evaluaciones e
         WHERE e.id = $id
          AND e.estado = 1");

        if($responder){
            return $responder;
        }else{
            return 0;
        }
    }
    
}