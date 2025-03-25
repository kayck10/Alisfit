<?php

namespace App\Http\Controllers;

use App\Models\Cupons;
use App\Models\Pedidos;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class CuponsController extends Controller
{
    public function create()
    {
        return view('cupons.create');
    }

    public function index()
    {
        $cupons = Cupons::orderBy('id', 'desc')->paginate(10);
        return view('cupons.index', compact('cupons'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|unique:cupons,codigo',
            'tipo' => 'required|in:percentual,fixo',
            'valor' => 'required|numeric|min:1',
            'quantidade' => 'required|integer|min:1',
            'expiracao' => 'nullable|date|after:today',
            'ativo' => 'required|boolean',
        ]);

        Cupons::create($request->all());
        Toastr::success('Cupom criado com sucesso!', 'Sucesso', ["positionClass" => "toast-top-center"]);
        return redirect()->route('cupons.index');
    }

    public function edit(Cupons $cupom)
    {
        return view('cupons.edit', compact('cupom'));
    }

    public function update(Request $request, $id)
    {
        $cupom = Cupons::findOrFail($id);
        $cupom->update($request->all());

        return response()->json(['success' => true, 'message' => 'Cupom atualizado com sucesso!']);
    }

    public function destroy(Cupons $cupom)
    {
        $cupom->delete();

        return redirect()->route('cupons.index')->with('success', 'Cupom excluído com sucesso!');
    }


}
