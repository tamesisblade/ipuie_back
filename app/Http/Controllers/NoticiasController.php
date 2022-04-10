<?php

namespace App\Http\Controllers;

use App\Noticias;
use Illuminate\Http\Request;
use DB;
use Illuminate\Provider\Image;
use Mail;
use Illuminate\Support\Facades\File;

class NoticiasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $noticias = DB::select("SELECT * FROM not_noticias WHERE estado = 1 ORDER BY updated_at");

        return $noticias;
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

        $ruta = public_path('images/noticias');

        if( $request->id_noticia != 0 ){

            $noticia = Noticias::find($request->id_noticia);

            if($request->file('img_portada') && $request->file('img_portada') != null && $request->file('img_portada')!= 'null'){
                $file = $request->file('img_portada');
                $fileName = uniqid().$file->getClientOriginalName();
                $file->move($ruta,$fileName);
                if( file_exists('images/noticias/'.$request->img_old) && $request->img_old != '' ){
                    unlink('images/noticias/'.$request->img_old);
                }
            }else{
                $fileName = $request->img_old;
            }

        }else{
            $noticia = new Noticias();

            $file = $request->file('img_portada');
            $ruta = public_path('images/noticias');
            $fileName = uniqid().$file->getClientOriginalName();
            $file->move($ruta,$fileName);
        }

        $noticia->titulo = $request->titulo;
        $noticia->subtitulo = $request->subtitulo;
        $noticia->contenido = $request->contenido;
        $noticia->img_portada = $fileName;
        $noticia->user = $request->idusuario;
        $noticia->save();

        return $noticia;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id_noticia)
    {
        $noticia = DB::select("SELECT * FROM not_noticias WHERE id_noticia = $id_noticia");
        $data = array();
        $comentarios = '';//DB::SELECT("SELECT * FROM not_comentarios WHERE id_noticia = ?", [$id_noticia]);
        $data['items'] = [
            'noticia' => $noticia,
            'comentarios'=> $comentarios,
        ];

        return $data;
    }

    public function guardar_comentario(Request $request)
    {

        if( $request->id_comentario != 0 ){
            $comentario = comentarioes::find($request->id_comentario);
        }else{
            $comentario = new comentarioes();
        }

        $comentario->titulo = $request->titulo;
        $comentario->contenido = $request->contenido;
        $comentario->id_noticia = $request->id_noticia;
        $comentario->save();

        return $comentario;
    }

    public function elimiar_noticia($id_noticia)
    {
        $noticia = Noticias::find($id_noticia);
        $noticia->estado = 0;
        $noticia->save();
    }

    public function elimiar_comentario($id_comentario)
    {
        $comentario = Comentarios::find($id_comentario);
        $comentario->estado = 0;
        $comentario->save();
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
    public function enviar_mail(Request $request)
    {
        $correo = $request->correo;
        $telefono = $request->telefono;
        $mensaje = $request->mensaje;
        $envio = Mail::send('plantilla.envio_mail',
            [
                'correo' => $correo,
                'telefono' => $telefono,
                'mensaje' => $mensaje,
            ],

            function ($message) use ($correo, $telefono, $mensaje) {
                $message->from($correo);
                $message->to('stivenmartinez437@gmail.com')->bcc('alexandro2011.x1@gmail.com')->subject('Mensaje desde pagina web');
            }
        );
    }

}
