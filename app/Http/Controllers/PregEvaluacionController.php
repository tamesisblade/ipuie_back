<?php

namespace App\Http\Controllers;

use App\OpcionPregunta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;///instanciamos base de datos para poder hacer consultas con varias tablas
use App\Pre_eva; //modelo Pre_eva.php
use App\Preguntas;

class PregEvaluacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $preguntas = DB::SELECT("SELECT * FROM `pre_evas` WHERE `id_evaluacion` = $request->id_evaluacion AND `grupo` = $request->grupo AND `id_pregunta` = $request->id_pregunta");

        if( count($preguntas) > 0 ){
            return 0;
        }else{
            $pregunta = new Pre_eva();
            $pregunta->id_evaluacion = $request->id_evaluacion;
            $pregunta->id_pregunta = $request->id_pregunta;
            $pregunta->grupo = $request->grupo;
            $pregunta->save();
    
            return $pregunta;
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $preguntas = DB::SELECT("SELECT p.puntaje_pregunta, p.id as id, p.id_tema, p.descripcion, p.img_pregunta, pe.id as 'id_pre_evas', p.id_tipo_pregunta, pe.id_evaluacion, ti.nombre_tipo FROM preguntas p, pre_evas pe, tipos_preguntas ti WHERE pe.id_pregunta = p.id AND ti.id_tipo_pregunta = p.id_tipo_pregunta AND pe.id_evaluacion = $id ORDER BY RAND()");

        if(!empty($preguntas)){
            foreach ($preguntas as $key => $value) {
                $opciones = DB::SELECT("SELECT id_opcion_pregunta, id_pregunta, opcion, img_opcion, tipo FROM opciones_preguntas WHERE opciones_preguntas.id_pregunta = ? ORDER BY RAND()",[$value->id]);
                $data['items'][$key] = [
                    'id' => $value->id,
                    'id_pre_evas' => $value->id_pre_evas,
                    'id_evaluacion' => $value->id_evaluacion,
                    'descripcion' => $value->descripcion,
                    'img_pregunta' => $value->img_pregunta,
                    'puntaje_pregunta' => $value->puntaje_pregunta,
                    'id_tipo_pregunta' => $value->id_tipo_pregunta,
                    'nombre_tipo' => $value->nombre_tipo,
                    'opciones'=>$opciones,
                ];            
            }
        }else{
            $data = [];
        }
        return $data;

    }

    
    public function pregEvaluacionGrupo(Request $request)
    {
        $preguntas = DB::SELECT("SELECT p.id as id, p.id_tema, p.descripcion, p.puntaje_pregunta, p.img_pregunta, pe.id as 'id_pre_evas',
         p.id_tipo_pregunta, pe.id_evaluacion, ti.nombre_tipo, ti.indicaciones
         FROM preguntas p, pre_evas pe, tipos_preguntas ti
          
          WHERE pe.id_pregunta = p.id 
          AND ti.id_tipo_pregunta = p.id_tipo_pregunta
           AND pe.id_evaluacion = $request->evaluacion 
           AND p.estado=1
            AND pe.grupo = '1' ORDER BY RAND()");

        if(!empty($preguntas)){
            foreach ($preguntas as $key => $value) {
                $opciones = DB::SELECT("SELECT id_opcion_pregunta, id_pregunta, opcion,  tipo 
                FROM opciones_preguntas
                 WHERE opciones_preguntas.id_pregunta = ?
                  ORDER BY RAND()",[$value->id]);
                $data['items'][$key] = [
                    'id' => $value->id,
                    'id_pre_evas' => $value->id_pre_evas,
                    'id_evaluacion' => $value->id_evaluacion,
                    'descripcion' => $value->descripcion,
                    'img_pregunta' => $value->img_pregunta,
                    'id_tipo_pregunta' => $value->id_tipo_pregunta,
                    'nombre_tipo' => $value->nombre_tipo,
                    'indicaciones' => $value->indicaciones,
                  
                    'puntaje_pregunta' => $value->puntaje_pregunta,
            
                    'opciones'=>$opciones,
                ];            
            }
        }else{
            $data = [];
        }
        return $data;
    }

    public function guardarEvaluacion(Request $request){
      
        //GUARDAR PREGUNTA
            $pregunta = new Preguntas();
            $pregunta->descripcion = $request->pregunta;         
            $pregunta->id_tipo_pregunta = $request->id_tipo_pregunta;
            $pregunta->puntaje_pregunta = '1';
            $pregunta->idusuario = $request->usuario;
            $pregunta->save();

        //GUARDAR PREGUNTA EVALUACION
           $pregunta_eva = new Pre_eva();
            $pregunta_eva->id_evaluacion = $request->evaluacion_id;
            $pregunta_eva->id_pregunta = $pregunta->id;
            $pregunta_eva->grupo = '1';
            $pregunta_eva->save();

            //si la pregunta es con respuestas
            if($request->conRespuestas){
                //para guardar la respuesta
                $respuesta = new OpcionPregunta();
                $respuesta->id_pregunta = $pregunta->id;
                $respuesta->opcion = $request->opcion1;
                $respuesta->tipo = $request->tipo1;
                $respuesta->save();

                //segunda respuesta
                $respuesta2 = new OpcionPregunta();
                $respuesta2->id_pregunta = $pregunta->id;
                $respuesta2->opcion = $request->opcion2;
                $respuesta2->tipo = $request->tipo2;
                $respuesta2->save();

                //tercera respuesta
                $respuesta3 = new OpcionPregunta();
                $respuesta3->id_pregunta = $pregunta->id;
                $respuesta3->opcion = $request->opcion3;
                $respuesta3->tipo = $request->tipo3;
                $respuesta3->save();

                //cuarta respuesta
                $respuesta4 = new OpcionPregunta();
                $respuesta4->id_pregunta = $pregunta->id;
                $respuesta4->opcion = $request->opcion4;
                $respuesta4->tipo = $request->tipo4;
                $respuesta4->save();
            }
                
            
            return ["status" => "0" , "message" => "se guardo correctamente"];

    }



    public function pregEvaluacionEstudiante(Request $request)
    {
        $preguntas = DB::SELECT("SELECT DISTINCT rp.puntaje, rp.id_respuesta_pregunta, rp.respuesta, (SELECT GROUP_CONCAT(op.id_opcion_pregunta) FROM opciones_preguntas op WHERE op.id_pregunta = pe.id_pregunta AND op.tipo=1) as respuestas_seleccion, (SELECT GROUP_CONCAT(op.opcion) FROM opciones_preguntas op WHERE op.id_pregunta = pe.id_pregunta AND op.tipo=1) as respuestas_escritas, p.id as id, p.descripcion, p.puntaje_pregunta, p.img_pregunta, pe.id as 'id_pre_evas', p.id_tipo_pregunta, pe.id_evaluacion, ti.nombre_tipo
        FROM preguntas p, pre_evas pe, tipos_preguntas ti,  respuestas_preguntas rp 
    
        WHERE pe.id_pregunta = p.id 
        AND ti.id_tipo_pregunta = p.id_tipo_pregunta 
        AND pe.id_evaluacion = $request->evaluacion 
        AND pe.grupo = $request->grupo 
        AND p.estado=1 
        AND rp.id_evaluacion = pe.id_evaluacion 
        AND rp.id_pregunta = pe.id_pregunta 
        AND rp.id_estudiante=$request->estudiante 
        ORDER BY p.id_tipo_pregunta");

        if(!empty($preguntas)){
            foreach ($preguntas as $key => $value) {
                $opciones = DB::SELECT("SELECT DISTINCT id_opcion_pregunta, id_pregunta, opcion, img_opcion, tipo FROM opciones_preguntas WHERE opciones_preguntas.id_pregunta = ?",[$value->id]);
                $data['items'][$key] = [
                    'id' => $value->id,
                    'id_pre_evas' => $value->id_pre_evas,
                    'id_evaluacion' => $value->id_evaluacion,
                    'descripcion' => $value->descripcion,
                    'img_pregunta' => $value->img_pregunta,
                    'id_tipo_pregunta' => $value->id_tipo_pregunta,
                    'nombre_tipo' => $value->nombre_tipo,
                    'puntaje_pregunta' => $value->puntaje_pregunta,
                   
                    'puntaje' => $value->puntaje,
                    'id_respuesta_pregunta' => $value->id_respuesta_pregunta,
                    'respuesta' => $value->respuesta,
                    'respuestas_seleccion' => $value->respuestas_seleccion,
                    'respuestas_escritas' => $value->respuestas_escritas,
                    'opciones'=>$opciones,
                ];            
            }
        }else{
            $data = [];
        }
        return $data;
    }



    
    public function verRespEstudianteEval(Request $request)
    {
        $respuestas = DB::SELECT("SELECT p.id_tipo_pregunta, COUNT(op.id_opcion_pregunta) as cantidadTipo, COUNT(DISTINCT op.id_pregunta) as cantPregTipo FROM preguntas p, pre_evas pe, opciones_preguntas op WHERE op.id_pregunta = pe.id_pregunta AND op.tipo = 1 AND pe.id_pregunta = p.id AND pe.id_evaluacion = $request->evaluacion GROUP BY p.id_tipo_pregunta");

        if(!empty($respuestas)){
            foreach ($respuestas as $key => $value) {
                $opciones = DB::SELECT("SELECT op.id_pregunta, op.id_opcion_pregunta, op.opcion, op.img_opcion, p.puntaje_pregunta FROM opciones_preguntas op, preguntas p, pre_evas pe WHERE op.id_pregunta = p.id AND op.tipo = 1 AND p.id_tipo_pregunta = ? AND pe.id_pregunta = p.id AND pe.id_evaluacion = ?",[$value->id_tipo_pregunta, $request->evaluacion]);

                $data['items'][$key] = [
                    'id_tipo_pregunta' => $value->id_tipo_pregunta,
                    'cantidadTipo' => $value->cantidadTipo,
                    'cantPregTipo' => $value->cantPregTipo,
                    'opciones'=>$opciones,
                ];            
            }
        }else{
            $data = [];
        }
        return $data;

    }


    
    public function getRespuestasGrupo(Request $request)
    {
        $respuestas = DB::SELECT("SELECT p.id_tipo_pregunta, COUNT(op.id_opcion_pregunta) as cantidadTipo,
         COUNT(DISTINCT op.id_pregunta) as cantPregTipo
          FROM preguntas p, pre_evas pe, opciones_preguntas op
           WHERE op.id_pregunta = pe.id_pregunta
            AND op.tipo = 1 
            AND pe.id_pregunta = p.id
             AND pe.id_evaluacion = $request->evaluacion 
             AND pe.grupo = $request->grupo 
             AND p.estado=1
              GROUP BY p.id_tipo_pregunta");

        if(!empty($respuestas)){
            foreach ($respuestas as $key => $value) {
                $opciones = DB::SELECT("SELECT op.id_pregunta, op.id_opcion_pregunta, op.opcion, 
                op.img_opcion, p.puntaje_pregunta
                 FROM opciones_preguntas op, preguntas p, pre_evas pe 
                 WHERE op.id_pregunta = p.id
                  AND op.tipo = 1 AND p.id_tipo_pregunta = ? 
                  AND pe.id_pregunta = p.id 
                  AND pe.id_evaluacion = ?
                   AND pe.grupo = ?",[$value->id_tipo_pregunta, $request->evaluacion, $request->grupo]);

                $data['items'][$key] = [
                    'id_tipo_pregunta' => $value->id_tipo_pregunta,
                    'cantidadTipo' => $value->cantidadTipo,
                    'cantPregTipo' => $value->cantPregTipo,
                    'opciones' => $opciones,
                ];            
            }
        }else{
            $data = [];
        }
        return $data;

    }


    public function getRespuestas($id)
    {
        $respuestas = DB::SELECT("SELECT p.id_tipo_pregunta, COUNT(op.id_opcion_pregunta) as cantidadTipo, COUNT(DISTINCT op.id_pregunta) as cantPregTipo FROM preguntas p, pre_evas pe, opciones_preguntas op WHERE op.id_pregunta = pe.id_pregunta AND op.tipo = 1 AND pe.id_pregunta = p.id AND pe.id_evaluacion = $id AND p.estado=1 GROUP BY p.id_tipo_pregunta");

        if(!empty($respuestas)){
            foreach ($respuestas as $key => $value) {
                $opciones = DB::SELECT("SELECT op.id_pregunta, op.id_opcion_pregunta, op.opcion, op.img_opcion, p.puntaje_pregunta FROM opciones_preguntas op, preguntas p, pre_evas pe WHERE op.id_pregunta = p.id AND op.tipo = 1 AND p.id_tipo_pregunta = ? AND pe.id_pregunta = p.id AND pe.id_evaluacion = ?",[$value->id_tipo_pregunta, $id]);

                $data['items'][$key] = [
                    'id_tipo_pregunta' => $value->id_tipo_pregunta,
                    'cantidadTipo' => $value->cantidadTipo,
                    'cantPregTipo' => $value->cantPregTipo,
                    'opciones' => $opciones,
                ];            
            }
        }else{
            $data = [];
        }
        return $data;

    }



    public function getRespuestasAcum(Request $request)
    {
        $respuestas = DB::SELECT("SELECT op.id_pregunta, GROUP_CONCAT(op.opcion) as opcion, GROUP_CONCAT(op.id_opcion_pregunta) AS id_opcion_pregunta, (SELECT p.puntaje_pregunta FROM preguntas p WHERE p.id = op.id_pregunta) AS puntaje_pregunta, GROUP_CONCAT(DISTINCT op.cant_coincidencias) AS cant_coincidencias FROM opciones_preguntas op, preguntas p, pre_evas pe WHERE op.id_pregunta = p.id AND op.tipo = 1 AND pe.id_pregunta = p.id AND pe.id_evaluacion = ? AND pe.grupo = ? GROUP BY op.id_pregunta",[$request->evaluacion, $request->grupo]);

        return $respuestas;
    }



    public function clasifGrupEstEval(Request $request)
    {   
        $estudiantes = explode(",",$request->estudiantes);
        $interval = intval(intval($request->cantidad) / intval($request->grupos));
        $ini_interval = 0;
        $fin_interval = $interval;

        for( $i=1; $i<=$request->grupos; $i++ ){
            for( $j=$ini_interval; $j<$fin_interval; $j++ ){
                if( $request->cantidad > $j ){
                    
                    DB::UPDATE("UPDATE estudiante SET grupo = ? WHERE usuario_idusuario = ? AND codigo = ?",[$i, $estudiantes[$j], $request->codigo]);

                }
            }
            $ini_interval = $ini_interval + $interval;
            $fin_interval = $fin_interval + $interval;
        }
    }



    
    public function preguntasxbancoDocente(Request $request)
    {   
        if( $request->tipo == 'null' ){
            $preguntas = DB::SELECT("SELECT ti.nombre_tipo, ti.descripcion_tipo, preguntas.id, preguntas.id_tema, preguntas.descripcion, preguntas.img_pregunta, preguntas.id_tipo_pregunta, preguntas.puntaje_pregunta, evaluaciones.nombre_evaluacion, temas.nombre_tema, temas.clasificacion, temas.unidad, evaluaciones.id_asignatura FROM preguntas, evaluaciones, temas, tipos_preguntas ti WHERE preguntas.idusuario = $request->usuario AND ti.id_tipo_pregunta = preguntas.id_tipo_pregunta AND evaluaciones.id_asignatura = temas.id_asignatura AND preguntas.id_tema = temas.id AND preguntas.estado = 1 AND evaluaciones.id = $request->evaluacion AND temas.estado=1 AND temas.unidad = $request->unidad AND preguntas.id NOT IN (select id_pregunta from pre_evas where id_evaluacion = $request->evaluacion AND grupo = $request->grupo) ORDER BY preguntas.descripcion DESC");
        }else{
            $preguntas = DB::SELECT("SELECT ti.nombre_tipo, ti.descripcion_tipo, preguntas.id, preguntas.id_tema, preguntas.descripcion, preguntas.img_pregunta, preguntas.id_tipo_pregunta, preguntas.puntaje_pregunta, evaluaciones.nombre_evaluacion, temas.nombre_tema, temas.clasificacion, temas.unidad, evaluaciones.id_asignatura FROM preguntas, evaluaciones, temas, tipos_preguntas ti WHERE preguntas.idusuario = $request->usuario AND ti.id_tipo_pregunta = preguntas.id_tipo_pregunta AND evaluaciones.id_asignatura = temas.id_asignatura AND preguntas.id_tema = temas.id AND preguntas.estado = 1 AND evaluaciones.id = $request->evaluacion AND temas.estado=1 AND temas.unidad = $request->unidad AND preguntas.id_tipo_pregunta = $request->tipo AND preguntas.id NOT IN (select id_pregunta from pre_evas where id_evaluacion = $request->evaluacion AND grupo = $request->grupo) ORDER BY preguntas.descripcion DESC");
        }

        if(!empty($preguntas)){
            foreach ($preguntas as $key => $value) {
                $opciones = DB::SELECT("SELECT id_opcion_pregunta, id_pregunta, opcion, img_opcion, tipo FROM opciones_preguntas WHERE opciones_preguntas.id_pregunta = ?",[$value->id]);
                $data['items'][$key] = [
                    'id' => $value->id,
                    'idusuario' => $request->idusuario,
                    'id_tema' => $value->id_tema,
                    'unidad' => $value->unidad,
                    'nombre_tema' => $value->nombre_tema,
                    'nombre_evaluacion' => $value->nombre_evaluacion,
                    'id_asignatura' => $value->id_asignatura,
                    'descripcion' => $value->descripcion,
                    'img_pregunta' => $value->img_pregunta,
                    'id_tipo_pregunta' => $value->id_tipo_pregunta,
                    'nombre_tipo' => $value->nombre_tipo,
                    'descripcion_tipo' => $value->descripcion_tipo,
                    'puntaje_pregunta' => $value->puntaje_pregunta,
                    'clasificacion' => $value->clasificacion,    
                    'opciones'=>$opciones,
                ];            
            }
        }else{
            $data = [];
        }
        return $data;
    }


    
    public function preguntasxbancoProlipa(Request $request)
    {   
        if( $request->tipo == 'null' ){
            $preguntas = DB::SELECT("SELECT ti.nombre_tipo, ti.descripcion_tipo, preguntas.id, preguntas.id_tema, preguntas.descripcion, preguntas.img_pregunta, preguntas.id_tipo_pregunta, preguntas.puntaje_pregunta, evaluaciones.nombre_evaluacion, temas.nombre_tema, temas.clasificacion, temas.unidad, evaluaciones.id_asignatura FROM preguntas, evaluaciones, temas, tipos_preguntas ti, usuario u WHERE ti.id_tipo_pregunta = preguntas.id_tipo_pregunta AND evaluaciones.id_asignatura = temas.id_asignatura AND preguntas.id_tema = temas.id AND preguntas.estado = 1 AND preguntas.idusuario = u.idusuario AND u.idusuario != $request->usuario AND evaluaciones.id = $request->evaluacion AND temas.estado=1 AND temas.unidad = $request->unidad AND preguntas.id NOT IN (select id_pregunta from pre_evas where id_evaluacion = $request->evaluacion AND grupo = $request->grupo) ORDER BY preguntas.descripcion DESC");
        }else{
            $preguntas = DB::SELECT("SELECT ti.nombre_tipo, ti.descripcion_tipo, preguntas.id, preguntas.id_tema, preguntas.descripcion, preguntas.img_pregunta, preguntas.id_tipo_pregunta, preguntas.puntaje_pregunta, evaluaciones.nombre_evaluacion, temas.nombre_tema, temas.clasificacion, temas.unidad, evaluaciones.id_asignatura FROM preguntas, evaluaciones, temas, tipos_preguntas ti, usuario u WHERE ti.id_tipo_pregunta = preguntas.id_tipo_pregunta AND evaluaciones.id_asignatura = temas.id_asignatura AND preguntas.id_tema = temas.id AND preguntas.estado = 1 AND preguntas.idusuario = u.idusuario AND u.idusuario != $request->usuario AND evaluaciones.id = $request->evaluacion AND preguntas.id_tipo_pregunta = $request->tipo AND temas.estado=1 AND temas.unidad = $request->unidad AND preguntas.id NOT IN (select id_pregunta from pre_evas where id_evaluacion = $request->evaluacion AND grupo = $request->grupo) ORDER BY preguntas.descripcion DESC");
        }
        

        if(!empty($preguntas)){
            foreach ($preguntas as $key => $value) {
                $opciones = DB::SELECT("SELECT id_opcion_pregunta, id_pregunta, opcion, img_opcion, tipo FROM opciones_preguntas WHERE opciones_preguntas.id_pregunta = ?",[$value->id]);
                $data['items'][$key] = [
                    'id' => $value->id,
                    'id_tema' => $value->id_tema,
                    'unidad' => $value->unidad,
                    'nombre_tema' => $value->nombre_tema,
                    'nombre_evaluacion' => $value->nombre_evaluacion,
                    'id_asignatura' => $value->id_asignatura,
                    'descripcion' => $value->descripcion,
                    'img_pregunta' => $value->img_pregunta,
                    'id_tipo_pregunta' => $value->id_tipo_pregunta,
                    'nombre_tipo' => $value->nombre_tipo,
                    'descripcion_tipo' => $value->descripcion_tipo,
                    'puntaje_pregunta' => $value->puntaje_pregunta,
                    'clasificacion' => $value->clasificacion,    
                    'opciones'=>$opciones,
                ];            
            }
        }else{
            $data = [];
        }
        return $data;
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
        //
    }

    
    public function quitarPregEvaluacion($id)
    {
        $pregunta = Pre_eva::find($id);
        $pregunta->delete();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pregunta = Pre_eva::find($id);
        $pregunta->delete();
    }
}