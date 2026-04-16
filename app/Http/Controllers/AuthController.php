<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'senha' => 'required',
        ], [
            'email.required' => 'O e-mail é obrigatório.',
            'email.email'    => 'Informe um e-mail válido.',
            'senha.required' => 'A senha é obrigatória.',
        ]);

        $credenciais = [
            'email'    => $request->email,
            'password' => $request->senha,
        ];

        if (Auth::attempt($credenciais)) {
            $request->session()->regenerate();

            if (Auth::user()->tipo === 'ADMIN') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('home');
        }

        return back()->withErrors(['email' => 'E-mail ou senha incorretos.'])->withInput();
    }

    public function showCadastro()
    {
        return view('auth.cadastro');
    }

    public function cadastro(Request $request)
    {
        $request->validate([
            'nome'  => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'senha' => 'required|min:6|confirmed',
        ], [
            'nome.required'      => 'O nome é obrigatório.',
            'nome.min'           => 'O nome deve ter pelo menos 3 caracteres.',
            'email.required'     => 'O e-mail é obrigatório.',
            'email.email'        => 'Informe um e-mail válido.',
            'email.unique'       => 'Este e-mail já está cadastrado.',
            'senha.required'     => 'A senha é obrigatória.',
            'senha.min'          => 'A senha deve ter pelo menos 6 caracteres.',
            'senha.confirmed'    => 'As senhas não coincidem.',
        ]);

        User::create([
            'nome'       => $request->nome,
            'email'      => $request->email,
            'password'   => Hash::make($request->senha),
            'tipo'       => 'COMUM',
        ]);

        Auth::attempt(['email' => $request->email, 'password' => $request->senha]);

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
