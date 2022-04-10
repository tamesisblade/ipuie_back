<?php

namespace App\Http\Controllers;

use App\Misiones;
use Illuminate\Http\Request;
use DB;

class MisionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $misiones = DB::select("SELECT m.id_mision, m.id_tipo_mision, m.nombre_mision, m.fecha_mision, m.hora_ataque, m.observaciones, m.created_at FROM misiones m ORDER BY id_mision DESC");
        return $misiones;
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
        if( $request->id_mision ){
            $mision = Misiones::find($request->id_mision);
        }else{
            $mision = new Misiones();
        }

        $mision->id_tipo_mision = $request->id_tipo_mision;
        $mision->nombre_mision = $request->nombre_mision;
        $mision->fecha_mision = $request->fecha_mision;
        $mision->hora_ataque = $request->hora_ataque;
        $mision->observaciones = $request->observaciones;
        $mision->id_usuario = $request->idusuario;
        $mision->save();
        
        return $mision;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $misiones = DB::select("SELECT m.id_mision, m.id_tipo_mision, m.nombre_mision, m.fecha_mision, m.hora_ataque, m.observaciones, m.created_at FROM misiones m WHERE m.id_usuario = $id ORDER BY id_mision DESC");
        return $misiones;
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
        $mision = Misiones::find($id);
        $mision->delete();
    }
}
