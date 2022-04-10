<?php

namespace App\Http\Controllers;

use App\Plantillas;
use Illuminate\Http\Request;
use DB;
use Illuminate\Provider\Image;
use Illuminate\Support\Facades\File;

class PlantillasController extends Controller
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

    public function uploadImagen(Request $request)
    {
        $ruta = public_path('images/plantillas');
        
        if( $request->id_plantilla ){

            if($request->file('img_plantilla') && $request->file('img_plantilla') != null && $request->file('img_plantilla')!= 'null'){
                $file = $request->file('img_plantilla');
                $fileName = uniqid().$file->getClientOriginalName();
                $file->move($ruta,$fileName);
                //eliminar imagen reemplazada
                if( file_exists('images/plantillas/'.$request->img_plantilla_old) && $request->img_plantilla_old != '' ){
                    unlink('images/plantillas/'.$request->img_plantilla_old);
                }

                $plantillas = DB::UPDATE("UPDATE `plantillas` SET `imagen`='$fileName' WHERE `id_plantilla`=$request->id_plantilla");
            }

            return $plantillas;

        }else{

            $plantillas = new Preguntas();
            
            if($request->file('img_plantilla')){
                $file = $request->file('img_plantilla');
                $ruta = public_path('images/plantillas');
                $fileName = uniqid().$file->getClientOriginalName();
                $file->move($ruta,$fileName);
    
                $plantillas->img_plantilla = $fileName;

                $plantillas->save();
            }

            return $plantillas;
            
        }

    }


    public function store(Request $request)
    {
        if( $request->id_plantilla ){
            $plantilla = Plantillas::find($request->id_plantilla);
        }else{
            $plantilla = new Plantillas();
        }

        //secuencia de eventos
        $plantilla->id_tipo_plantilla = $request->id_tipo_plantilla;
        $plantilla->id_mision = $request->id_mision;
        $plantilla->id_evento = $request->id_evento;
        $plantilla->responsable = $request->responsable;
        $plantilla->horaH = $request->horaH;
        $plantilla->duracion = $request->duracion;
        $plantilla->hora_real = $request->hora_real;
        //tripulaciones
        $plantilla->nominativo = $request->nominativo;
        $plantilla->id_aeronave = $request->id_aeronave;
        $plantilla->piloto = $request->piloto;
        $plantilla->copiloto = $request->copiloto;
        $plantilla->ing_vuelo = $request->ing_vuelo;
        //bitacora
        $plantilla->codigo = $request->codigo;
        $plantilla->frec2 = $request->frec2;
        $plantilla->latitud_ns = $request->latitud_ns;
        $plantilla->longitud_wo = $request->longitud_wo;
        

        //tareas generales
        $plantilla->wpt = $request->wpt;
        $plantilla->rumbo = $request->rumbo;
        $plantilla->distancia = $request->distancia;
        $plantilla->etaH = $request->etaH;
        $plantilla->etaM = $request->etaM;
        $plantilla->coordenadas = $request->coordenadas;
        $plantilla->latitud = $request->latitud;
        $plantilla->longitud = $request->longitud;
        $plantilla->gradosLatitud = $request->gradosLatitud;
        $plantilla->minutosLatitud = $request->minutosLatitud;
        $plantilla->segundosLatitud = $request->segundosLatitud;
        $plantilla->gradosLongitud = $request->gradosLongitud;
        $plantilla->minutosLongitud = $request->minutosLongitud;
        $plantilla->segundosLongitud = $request->segundosLongitud;
        $plantilla->frec = $request->frec;
        //zona de embarque
        $plantilla->posicion = $request->posicion;
        //zona de desembarque
        $plantilla->actividad = $request->actividad;
        $plantilla->referencia = $request->referencia;
        //zona de seguridad de desembarque
        $plantilla->datos = $request->datos;
        //formacion en vuelo
        $plantilla->id_tipo_formacion = $request->id_tipo_formacion;
        //apoyo fuegos
        $plantilla->referencia_blanco = $request->referencia_blanco;
        $plantilla->nombre_referencia = $request->nombre_referencia;
        $plantilla->caracteristicas = $request->caracteristicas;
        $plantilla->distancia_referencia = $request->distancia_referencia;
        $plantilla->salida_coordinacion = $request->salida_coordinacion;
        $plantilla->direccion_viraje = $request->direccion_viraje;
        $plantilla->segundo_rumbo_entrada = $request->segundo_rumbo_entrada;
        $plantilla->formacion_coordinacion = $request->formacion_coordinacion;
        $plantilla->rumbo = $request->rumbo;
        $plantilla->viraje_entrada = $request->viraje_entrada;
        $plantilla->formacion = $request->formacion;
        $plantilla->viraje_salida = $request->viraje_salida;
        $plantilla->rumbo_ingreso = $request->rumbo_ingreso;
        $plantilla->rumbo_salida = $request->rumbo_salida;
        $plantilla->kts1 = $request->kts1;
        $plantilla->kts2 = $request->kts2;
        $plantilla->fts1 = $request->fts1;
        $plantilla->fts2 = $request->fts2;

        $plantilla->save();
        
        return $plantilla;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $plantillas = DB::select("SELECT * FROM plantillas p, eventos e, responsables r WHERE p.id_evento = e.id_evento AND p.id_responsable = r.id_responsable AND id_mision = $id ORDER BY p.id_plantilla");

        return $plantillas;
    }


    
    public function plantillasTipo(Request $request)
    {
        $plantillas = DB::select("SELECT * FROM plantillas p LEFT JOIN eventos e ON p.id_evento = e.id_evento LEFT JOIN responsables r ON p.id_responsable = r.id_responsable LEFT JOIN aeronaves a ON p.id_aeronave = a.id_aeronave LEFT JOIN nominativos n ON p.id_nominativo = n.id_nominativo LEFT JOIN tipos_formacion tf ON p.id_tipo_formacion = tf.id_tipo_formacion LEFT JOIN tipos_reconocimiento tr ON p.id_tipo_reconocimiento = tr.id_tipo_reconocimiento WHERE p.id_mision = $request->id_mision AND p.id_tipo_plantilla = $request->id_tipo_plantilla ORDER BY p.id_plantilla");

        return $plantillas;
    }

    public function plantillasTipoSecuencia(Request $request)
    {
        $plantillas = DB::select("SELECT * FROM plantillas p LEFT JOIN eventos e ON p.id_evento = e.id_evento LEFT JOIN responsables r ON p.id_responsable = r.id_responsable LEFT JOIN aeronaves a ON p.id_aeronave = a.id_aeronave LEFT JOIN nominativos n ON p.id_nominativo = n.id_nominativo LEFT JOIN tipos_formacion tf ON p.id_tipo_formacion = tf.id_tipo_formacion LEFT JOIN tipos_reconocimiento tr ON p.id_tipo_reconocimiento = tr.id_tipo_reconocimiento WHERE p.id_mision = $request->id_mision AND p.id_tipo_plantilla = $request->id_tipo_plantilla ORDER BY p.hora_real");

        return $plantillas;
    }

    
    public function cargarTablaFraseologia($id_mision)
    {
        $vector_eventos = array(20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38);
        $vector_nominativos = array('BRINCO', 'PARRILLA', 'PIRATA', 'TANGO', 'DESTORNILLADOR', 'APAGÓN', 'CARGADOR', 'CARRUSEL', 'JARABE', 'VENTANA', 'CASA GRANDE', 'ROCA', 'RELIEVE', 'FENIX', 'NUBE', 'CUERVO', 'ZANCUDO', 'DUQUE', 'RATONERA');

        for( $i=0; $i<19; $i++ ){
            $plantilla = new Plantillas();
            $plantilla->id_mision = $id_mision;
            $plantilla->id_tipo_plantilla = 8;
            $plantilla->id_evento = $vector_eventos[$i];
            $plantilla->nominativo = $vector_nominativos[$i];
            $plantilla->save();
        }       

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $mision = Plantillas::find($id);
        $mision->delete();
    }


}
