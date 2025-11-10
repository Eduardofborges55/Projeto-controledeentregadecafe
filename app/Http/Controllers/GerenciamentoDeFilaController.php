<?php

namespace App\Http\Controllers;

use App\Models\GerenciamentoDeFila;
use Illuminate\Http\Request;

class GerenciamentoDeFilaController extends Controller
{
    public function criarFila(Request $request)
    {
        $Fila = new GerenciamentoDeFila();
        $Fila->user_id = $request->input('user_id');
        $Fila->ativo = $request->input('ativo');
        $Fila->save();

        return response()->json(['message' => 'Fila criada com sucesso!'], 201);
    }

    public function listarFila()
    {
        return GerenciamentoDeFila::all();
    }

    public function mostrarFila($id)
    {
        return GerenciamentoDeFila::findOrFail($id);
    }

    public function atualizarFila(Request $request, $id)
    {
        $Fila = GerenciamentoDeFila::findOrFail($id);
        
        $validado = $request->validate([
            'user_id' => ['sometimes', 'required', 'integer'],
            'ativo' => ['sometimes', 'required', 'boolean'],
        ]);

        $Fila->update($validado);

        return ['Message' => 'Fila atualizada com sucesso!'];
    }

    public function deletarFila($id)
    {
        $Fila = GerenciamentoDeFila::findOrFail($id);
        $Fila->delete();

        return ['Message' => 'Fila deletada com sucesso!'];
    }
}



?>
