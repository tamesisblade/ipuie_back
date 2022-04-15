<?php

namespace App\Http\Controllers;

use App\Carruseles;
use App\Footer;
use Illuminate\Http\Request;
use DB;
use Illuminate\Provider\Image;

use Illuminate\Support\Facades\File;

class CarruselesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $carruseles = DB::SELECT("SELECT * FROM lay_carruseles WHERE tipo = 1");

        return $carruseles;
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

        $ruta = public_path('images/carrousel');

        if( $request->id_carrusel != 0 ){

            $carrusel = Carruseles::find($request->id_carrusel);

            if($request->file('img_carrusel') && $request->file('img_carrusel') != null && $request->file('img_carrusel')!= 'null'){
                $file = $request->file('img_carrusel');
                $fileName = uniqid().$file->getClientOriginalName();
                $file->move($ruta,$fileName);
                if( file_exists('images/carrousel/'.$request->img_old) && $request->img_old != '' ){
                    unlink('images/carrousel/'.$request->img_old);
                }
            }else{
                $fileName = $request->img_old;
            }

        }else{
            $carrusel = new Carruseles();

            $file = $request->file('img_carrusel');
            $ruta = public_path('images/carrousel');
            $fileName = uniqid().$file->getClientOriginalName();
            $file->move($ruta,$fileName);
        }

        $carrusel->titulo = $request->titulo;
        $carrusel->descripcion = $request->descripcion;
        $carrusel->texto = $request->texto;
        $carrusel->color = $request->color;
        $carrusel->color_texto = $request->color_texto;
        $carrusel->imagen = $fileName;
        $carrusel->tipo = $request->tipo;
        $carrusel->save();

        return $carrusel;

    }


    public function eliminar_carrusel($id_carrusel, $img)
    {
        $carrusel = DB::DELETE("DELETE FROM `lay_carruseles` WHERE `id_carrusel` = $id_carrusel");
        unlink('images/carrousel/'.$img);
    }


    public function get_parallax()
    {
        $parallax = DB::select("SELECT * FROM lay_carruseles WHERE tipo = 2 LIMIT 1");

        return $parallax;
    }
    public function get_fondo()
    {
        $fondo = DB::select("SELECT * FROM lay_carruseles WHERE tipo = 4 LIMIT 1");

        return $fondo;
    }

    public function get_lados_cubo()
    {
        $lados_cubo = DB::select("SELECT * FROM lay_carruseles WHERE tipo = 3");

        return $lados_cubo;
    }
    public function guardar_img_cubo(Request $request)
    {
        $carrusel = new Carruseles();

        $file = $request->file('img_carrusel');
        $ruta = public_path('images/carrousel');
        $fileName = uniqid().$file->getClientOriginalName();
        $file->move($ruta,$fileName);

        $carrusel->titulo = 0;
        $carrusel->descripcion = 0;
        $carrusel->color = 0;
        $carrusel->texto = 0;
        $carrusel->color_texto = 0;
        $carrusel->imagen = $fileName;
        $carrusel->tipo = 3;
        $carrusel->save();

        return $carrusel;
    }


    public function eliminar_img_cubo($id_cubo, $img)
    {
        DB::DELETE("DELETE FROM `lay_carruseles` WHERE `id_carrusel` = $id_cubo");
        unlink('images/carrousel/'.$img);
    }

    public function get_footer()
    {
        $footer = DB::SELECT("SELECT * FROM lay_footer");

        return $footer;
    }


    public function get_acerca()
    {
        $acerca = DB::select("SELECT * FROM acerca_de");

        return $acerca;
    }


    public function save_get_acerca(Request $request)
    {
        $acerca = DB::UPDATE("UPDATE `acerca_de` SET `contenido`=?,`cod_mapa`=? WHERE `id` = ?", [$request->contenido, $request->cod_mapa, $request->id]);

        return $acerca;
    }

    public function guardar_footer(Request $request)
    {
        $footer = Footer::find(1);
        $ruta = public_path('images/carrousel');
        if($request->file('imagen') && $request->file('imagen') != null && $request->file('imagen')!= 'null'){
            $file = $request->file('imagen');
            $fileName = uniqid().$file->getClientOriginalName();
            $file->move($ruta,$fileName);
            if( file_exists('images/carrousel/'.$request->img_old) && $request->img_old != '' ){
                unlink('images/carrousel/'.$request->img_old);
            }
        }else{
            $fileName = $request->img_old;
        }

        $footer->titulo = $request->titulo;
        $footer->direccion = $request->direccion;
        $footer->telefono = $request->telefono;
        $footer->url1 = $request->url1;
        $footer->url2 = $request->url2;
        $footer->url3 = $request->url3;
        $footer->imagen = $fileName;
        $footer->save();

        return $footer;
    }

}
