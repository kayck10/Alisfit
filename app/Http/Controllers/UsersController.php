<?php

namespace App\Http\Controllers;

use App\Models\TiposUsuarios;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function create()
    {
        $tiposUsuarios = TiposUsuarios::all();
        return view('users.create', compact('tiposUsuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'senha' => 'required|min:6|confirmed',
            'id_tipos_usuarios' => 'required|exists:tipos_usuarios,id',
        ]);

        User::create([
            'name' => $request->nome,
            'email' => $request->email,
            'password' => Hash::make($request->senha),
            'id_tipos_usuarios' => $request->id_tipos_usuarios,
        ]);

        return redirect()->route('users.create')->with('success', 'Usuário criado com sucesso!');
    }

    public function index()
    {
        $usuarios = User::with('tiposUsuarios')->get();
        return view('users.index', compact('usuarios'));
    }


    public function edit($id)
    {
        $usuario = User::findOrFail($id);

        $tiposUsuarios = TiposUsuarios::all();

        return view('users.edit', compact('usuario', 'tiposUsuarios'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'nome' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
        'id_tipos_usuarios' => 'required|exists:tipos_usuarios,id',
    ]);

    $usuario = User::findOrFail($id);

    $usuario->update([
        'name' => $request->nome,
        'email' => $request->email,
        'id_tipos_usuarios' => $request->id_tipos_usuarios,
    ]);

    return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso!');
}


    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();

        return redirect()->route('users.index')->with('success', 'Usuário excluído com sucesso!');
    }
}
