<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;///instanciamos base de datos para poder hacer consultas con varias tablas
use App\Evaluaciones;//modelo Evaluaciones.php

class EvaluacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $evaluaciones = DB::SELECT("SELECT e.id, e.nombre_evaluacion,e.id_docente, e.descripcion, e.puntos, e.fecha_inicio, e.fecha_fin, e.duracion, e.estadoa FROM evaluaciones e");

        //return Evaluaciones::all();
        return $evaluaciones;

    }


    public function evaluacionesDocente(Request $request)
    {

        $evaluaciones = DB::SELECT("SELECT DISTINCT c.titulo as nombre_curso,
       e.codigo_curso, e.id, e.nombre_evaluacion, e.id_docente, e.descripcion, e.puntos, e.fecha_inicio,
          e.fecha_fin, e.duracion, e.estado
        FROM evaluaciones e,  cur_secciones_cursos c
        WHERE e.id_docente = '$request->docente'
        AND e.codigo_curso = '$request->codigo'
        AND e.seccion_id = '$request->seccion_id'
        AND e.codigo_curso = c.id_seccion");

        return $evaluaciones;
    }

    public function TiposEvaluacion()
    {
        $tiposevaluacion = DB::SELECT("SELECT tipo_nombre as label, id FROM eval_tipos WHERE tipo_estado = 1");
        return $tiposevaluacion;
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
    public function store(Request $request)//request datos que ingreso en los input del formulario
    {//agregar

        if( $request->id ){
            $evaluacion = Evaluaciones::find($request->id);
        }else{
            $evaluacion = new Evaluaciones();
        }

        $evaluacion->nombre_evaluacion = $request->nombre;

        $evaluacion->descripcion = $request->descripcion;
        $evaluacion->puntos = $request->puntos;
        $evaluacion->fecha_inicio = $request->fecha_inicio;
        $evaluacion->fecha_fin = $request->fecha_fin;
        $evaluacion->duracion = $request->duracion;
        $evaluacion->estado = $request->estado;
        $evaluacion->id_docente = $request->docente;
        $evaluacion->codigo_curso = $request->codigo;
        $evaluacion->seccion_id = $request->seccion_id;

        $evaluacion->save();

        return $evaluacion;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $evaluaciones = DB::SELECT("SELECT * FROM evaluaciones WHERE id = $id");

        if($evaluaciones){
            return $evaluaciones;
        }else{
            return 0;
        }
    }



    public function evaluacionesEstudianteCurso(Request $request)
    {
        $evaluaciones = DB::SELECT("SELECT DISTINCT  cu.titulo as nombre_curso,
       e.id, e.nombre_evaluacion,
         e.descripcion, e.puntos, e.fecha_inicio, e.fecha_fin, e.duracion,
          e.estado, es.id_estudiante as id_estudiante
            FROM evaluaciones e, estudiantes_cursos es, cur_secciones_cursos cu
             WHERE e.codigo_curso = es.id_curso
             AND e.seccion_id = '$request->seccion_id'
             AND e.estado = 1
             AND es.id_estudiante = '$request->estudiante'
             AND es.id_estudiante NOT IN (SELECT c.id_estudiante from calificaciones c WHERE c.id_evaluacion = e.id)
             AND es.id_curso = cu.id_curso
              AND cu.id_seccion = '$request->seccion_id'
              AND es.id_curso = '$request->codigo'
                ");

        return $evaluaciones;
    }


    public function evalCompleEstCurso(Request $request)
    {
        $evaluaciones = DB::SELECT("SELECT DISTINCT e.id, c.grupo, c.calificacion, e.nombre_evaluacion, e.descripcion,
         e.puntos, e.fecha_inicio, e.fecha_fin, e.duracion
          FROM calificaciones c, evaluaciones e, estudiantes_cursos es
           WHERE c.id_evaluacion = e.id
            AND c.id_estudiante = $request->estudiante
             AND e.codigo_curso = '$request->codigo'
             AND e.seccion_id = '$request->seccion_id'
              AND es.id_curso = e.codigo_curso
               AND es.id_estudiante = c.id_estudiante
                AND e.estado = 1
               ORDER BY e.id");

        return $evaluaciones;
    }


     public function verCalificacionEval($codigo,$seccion)
    {
        $estudiantes = DB::SELECT("SELECT DISTINCT e.id, e.id_estudiante, e.id_curso, u.cedula, u.nombres,
        u.apellidos, e.estado as estado_estudiante, u.estado_idEstado as estado_usuario,
         e.created_at
         FROM estudiantes_cursos e, usuario u
          WHERE e.id_estudiante = u.idusuario
           AND e.id_curso = $codigo
          ");


        if(!empty($estudiantes)){
            foreach ($estudiantes as $key => $value) {
                $calificaciones = DB::SELECT("SELECT DISTINCT e.id, e.nombre_evaluacion, e.puntos, e.duracion, es.id_estudiante,
                 (SELECT c.calificacion FROM calificaciones c
                  WHERE c.id_estudiante = es.id_estudiante
                  AND c.id_evaluacion = e.id) as calificacion
                 FROM evaluaciones e, estudiantes_cursos es
                  WHERE e.codigo_curso = ?
                  AND e.codigo_curso = es.id_curso
                  AND e.seccion_id = '$seccion'
                  AND es.id_estudiante = ?",[$codigo, $value->id_estudiante]);

                $total = DB::SELECT("SELECT DISTINCT * FROM evaluaciones e 
                WHERE e.codigo_curso = ?
                AND e.seccion_id = '$seccion'
                ",[$codigo]);

                $data['items'][$key] = [
                    'id' => $value->id,
                    'cedula' => $value->cedula,
                    'nombres' => $value->nombres,
                    'apellidos' => $value->apellidos,
                    'usuario_idusuario' => $value->id_estudiante,
                    'codigo' => $value->id_curso,
                    'estado_estudiante' => $value->estado_estudiante,
                    'estado_usuario' => $value->estado_usuario,
                    'created_at' => $value->created_at,
                    'calificaciones'=>$calificaciones,
                    'total'=>$total,
                ];
            }
        }else{
            $data = [];
        }
        return $data;
    }



    public function verEstCursoEval($id)
    {
        $estudiantes = DB::SELECT("SELECT DISTINCT e.grupo, u.idusuario, u.nombres, u.apellidos, u.cedula, u.email, u.telefono FROM estudiante e, usuario u WHERE e.codigo = '$id' AND e.usuario_idusuario = u.idusuario AND e.estado = '1' ORDER BY e.grupo");

        return $estudiantes;
    }


    public function asignarGrupoEst(Request $request)
    {
        $estudiantes = DB::UPDATE("UPDATE estudiante SET grupo = $request->grupo WHERE usuario_idusuario = $request->estudiante AND codigo = '$request->codigo'");

        return $estudiantes;
    }

    public function verEvalCursoExport($codigo,$seccion)
    {
        $evaluaciones = DB::SELECT("SELECT DISTINCT * FROM evaluaciones e
         WHERE e.codigo_curso = '$codigo'
         AND e.seccion_id = '$seccion'
         ");

        return $evaluaciones;
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
        $evaluacion = Evaluaciones::find($id);
        $evaluacion->nombre_evaluacion = $request->nombre_evaluacion;
        $evaluacion->descripcion = $request->descripcion;
        $evaluacion->puntos = $request->puntos;
        $evaluacion->fecha_inicio = $request->fecha_inicio;
        $evaluacion->fecha_fin = $request->fecha_fin;
        $evaluacion->estado = $request->estado;
        $evaluacion->save();

        return $evaluacion;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */



    public function eliminar_evaluacion($id_evaluacion)
    {
        $preguntas = DB::DELETE("DELETE FROM `pre_evas` WHERE `id_evaluacion` = $id_evaluacion");
        $eval = DB::DELETE("DELETE FROM `evaluaciones` WHERE `id` = $id_evaluacion");
    }




    public function destroy($id_evaluacion)
    {
        $evaluacion = Evaluaciones::find($id_evaluacion);
        $evaluacion->delete();
    }
}
