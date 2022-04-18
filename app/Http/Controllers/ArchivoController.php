<?php

namespace App\Http\Controllers;

use App\Models\Archivo;
use Illuminate\Http\Request;
use DB;
use Dirape\Token\Token;
use Carbon\Carbon;
class ArchivoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $archivos = DB::SELECT("SELECT * FROM archivo WHERE usuario_idusuario = ? AND estado = '1'",[$request->idusuario]);
        // return $archivos;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    public function dragArchivo(Request $request){
    return "good";
    }
    public function store(Request $request)
    {
        
        $idusuario = $request->idusuario;
        $file = $request->file('archivo');
        $extension = $request->file('archivo')->extension();
        $ruta = public_path('./tareas/');
        $codigo = uniqid().'.'.$extension;
        $archivo = $file->getClientOriginalName();
        $file->move($ruta,$codigo);
     
        return ["nombreArchivo" => $archivo, "extensionArchivo" => $extension,"codigoArchivo" => $codigo];


      
        


    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Archivo  $archivo
     * @return \Illuminate\Http\Response
     */
   

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Archivo  $archivo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Archivo $archivo)
    {
        $archivo = Archivo::find($archivo->idarchivo)->update(['estado' => '0']);
        return $archivo;
    }
}
