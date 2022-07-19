<?php

namespace App\Http\Controllers;

use App\Eventos;
use Illuminate\Http\Request;
use DB;

class EventosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $agenda = DB::SELECT("SELECT *, CONCAT(a.title, ' (', a.hora_inicio, ' - ', a.hora_fin, ')' ) as title FROM agenda_usuario a");

       return $agenda;
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
    { $ruta = public_path('images/eventos');
        if( $request->id != 0 ){
            $agenda = Eventos::find($request->id);

            if($request->file('img_portada') && $request->file('img_portada') != null && $request->file('img_portada')!= 'null'){
                $file = $request->file('img_portada');
                $fileName = uniqid().$file->getClientOriginalName();
                $file->move($ruta,$fileName);
                if( file_exists('images/eventos/'.$request->img_old) && $request->img_old != '' ){
                    unlink('images/eventos/'.$request->img_old);
                }
            }else{
                $fileName = $request->img_old;
            }

        }else{
            $agenda = new Eventos();

            $file = $request->file('img_portada');
            $ruta = public_path('images/eventos');
            $fileName = uniqid().$file->getClientOriginalName();
            $file->move($ruta,$fileName);

        }


        $agenda->id_usuario = $request->idusuario;
        $agenda->title = $request->title;
        $agenda->img_portada = $fileName;
        $agenda->label = $request->label;
        $agenda->classes = $request->classes;
        $agenda->startDate = $request->startDate;
        $agenda->endDate = $request->endDate;
        $agenda->hora_inicio = $request->hora_inicio;
        $agenda->hora_fin = $request->hora_fin;
        $agenda->url = $request->url;
        $agenda->link = $request->link;

        $agenda->save();

        return $agenda;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $eventos = DB::select("SELECT * FROM eventos ORDER BY id_eventos");

        return $eventos;
    }


    public function ver_inscritos($id)
    {
        $inscritos = DB::SELECT("SELECT * FROM `agenda_inscritos` WHERE `id_agenda` = $id");

        return $inscritos;
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

    public function delete_agenda($id_agenda)
    {
        DB::DELETE("DELETE FROM `agenda_usuario` WHERE `id` = $id_agenda");
    }

    public function inscripcion_evento(Request $request)
    {
        DB::INSERT("INSERT INTO `agenda_inscritos`(`id_agenda`, `identificacion`, `nombres`, `apellidos`, `email`, `telefono`, `genero`) VALUES (?,?,?,?,?,?,?);", [$request->id_evento, $request->identificacion, $request->nombres, $request->apellidos, $request->email, $request->telefono, $request->genero]);
    }
}
