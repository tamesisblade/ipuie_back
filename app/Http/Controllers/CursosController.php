<?php

namespace App\Http\Controllers;

use App\Cursos;
use App\Secciones;
use Illuminate\Http\Request;
use DB;
use Illuminate\Provider\Image;

use Illuminate\Support\Facades\File;

class CursosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cursos = DB::select("SELECT * FROM cur_cursos WHERE estado = 1 ORDER BY titulo");

        return $cursos;
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

        $ruta = public_path('images/cursos');

        if( $request->id_curso != 0 ){

            $curso = Cursos::find($request->id_curso);

            if($request->file('img_portada') && $request->file('img_portada') != null && $request->file('img_portada')!= 'null'){
                $file = $request->file('img_portada');
                $fileName = uniqid().$file->getClientOriginalName();
                $file->move($ruta,$fileName);
                if( file_exists('images/cursos/'.$request->img_old) && $request->img_old != '' ){
                    unlink('images/cursos/'.$request->img_old);
                }
            }else{
                $fileName = $request->img_old;
            }

        }else{
            $curso = new Cursos();

            $file = $request->file('img_portada');
            $ruta = public_path('images/cursos');
            $fileName = uniqid().$file->getClientOriginalName();
            $file->move($ruta,$fileName);
        }

        $curso->titulo = $request->titulo;
        $curso->subtitulo = $request->subtitulo;
        $curso->capacitador = $request->capacitador;
        $curso->cant_horas = $request->cant_horas;
        $curso->precio = $request->precio;
        $curso->descuento = $request->descuento;
        $curso->certificado = $request->certificado;
        $curso->requisitos = $request->requisitos;
        $curso->descripcion = $request->descripcion;
        $curso->aprender = $request->aprender;
        $curso->img_portada = $fileName;
        $curso->user = $request->idusuario;
        $curso->save();

        return $curso;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id_curso)
    {
        $curso = DB::select("SELECT * FROM cur_cursos WHERE id_curso = $id_curso");
        $data = array();
        $secciones = DB::SELECT("SELECT * FROM cur_secciones_cursos WHERE id_curso = ? AND estado = 1", [$id_curso]);
        $data['items'] = [
            'curso' => $curso,
            'secciones'=>$secciones,
        ];

        return $data;
    }

    public function curso_estudiante($id_curso, $id_estudiante)
    {
        if( $id_estudiante != 0 ){
            $cursos = DB::SELECT("SELECT * FROM `estudiantes_cursos` WHERE `id_curso` = ? AND `id_estudiante` = ?", [$id_curso, $id_estudiante]);
        }else{
            $cursos = DB::SELECT("SELECT * FROM `estudiantes_cursos` WHERE `id_curso` = ?", [$id_curso]);
        }


        if( count($cursos) > 0 ){
            if( $cursos[0]->estado == 1 ){
                return 1;
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }

    public function solicitudes_usuarios()
    {
        $solicitudes = DB::SELECT("SELECT e.id_estudiante, e.solicitud, e.estado AS estado_inscripcion, c.*, u.cedula, u.nombres, u.apellidos, u.email, u.telefono, e.comprobante, e.forma_pago, e.valor FROM estudiantes_cursos e, usuario u, cur_cursos c WHERE e.id_curso = c.id_curso AND e.id_estudiante = u.idusuario ORDER BY e.created_at DESC;");

        return $solicitudes;
    }

    public function procesar_solicitud(Request $request)
    {
        $solicitud = DB::UPDATE("UPDATE `estudiantes_cursos` SET `estado`= ? WHERE `id_curso` = ? AND `id_estudiante` = ?", [$request->estado, $request->id_curso, $request->id_estudiante]);

        return $solicitud;
    }

    public function guardar_seccion(Request $request)
    {
        if( $request->id_seccion != 0 ){
            $seccion = Secciones::find($request->id_seccion);
        }else{
            $seccion = new Secciones();
        }

        $seccion->titulo = $request->titulo;
        $seccion->contenido = $request->contenido;
        $seccion->id_curso = $request->id_curso;
        $seccion->save();

        return $seccion;
    }

    public function elimiar_curso($id_curso)
    {
        $curso = Cursos::find($id_curso);
        $curso->estado = 0;
        $curso->save();
    }

    public function elimiar_seccion($id_seccion)
    {
        $seccion = Secciones::find($id_seccion);
        $seccion->estado = 0;
        $seccion->save();
    }


    public function inscripcion_curso(Request $request)
    {

        $cursos = DB::SELECT("SELECT * FROM `estudiantes_cursos` WHERE `id_curso` = ? AND `id_estudiante` = ?", [$request->id_curso, $request->id_estudiante]);

        if( count($cursos) > 0 ){ return 0; }

        $file = $request->file('comprobante');
        $ruta = public_path('images/comprobantes');
        $fileName = uniqid().$file->getClientOriginalName();
        $file->move($ruta,$fileName);

        $inscripcion_curso = DB::INSERT("INSERT INTO `estudiantes_cursos`(`id_curso`, `id_estudiante`, `solicitud`, `estado`, `forma_pago`, `valor`, `comprobante`) VALUES (?,?,?,?,?,?,?)", [$request->id_curso, $request->id_estudiante, $request->solicitud, $request->estado, $request->forma_pago, $request->valor, $fileName]);

        // return $inscripcion_curso;
    }
}
