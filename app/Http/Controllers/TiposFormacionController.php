<?php

namespace App\Http\Controllers;

use App\TiposFormacion;
use Illuminate\Http\Request;
use DB;

class TiposFormacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipos_formacion = DB::select("SELECT * FROM tipos_formacion ORDER BY id_tipo_formacion");

        return $tipos_formacion;
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
        if( $request->id_tipo_formacion ){
            $tipos_formacion = TiposFormacion::find($request->id_tipo_formacion);
        }else{
            $tipos_formacion = new TiposFormacion();
        }

        $tipos_formacion->id_tipo_formacion = $request->id_tipo_formacion;
        $tipos_formacion->id_responsable = $request->id_responsable;
        $tipos_formacion->horaH = $request->horaH;

        $tipos_formacion->save();
        
        return $tipos_formacion;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tipos_formacion = DB::select("SELECT * FROM tipos_formacion ORDER BY id_tipo_formacion");

        return $tipos_formacion;
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
        $mision = TiposFormacion::find($id);
        $mision->delete();
    }
}
