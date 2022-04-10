<?php

namespace App\Http\Controllers;

use App\TiposMisiones;
use Illuminate\Http\Request;
use DB;

class TiposMisionesController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tipos_misiones = DB::select("SELECT * FROM tipos_misiones where id_tipo_mision = $id");
        return $tipos_misiones;
    }


    public function plantillas_por_misiones($id)
    {
        $plantillas = DB::select("SELECT pm.orden, tm.id_tipo_mision, tm.nombre_tipo_mision, tm.descripcion_tipo_mision, tp.id_tipo_plantilla, tp.nombre_tipo_plantilla, tp.descripcion_tipo_plantilla, tp.link, tp.size FROM plantillas_por_misiones pm, tipos_misiones tm, tipos_plantillas tp WHERE pm.id_tipo_mision = tm.id_tipo_mision AND pm.id_tipo_plantilla = tp.id_tipo_plantilla AND pm.id_tipo_mision = $id ORDER BY pm.orden");
        return $plantillas;
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
        //
    }
}
