<?php

namespace App\Http\Controllers;

use App\TiposReconocimiento;
use Illuminate\Http\Request;
use DB;

class TiposReconocimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tiposReconocimiento = DB::select("SELECT * FROM tipos_reconocimiento ORDER BY id_tipo_reconocimiento");

        return $tiposReconocimiento;
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
        if( $request->id_tipo_reconocimiento ){
            $tiposReconocimiento = TiposReconocimiento::find($request->id_tipo_reconocimiento);
        }else{
            $tiposReconocimiento = new TiposReconocimiento();
        }

        $tiposReconocimiento->id_tipo_reconocimiento = $request->id_tipo_reconocimiento;
        $tiposReconocimiento->id_responsable = $request->id_responsable;
        $tiposReconocimiento->horaH = $request->horaH;
        $tiposReconocimiento->save();
        
        return $tiposReconocimiento;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tiposReconocimiento = DB::select("SELECT * FROM tipos_reconocimiento ORDER BY id_tipo_reconocimiento");

        return $tiposReconocimiento;
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
        $mision = TiposReconocimiento::find($id);
        $mision->delete();
    }
}
