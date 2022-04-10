<?php

namespace App\Http\Controllers;

use App\Nominativos;
use Illuminate\Http\Request;
use DB;

class NominativosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nominativos = DB::select("SELECT * FROM nominativos ORDER BY id_nominativo");

        return $nominativos;
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
        if( $request->id_nominativo ){
            $nominativos = Nominativos::find($request->id_nominativo);
        }else{
            $nominativos = new Nominativos();
        }

        $nominativos->id_nominativo = $request->id_nominativo;
        $nominativos->id_responsable = $request->id_responsable;
        $nominativos->horaH = $request->horaH;

        $nominativos->save();
        
        return $nominativos;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $nominativos = DB::select("SELECT * FROM nominativos ORDER BY id_nominativos");

        return $nominativos;
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
        $mision = Nominativos::find($id);
        $mision->delete();
    }
}
