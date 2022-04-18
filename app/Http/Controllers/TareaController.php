<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tarea;
use App\TareaEstudiante;
use DB;

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
     
        $tarea = DB::SELECT("SELECT * 
        FROM tareas t
       
        WHERE t.id_seccion = ? 
        AND t.estado = '1' 
        AND t.fecha_entrega >= '$fechaActual'
        ",[$request->id_seccion]
        );

       
   
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

            $TareasEstudiante = DB::select("SELECT t.* ,r.id ,r.url, r.fecha_create,r.archivo ,r.descripcion,r.nota,r.comentario ,CONCAT(u.nombres, ' ', u.apellidos) as estudiante, u.cedula
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

    public function respuesta(Request $request){
         //para ingresar tarea
         $idusuario = $request->idusuario;
         $idtarea = $request->idtarea;
         $descripcion = $request->descripcion;
         $file = $request->archivo;
         $extension = $request->extension;
         $codigo = $request->codigo;
  
     
    
         DB::INSERT("INSERT INTO `tareas_estudiante`(`id_tarea`, `archivo`,`url`,`id_estudiante`, `descripcion`) VALUES (?,?,?,?,?)",[$idtarea,$file,$codigo,$idusuario,$descripcion]);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
