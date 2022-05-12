<?php
namespace App\Http\Controllers;
use App\Http\Requests\RegisterAuthRequest;
use App\Http\Controllers\Controller;
use App\User;
use DB;
use App\Quotation;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
class AuthController extends Controller {
    public $loginAfterSignUp = true;

    public function register(Request $request) {

        $datosValidados=$request->validate([
            'cedula' => 'required|max:11|unique:usuario',
            'password' => 'required|min:8',
            'nombres' => 'required',
            'apellidos' => 'required',
            'ciudad' => 'required',
            'email' => 'required|email|unique:usuario',
        ]);

        $user = new User();
        $user->cedula = $request->cedula;
        $user->nombres = $request->nombres;
        $user->apellidos = $request->apellidos;
        $user->email = $request->email;
        $user->name_usuario = $request->email;
        $user->id_group = '2';
        $user->ciudad = $request->ciudad;
        $user->password = sha1(md5($request->password));
        $user->save();
        if ($this->loginAfterSignUp) {
        return $this->login($request);
        }
        return response()->json([
        'status' => 'ok',
        'data' => $user
        ], 200);
    }


    public function crearUsuario(Request $request) {

        $datosValidados=$request->validate([
            'cedula' => 'required|max:11|unique:usuario',
            'password' => 'required|min:8',
            'nombres' => 'required',
            'apellidos' => 'required',
            'ciudad' => 'required',
            'apellidos' => 'required',
            'email' => 'required|email|unique:usuario',
        ]);

        $user = new User();
        $user->cedula = $request->cedula;
        $user->nombres = $request->nombres;
        $user->apellidos = $request->apellidos;
        $user->email = $request->email;
        $user->name_usuario = $request->email;
        $user->telefono = $request->telefono;
        $user->id_group = $request->id_group;
        $user->ciudad = $request->ciudad;
        $user->password = sha1(md5($request->password));
        $user->save();

        return $user;
    }

    public function editarUsuario(Request $request) {

        $datosValidados=$request->validate([
            'cedula' => 'required|max:11',
            'nombres' => 'required',
            'apellidos' => 'required',
            'ciudad' => 'required',
            'email' => 'required|email',
        ]);

        $user = User::find($request->idusuario);
        $user->cedula = $request->cedula;
        $user->nombres = $request->nombres;
        $user->apellidos = $request->apellidos;
        $user->email = $request->email;
        $user->telefono = $request->telefono;
        $user->name_usuario = $request->email;
        $user->id_group = $request->id_group;
        $user->ciudad = $request->ciudad;

        $user->save();

        return $user;
    }


    public function eliminarUsuario($id) {

         $usuario = DB::DELETE("DELETE FROM `usuario` WHERE idusuario=$id");

        return $usuario;
    }


    public function login(Request $request) {

        // return sha1(md5($request->password));
        $usuario = DB::SELECT("SELECT * FROM usuario WHERE name_usuario = ? AND password = ?",[$request->name_usuario,sha1(md5($request->password))]);
        $input = $request->only('name_usuario', 'password');
        $jwt_token = JWTAuth::attempt($input);
        if ($usuario == null || $usuario ==  '') {
            return response()->json([
            'status' => 'invalid_credentials',
            'message' => 'Correo o contraseña no válidos.',
            ], 401);
        }
        return response()->json([
            'datos' =>$usuario,
            'status' => 'ok',
            'token' => $jwt_token,
        ]);
    }
    public function logout(Request $request) {
    $this->validate($request, [
    'token' => 'required'
    ]);
    try {
    JWTAuth::invalidate($request->token);
    return response()->json([
    'status' => 'ok',
    'message' => 'Cierre de sesión exitoso.'
    ]);
    } catch (JWTException $exception) {
    return response()->json([
        'status' => 'unknown_error',
 'message' => 'Al usuario no se le pudo cerrar la sesión.'
 ], 500);
 }
 }
 public function getAuthUser(Request $request) {
 $this->validate($request, [
 'token' => 'required'
 ]);
 $user = JWTAuth::authenticate($request->token);
 return response()->json(['user' => $user]);
 }
 protected function jsonResponse($data, $code = 200)
{
 return response()->json($data, $code,
 ['Content-Type' => 'application/json;charset=UTF8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
}
}
