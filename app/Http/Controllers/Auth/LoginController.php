<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
// use Auth;
// use AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }
    
    // public function login(){
    //     $credenciales = $this->validate(request(),[
    //         'USERNAME' => 'required|string',
    //         'password' => 'required|string'
    //     ]);
    //     $data = array(
    //         'USERNAME' => $credenciales['USERNAME'],
    //         'password' => sha1(md5($credenciales['password']))
    //     );
    //     return $data;
    //     if(Auth::attempt($data)){
    //         return 'Tu sesion a inciado correctamente';
    //     }
    // }
}
