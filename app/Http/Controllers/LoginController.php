<?php

namespace App\Http\Controllers;

use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user && $user->id_tipos == 4) {
            Toastr::error('Autenticação Inválida.', 'Falha', ["positionClass" => "toast-top-center"]);
            return back()->with('error', 'Clientes não têm permissão para acessar este painel.');
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }
        Toastr::error('Credenciais Inválidas.', 'Falha', ["positionClass" => "toast-top-center"]);
        return back()->with('error', 'As credenciais fornecidas não correspondem aos nossos registros.');
    }

    public function cadastro(Request $request)
    {
        if (User::where('email', $request->email)->exists()) {
            Toastr::error('Este e-mail já está cadastrado.', 'Erro', ["positionClass" => "toast-top-center"]);
            return redirect()->back()->withInput();
        }

        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'senha' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->nome,
            'email' => $request->email,
            'password' => Hash::make($request->senha),
            'id_tipos_usuarios' => 4,
        ]);

        Auth::login($user);

        Toastr::success('Usuário cadastrado e logado com sucesso!', 'Sucesso', ["positionClass" => "toast-top-center"]);

        return redirect()->back();
    }



    public function Clistore(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->back();
        }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas não correspondem aos nossos registros.',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logout realizado com sucesso!');
    }
}
