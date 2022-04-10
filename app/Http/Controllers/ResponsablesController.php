<?php

namespace App\Http\Controllers;

use App\Responsables;
use Illuminate\Http\Request;
use DB;

class ResponsablesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $responsables = DB::select("SELECT * FROM responsables ORDER BY id_responsable");

        return $responsables;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if( $request->id_responsable ){
            $responsable = Responsables::find($request->id_responsable);
        }else{
            $responsable = new Responsables();
        }

        $responsable->id_evento = $request->id_evento;
        $responsable->id_responsable = $request->id_responsable;
        $responsable->horaH = $request->horaH;
        $responsable->save();
        
        return $responsable;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $responsables = DB::select("SELECT * FROM responsables ORDER BY id_responsable");

        return $responsables;
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
        $mision = Responsables::find($id);
        $mision->delete();
    }
}
