<?php

namespace App\Http\Controllers;

use App\Usuario;
use Illuminate\Http\Request;
use App\Quotation;
use DB;
use Mail;
use Cookie;
use Dirape\Token\Token;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $usuarios = DB::select("CALL `datosusuario` ();");
        return $usuarios;
    }

    public function docente(Request $request)
    {
        $usuarios = DB::select("CALL `docente` ();");
        return $usuarios;
    }

    public function prolipa(Request $request)
    {
        $usuarios = DB::select("CALL `prolipa` ();");
        return $usuarios;
    }

    public function estudiantes(Request $request)
    {
        $usuarios = DB::select("CALL `estudiantes` ();");
        return $usuarios;
    }


    public function aplicativo(Request $request){
        $usuarios = DB::select("CALL usuariologin ( ? , ? )",[$request->username,$request->password]);
        return $usuarios;
    }
    
    public function aplicativobase(Request $request){
        $usuarios = DB::select("CALL periodofecha (?)",[$request->idusuario]);
        return $usuarios;
    }

    public function getVendedor(){
        $vendedor = DB::SELECT('CALL `selectVendedor`();');
        return $vendedor;
    }

    public function getReporte(Request $request){
        $reporte = DB::SELECT('CALL `reporteVendedor` (?);',[$request->cedula]);
        return $reporte;
    }

    public function getReporteVisitas(Request $request){
        $reporte = DB::SELECT('CALL `vendedor_institucion_docente` (?);',[$request->idInstitucion]);
        return $reporte;
    }

    public function getCompleto(Request $request){
        $reporte = DB::SELECT('CALL `reporteVendedor` (?);',[$request->cedula]);
        if(!empty($reporte)){
            foreach ($reporte as $key => $post) {
                $docentes = DB::SELECT('CALL `vendedor_institucion_docente` (?)', [$post->ID]);
                $data['items'][$key] = [
                    'institucion' => $post,
                    'docentes'=>$docentes,
                ];
            }
            return $data;
        }else{
            $data = null;
        }
    }

    public function activar(Request $request){
        $idusuario = $request->idusuario;
        DB::update("UPDATE `usuario` SET `estado_idEstado`= ?  WHERE `idusuario` = ?",[1,$idusuario]);
    }

    public function desactivar(Request $request){
        $idusuario = $request->idusuario;
        DB::update("UPDATE `usuario` SET `estado_idEstado`= ?  WHERE `idusuario` = ?",[2,$idusuario]);
    }

    public function guardarPassword(Request $request){
        $datosValidados=$request->validate([
            'password' => 'min:8',
            'password_confirmation' => 'required_with:password|same:password|min:8'
        ]);
        $idusuario = auth()->user()->idusuario;
        $password = sha1(md5($request->password));
        $usuario = DB::update("UPDATE `usuario` SET `password`= ?, `p_ingreso`=?   WHERE `idusuario` = ?",[$password,'1',$idusuario]);
    }

    public function loginMac(Request $request)
    {
        $usuarios = DB::select("SELECT * FROM usuario where name_usuario = ? and password = ? ",[$request->usuario,sha1(md5($request->password))]);
        return $usuarios;
    }

    public function buscaUsuario(Request $request)
    {
        $usuarios = DB::SELECT("SELECT * FROM usuario where cedula = ?",[$request->cedula]);
        return $usuarios;
    }

    public function vendedor(Request $request)
    {
        $idusuario = auth()->user()->idusuario;
        if(!empty($idusuario)){
            $usuarios = DB::select("SELECT * FROM usuario where institucion_idInstitucion = 66 ");
            return $usuarios;
        }
    }

    public function select()
    {
        $usuario = DB::table('usuario')
                ->where('institucion_idInstitucion', '66')
                ->get();
        return  $usuario;
    }

    public function docentes(Request $request)
    {
        $idinstitucion = $request->idInstitucion;
        $consulta=DB::select("select * from usuario where institucion_idInstitucion = $idinstitucion");
        return $consulta;
    }

    public function datosUsuario(Request $request)
    {
        $idusuario = auth()->user()->idusuario;
        $idgrupo = auth()->user()->id_group;
        if(!empty($idusuario)){
            if($idgrupo == 4){
                $consulta = DB::SELECT("SELECT * FROM usuario WHERE idusuario = ?",[$idusuario]);
                return $consulta;
            }else{
                $consulta=DB::select("SELECT * FROM usuario left join institucion on usuario.institucion_idInstitucion = institucion.idInstitucion left join periodoescolar_has_institucion on periodoescolar_has_institucion.institucion_idInstitucion = institucion.idInstitucion left join periodoescolar on periodoescolar.idperiodoescolar = periodoescolar_has_institucion.periodoescolar_idperiodoescolar left join sys_group_users on usuario.id_group = sys_group_users.id  WHERE usuario.idusuario = $idusuario AND estado = '1' ORDER BY usuario.idusuario");
                return $consulta;
            }
        }
    }

    public function historial(Request $request)
    {
        $idusuario = $request->idusuario;
        $consulta=DB::select("select * from registro_usuario where usuario_idusuario = $idusuario ORDER BY  `registro_usuario`.`hora_ingreso_usuario` DESC ");
        return $consulta;
    }
    public function historialI(Request $request)
    {
        $idinstitucion = $request->idInstitucion;
        $consulta=DB::select("SELECT * FROM registro_usuario LEFT JOIN usuario ON usuario.idusuario = registro_usuario.usuario_idusuario LEFT JOIN institucion ON institucion.idInstitucion = usuario.institucion_idInstitucion  where institucion.idInstitucion = $idinstitucion ");
        return $consulta;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function perfil(Request $request)
    {
        if(!empty($request->file('archivo'))){
            $idusuario = $request->idusuario;
            $file = $request->file('archivo');
            $ruta = public_path('/perfil');
            $fileName = uniqid().$file->getClientOriginalName();
            $file->move($ruta,$fileName);
            DB::UPDATE("UPDATE `usuario` SET `cedula`=?,`nombres`=?,`apellidos`=?,`password`=?,`email`=?,`foto_user`=?,`telefono`=?,`institucion_idInstitucion`=? WHERE `idusuario` = ?",[
                $request->cedula,$request->nombres,$request->apellidos,sha1(md5($request->password)),$request->email,$fileName,$request->telefono,$request->institucion_idInstitucion,$request->idusuario
            ]);
        }else{
            $idusuario = $request->idusuario;
            DB::UPDATE("UPDATE `usuario` SET `cedula`=?,`nombres`=?,`apellidos`=?,`password`=?,`email`=?,`telefono`=?,`institucion_idInstitucion`=? WHERE `idusuario` = ?",[
                $request->cedula,$request->nombres,$request->apellidos,sha1(md5($request->password)),$request->email,$request->telefono,$request->institucion_idInstitucion,$request->idusuario
            ]);
        }
        $usuario = DB::SELECT("SELECT * FROM usuario WHERE idusuario = ?",[$request->idusuario]); 
        return $usuario;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $datosValidados=$request->validate([
            'cedula' => 'required|max:11|unique:usuario',
            'nombres' => 'required',
            'apellidos' => 'required',
            'name_usuario' => 'required|unique:usuario',
            'email' => 'required|email|unique:usuario',
            'id_group' => 'required',
        ]);

        $usuario = new Usuario();
        $usuario->cedula = $request->cedula;
        $usuario->nombres = $request->nombres;
        $usuario->apellidos = $request->apellidos;
        $usuario->name_usuario = $request->name_usuario;
        $usuario->email = $request->email;
        $usuario->id_group = $request->id_group;
        $usuario->password=sha1(md5($request->cedula));
        $usuario->estado_idEstado=1;
        $email = $request->email;
        $usuario->save();
        
        $data = array(
            'name'=>"Prolipa",
        );
        Mail::send('plantilla.registro', $data,function ($message){
            $message->from($_GET['email'], 'Prolipa');
            $message->to($_GET['email'])->subject('Registro Prolipa');
        });
    }

    public function closeSession(){
        Auth::logout();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $usuario = Usuario::find($id);
        return $usuario;
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Usuario  $usuario
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
     * @param  \App\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!$request->ajax());
        $usuario = new Usuario();
        $usuario->cedula = $request->cedula;
        $usuario->nombres = $request->nombres;
        $usuario->apellidos = $request->apellidos;
        $usuario->name_usuario = $request->name_usuario;
        $usuario->password=sha1(md5($request->cedula));
        $usuario->email = $request->email;
        $usuario->id_group = $request->id_group;
        $usuario->estado_idEstado=1;
        $usuario->idusuario = $request->idusuario;
        
        DB::update("UPDATE `usuario` SET `cedula`= ?,`nombres`= ?,`apellidos`= ?,`name_usuario`= ?,`password`= ?,`email`= ?,`id_group`= ?,`institucion_idInstitucion`= ?,`estado_idEstado`= ?,`p_ingreso`= ? WHERE `idusuario`= ?",[$request->cedula,$request->nombres,$request->apellidos,$request->name_usuario,$usuario->password,$request->email,$request->id_group,$request->institucion_idInstitucion,$request->estado_idEstad,$usuario->p_ingreso,$request->idusuario]);
        
        
        $data = array(
            'name'=>"Prolipa",
        );
        Mail::send('plantilla.update', $data,function ($message){
            $message->from($_GET['email'], 'Prolipa');
            $message->to($_GET['email'])->subject('Registro Prolipa');
        });
    }

    public function restaurar(Request $request){
        $codigo = (new Token())->Unique('curso', 'codigo', 8);
        $password=sha1(md5($codigo));
        $usuario = DB::SELECT("SELECT * FROM usuario WHERE email = ?",[$request->email]);
        $cedula = '';
        $nombres = '';
        $apellidos = '';
        foreach ($usuario as $key => $value) {
            $cedula = $value->cedula;
            $nombres = $value->nombres;
            $apellidos = $value->apellidos;
        }
        $update = DB::update("UPDATE `usuario` SET `password`= ?, `password_status`=? WHERE `email`= ?",[$password,'0',$request->email]);
        
        $to_name = "Prolipa";
        $to_email = $request->email;
        $data = array(
            'name'=>"Prolipa",
            'email' => $request->email,
            'codigo' => $codigo,
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            'cedula' => $cedula
        );
        
        Mail::send('plantilla.restaurar',$data, function($message) use ($to_name, $to_email) {
        $message->to($to_email, $to_name)
        ->subject('Código Temporal');
        $message->from($to_email, 'Prolipa');
        });
   
        return 'Correo enviado';
    }

    public function passwordC(Request $request){
        $idusuario = $request->idusuario;
        $password = sha1(md5($request->password));
        $usuario = DB::update("UPDATE `usuario` SET `password`= ?, `password_status`=?   WHERE `idusuario` = ?",[$password,'1',$idusuario]);
        $usuario = DB::SELECT("SELECT * FROM usuario WHERE idusuario = ?",[$request->idusuario]);
        $cedula = '';
        $nombres = '';
        $apellidos = '';
        foreach ($usuario as $key => $value) {
            $cedula = $value->cedula;
            $nombres = $value->nombres;
            $apellidos = $value->apellidos;
            $email = $value->email;
        }
        $to_name = "Prolipa";
        $to_email = $email;
        $data = array(
            'name'=>"Prolipa",
            'email' => $email,
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            'cedula' => $cedula
        );
        Mail::send('plantilla.cambio',$data, function($message) use ($to_name, $to_email) {
        $message->to($to_email, $to_name)
        ->subject('Cambio de Contraseña');
        $message->from($to_email, 'Prolipa');
        });
        return $usuario;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function eliminarUsuario(Request $request)
    {
        $idusuario = $request->idusuario;
        DB::select("INSERT INTO usuarioEliminado SELECT * FROM usuario WHERE idusuario = ?",[$idusuario]);
        DB::delete("DELETE FROM usuario WHERE idusuario = ?",[$idusuario]);
    }

    public function papeleraUsuario(Request $request)
    {
        $usuarios = DB::select("SELECT * FROM usuarioEliminado");
        return $usuarios;
    }

    public function restaurarUsuario(Request $request)
    {
        $idusuario = $request->idusuario;
        DB::select("INSERT INTO usuario SELECT * FROM usuarioEliminado WHERE idusuario = ?",[$idusuario]);
        DB::delete("DELETE FROM usuarioEliminado WHERE idusuario = ?",[$idusuario]);
    }

    public function guardarUsuarioConectado(Request $request){
        DB::insert("INSERT INTO usuarioConectados(idusuario, claveio, nombres, apellidos, email, Institucion) VALUES (?,?,?,?,?,?)",[$request->idusuario, $request->claveio, $request->nombres, $request->apellidos, $request->email, $request->Institucion]);
    }

    public function eliminarUsuarioConectado(Request $request){
        $usuario = DB::select("SELECT * FROM usuarioConectados WHERE claveio = ? ",[$request->claveio]);
        DB::delete("DELETE FROM usuarioConectados WHERE  claveio = ? ",[$request->claveio]);
        return $usuario;
    }


    /*==========usuariosmisiones=====================*/

    
    public function usuariosmisiones(){
        $usuario = DB::select("SELECT * FROM usuario ORDER BY id_group");
        return $usuario;
    }

    
}

