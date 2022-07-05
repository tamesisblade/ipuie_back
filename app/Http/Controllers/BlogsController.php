<?php

namespace App\Http\Controllers;

use App\Blogs;
use Illuminate\Http\Request;
use DB;
use Illuminate\Provider\Image;
use Mail;
use Illuminate\Support\Facades\File;

class BlogsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogs = DB::SELECT("CALL get_blogs");

        return $blogs;
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

        $ruta = public_path('images/blogs');

        if( $request->id_blog != 0 ){

            $blog = Blogs::find($request->id_blog);

            if($request->file('img_portada') && $request->file('img_portada') != null && $request->file('img_portada')!= 'null'){
                $file = $request->file('img_portada');
                $fileName = uniqid().$file->getClientOriginalName();
                $file->move($ruta,$fileName);
                if( file_exists('images/blogs/'.$request->img_old) && $request->img_old != '' ){
                    unlink('images/blogs/'.$request->img_old);
                }
            }else{
                $fileName = $request->img_old;
            }

        }else{
            $blog = new Blogs();

            $file = $request->file('img_portada');
            $ruta = public_path('images/blogs');
            $fileName = uniqid().$file->getClientOriginalName();
            $file->move($ruta,$fileName);
        }

        $blog->titulo = $request->titulo;
        $blog->subtitulo = $request->subtitulo;
        $blog->contenido = $request->contenido;
        $blog->id_categoria = $request->id_categoria;
        $blog->img_portada = $fileName;
        $blog->user = $request->idusuario;
        $blog->save();

        return $blog;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id_blog)
    {
        $blog = DB::select("SELECT b.*, c.nombre_categoria, c.id_categoria FROM blogs b, categorias c WHERE b.id_blog = $id_blog AND b.id_categoria = c.id_categoria AND c.estado = 1 AND b.estado = 1;");
        $data = array();
        $comentarios = '';//DB::SELECT("SELECT * FROM not_comentarios WHERE id_blog = ?", [$id_blog]);
        $data['items'] = [
            'blog' => $blog,
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
        $comentario->id_blog = $request->id_blog;
        $comentario->save();

        return $comentario;
    }

    public function elimiar_blog($id_blog, $img_blog)
    {
        $blog = Blogs::find($id_blog);
        $blog->estado = 0;
        $blog->save();

        if( file_exists('images/blogs/'.$img_blog) ){
            unlink('images/blogs/'.$img_blog);
        }
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

    public function get_categorias()
    {
        $categorias = DB::select("SELECT * FROM categorias WHERE estado = 1 ORDER BY nombre_categoria");
        return $categorias;
    }

    public function guardar_categoria(Request $request)
    {
        DB::INSERT("INSERT INTO `categorias`(`nombre_categoria`, `id_usuario`) VALUES (?,?)", [$request->nombre_categoria, $request->id_usuario]);
    }

    public function guardar_valoracion_blog($id_blog, $usuario, $valoracion)
    {
        DB::INSERT("INSERT INTO `blog_valoraciones`(`id_blog`, `id_usuario`, `valoracion`) VALUES (?, ?, ?)", [$id_blog, $usuario, $valoracion]);
    }

    public function get_blogs_categoria($id_categoria)
    {
        $blogs = DB::SELECT("call get_blogs_categoria($id_categoria);");
        return $blogs;
    }

    public function eliminar_categoria($id_categoria)
    {
        DB::UPDATE("UPDATE `categorias` SET `estado`=0 WHERE `id_categoria` = $id_categoria");
    }

}
