<?php

namespace App\Http\Controllers;

use App\DocumentosMinisteriales;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DocumentosMinisterialesController extends Controller
{
    public function index()
    {
        $documentos = DB::SELECT("SELECT * FROM `mst_documentos_ministeriales`");
        return $documentos;
    }

    public function show($id_documento)
    {
        $documento = DB::SELECT("SELECT * FROM `mst_documentos_ministeriales` WHERE `id_ministeriales` = $id_documento;");
        return $documento;
    }

    public function store(Request $request)
    {
        $ruta = public_path('images/ministeriales');

        if( $request->id_ministeriales != 0 ){

            $documento = DocumentosMinisteriales::find($request->id_ministeriales);

            if($request->file('img_portada') && $request->file('img_portada') != null && $request->file('img_portada')!= 'null'){
                $file = $request->file('img_portada');
                $fileName = uniqid().$file->getClientOriginalName();
                $file->move($ruta, $fileName);
                if( file_exists('images/ministeriales/'.$request->img_old) && $request->img_old != '' ){
                    unlink('images/ministeriales/'.$request->img_old);
                }
            }else{
                $fileName = $request->img_old;
            }

        }else{
            $documento = new DocumentosMinisteriales();

            $file = $request->file('img_portada');
            $ruta = public_path('images/ministeriales');
            $fileName = uniqid().$file->getClientOriginalName();
            $file->move($ruta, $fileName);
        }

        $documento->titulo = $request->titulo;
        $documento->id_usuario = $request->id_usuario;
        $documento->descripcion = $request->descripcion;
        $documento->imagen = $fileName;
        $documento->save();

        return $documento;

    }

    public function eliminarDocumento($id_documento, $imagen)
    {

        $documentos = DocumentosMinisteriales::findOrFail($id_documento);
        if (file_exists('images/ministeriales/' . $imagen)) {
            unlink('images/ministeriales/' . $imagen);
        }

        $documentos->delete();
    }

}
