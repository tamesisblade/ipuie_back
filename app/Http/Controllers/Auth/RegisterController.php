<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\estudiante;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'cedula' => ['required','unique:usuario'],
            'nombres' => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            'email' => ['required','email','unique:usuario']
            // 'codigo' => ['required']

        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $usuario = User::create([
        'name_usuario' => $data['email'],
        'nombres' => $data['nombres'],
        'apellidos' => $data['apellidos'],
        'id_group' => '4',
        'email' => $data['email'],
        'cedula' => $data['cedula'],
        'password' => sha1(md5($data['cedula'])),
        ]);
        // estudiante::create([
        //     'usuario_idusuario' => $usuario->idusuario,
        //     'codigo'=>$data['codigo'],
        // ]);
        return $usuario;
    }
}
