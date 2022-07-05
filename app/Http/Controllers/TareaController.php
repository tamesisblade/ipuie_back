<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tarea;
use App\TareaEstudiante;
use DB;
use Illuminate\Provider\Image;
use Illuminate\Support\Facades\File;

class TareaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tareas = DB::SELECT("SELECT * FROM tareas
        WHERE id_seccion = '$request->id_seccion'");
        return $tareas;
    }

    public function tareaEstudiantePendiente(Request $request){
        $data=array();
        $fechaActual  = date('Y/m/d');

        $tarea = DB::SELECT("SELECT * FROM tareas t WHERE t.id_seccion = ? AND t.estado = '1' ",[$request->id_seccion] );

        foreach ($tarea as $key => $post) {
            $verifica = DB::SELECT("SELECT * FROM tareas_estudiante WHERE id_tarea = ? AND id_estudiante = ?",[$post->id_tarea,$request->idusuario]);
            if(!empty($verifica)){

            }else{
                array_push ($data , $post);
            }
        }
        return $data;
    }


    public function tareaEstudianteRealizada(Request $request){

        if($request->docente){

            $TareasEstudiante = DB::select("SELECT t.* ,r.id ,r.url, r.fecha_create,r.archivo ,r.descripcion,r.nota,r.comentario ,CONCAT(u.nombres, ' ', u.apellidos) as estudiante, u.cedula, u.email
            FROM tareas t, tareas_estudiante r , usuario u
            WHERE  r.id_tarea = t.id_tarea
            AND  r.id_estudiante = u.idusuario
            AND  t.id_seccion = $request->id_seccion
            AND   t.estado = '1'

            AND  r.id_tarea = $request->id_tarea

            ");

            return $TareasEstudiante;
        }

        else{

            $data=array();
            $tarea = DB::SELECT("SELECT * FROM tareas WHERE id_seccion = ? AND estado = '1' ",[$request->id_seccion]);

            foreach ($tarea as $key => $post) {
                $verifica = DB::SELECT("SELECT * FROM tareas_estudiante r join tareas t on t.id_tarea = r.id_tarea
                  WHERE r.id_tarea = ? AND r.id_estudiante = ?",[$post->id_tarea,$request->idusuario]);
                if(!empty($verifica)){
                    array_push ($data , $verifica[0]);
                }else{
                }
            }
            return $data;
        }

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

    public function makeid(){
        $characters = '123456789abcdefghjkmnpqrstuvwxyz';
        $charactersLength = strlen($characters);

        $randomString = '';
        for ($i = 0; $i < 5; $i++) {
            for ($i = 0; $i < 16; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;


         }
    }
    public function store(Request $request)
    {
        if($request->id_tarea > 0){
            $tarea = Tarea::findOrFail($request->id_tarea);
            $tarea->id_seccion =  $request->id_seccion;
            $tarea->observaciones =  $request->observaciones;
            $tarea->id_seccion =  $request->id_seccion;
            $tarea->fecha_entrega =  $request->fecha_entrega;
            $tarea->estado =  $request->estado;
            //si envia foto
            if($request->archivo_old){
                if(file_exists('tareas/'.$request->archivo_old) ){
                    unlink('tareas/'.$request->archivo_old);

                }
                //guardar el nuevo archiv
                $traercodigo = $this->makeid();
                $files = $request->file('archivo');
                $path = "/tareas/";
                $filename = $traercodigo."".$files->getClientOriginalName();
                if($files->move(public_path().$path,$filename)){
                    $tarea->archivo =  $traercodigo."".$files->getClientOriginalName();
                    $tarea->tarea = $files->getClientOriginalName();
                }
            }

            $tarea->save();
            return "se guardo correctamente";

        }
            $traercodigo = $this->makeid();
            $files = $request->file('archivo');
            // foreach($files as $clave => $file){

                $path = "/tareas/";
                $filename = $traercodigo."".$files->getClientOriginalName();

                 if($files->move(public_path().$path,$filename)){

                Tarea::create([
                            "tarea" => $files->getClientOriginalName(),
                            "id_seccion" => $request->id_seccion,
                            "archivo" => $traercodigo."".$files->getClientOriginalName(),
                            "observaciones" => $request->observaciones,
                            'fecha_entrega' => $request->fecha_entrega,
                            'estado' => $request->estado,

                        ]);
                }
           // }

         return "se guardo correctamente";


    }

    public function guardar_tarea(Request $request){

        $ruta = public_path('./tareas/');
        $file = $request->file('archivo');
        $fileName = uniqid().$file->getClientOriginalName();
        $file->move($ruta, $fileName);

        DB::INSERT("INSERT INTO `tareas_estudiante`(`id_tarea`, `archivo`, `url`, `id_estudiante`, `descripcion`) VALUES (?,?,?,?,?)",[$request->idtarea, $fileName, $fileName, $request->idusuario, $request->descripcion]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function editarCalificacionTarea(Request $request){
        $verificarsiExiste = DB::select("SELECT * FROM tareas_estudiante WHERE id = $request->idrespuesta");

        if(!empty($verificarsiExiste)){
             DB::table('tareas_estudiante')
            ->where('id', $request->idrespuesta)
            ->update([
                'nota' => $request->nota,
                'comentario' => $request->observacion
        ]);

            return ["status"=> "1","message" => "Se guardo correctamente"];
        }else{
            return ["status"=> "0","message" => "Ocurrio un error al guardar"];
        }

    }

    public function eliminarTareaEstudiante(Request $request){

        $tarea = TareaEstudiante::findOrFail($request->idrespuesta);

        $verificarsiExiste = DB::select("SELECT * FROM tareas_estudiante WHERE id = $request->idrespuesta");
        if(!empty($verificarsiExiste)){
            $archivo  = $request->url;
            if(file_exists('tareas/'.$archivo) ){
                unlink('tareas/'.$archivo);
            }

            $tarea->delete();
            return ["status"=> "1","message" => "Se devolvio correctamente la tarea"];
        }else{
            return ["status"=> "0","message" => "No se pudo devolver la tarea al estudiante"];
        }
    }


    public function reporte_curso_tareas($id_curso)
    {
        $data = array();

        $cant_tareas = DB::SELECT("SELECT COUNT(*) AS cantidad FROM tareas t, cur_secciones_cursos s WHERE t.id_seccion = s.id_seccion AND s.id_curso = $id_curso;");

        $estudiantes = DB::SELECT("SELECT DISTINCT e.id, e.id_estudiante, e.id_curso, u.cedula, u.email, u.nombres,
        u.apellidos
        FROM estudiantes_cursos e, usuario u
        WHERE e.id_estudiante = u.idusuario
        AND e.id_curso = $id_curso");

        foreach ($estudiantes as $key => $value) {
            $calificaciones = DB::SELECT("SELECT t.nota FROM tareas_estudiante t
            INNER JOIN tareas tr ON t.id_tarea = tr.id_tarea
            INNER JOIN cur_secciones_cursos s ON tr.id_seccion = s.id_seccion
            WHERE s.id_curso = ? AND t.id_estudiante = ? AND tr.estado = 1;", [$id_curso, $value->id_estudiante]);

            $data[$key] = [
                'id_estudiante' => $value->id_estudiante,
                'nombres' => $value->nombres,
                'apellidos' => $value->apellidos,
                'email' => $value->email,
                'cedula' => $value->cedula,
            ];

            if( count($calificaciones) > 0 ){
                foreach ($calificaciones as $key_c => $value_c) {
                    array_push($data[$key], $value_c->nota);
                }
            }else{
                for( $i=0; $i<$cant_tareas[0]->cantidad; $i++ ){
                    array_push($data[$key], 0);
                }
            }


        }

        return response()->json(['data' => $data, 'cantidad' => $cant_tareas[0]->cantidad ]);

    }


}
