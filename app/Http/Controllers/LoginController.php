<?php

namespace App\Http\Controllers;

use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('login/login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only(['nome-email', 'password']);

        if (filter_var($credentials['nome-email'], FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $credentials['nome-email'];
        } else {
            $credentials['name'] = $credentials['nome-email'];
        }
        unset($credentials['nome-email']);

        if (Auth::attempt($credentials)) {
            LogService::log(
                'User logged',
                'User logged into system'
            );

            return redirect()->route('solicitations.index');
        }

        return redirect()->back()->withErrors(['login' => 'Usuário ou senha inválidos']);
    }

    public function logout()
    {
        Auth::logout();
        return view('login/login');
    }
}
