<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\GerenciamentoDeCompra;
use App\Models\GerenciamentoDeFila;
use Carbon\Carbon;

class GerenciamentoDeCompraController extends Controller
{
    public function criarCompra(Request $request)
    {
        $compra = new GerenciamentoDeCompra();
        $compra->user_id = $request->input('user_id');
        $compra->purchase_time = $request->input('purchase_time');
        $compra->amount_Cafe = $request->input('amount_Cafe');
        $compra->amount_Filtro = $request->input('amount_Filtro');
        $compra->save();

        return response()->json(['message' => 'Compra criada com sucesso!'], 201);      
    }

    public function listarCompra()
    {
        return GerenciamentoDeCompra::all();
    }

    public function mostrarCompra($id)
    {
        return GerenciamentoDeCompra::findOrFail($id);
    }

    public function atualizarCompra(Request $request, $id)
    {
        $compra = GerenciamentoDeCompra::findOrFail($id);
        
        $validado = $request->validate([
            'purchase_time' => ['sometimes', 'required', 'date'],
            'amount_Cafe' => ['sometimes', 'required', 'numeric'],
            'amount_Filtro' => ['sometimes', 'required', 'numeric'],
        ]);

        $compra->update($validado);

        return ['Message' => 'Compra atualizada com sucesso!'];
    }

    public function deletarCompra($id)
    {
        $compra = GerenciamentoDeCompra::findOrFail($id);
        $compra->delete();

        return ['Message' => 'Compra deletada com sucesso!'];
    }

public function RegistrarCompra(Request $request)
{
    $userId = $request->input('user_id');
    $amount_Cafe = $request->input('amount_Cafe');
    $amount_Filtro = $request->input('amount_Filtro', 0);


    if (empty($userId) || empty($amount_Cafe)) {
        return response()->json(['message' => 'user_id e amount_Cafe são obrigatórios'], 400);
    }

    return DB::transaction(function () use ($userId, $amount_Cafe, $amount_Filtro) {

        $compra = GerenciamentoDeCompra::create([
            'user_id' => $userId,
            'amount_Cafe' => $amount_Cafe,
            'amount_Filtro' => $amount_Filtro,
            'purchase_time' => now()
        ]);

        GerenciamentoDeFila::where('user_id', $userId)->delete();

        GerenciamentoDeFila::create([
            'user_id' => $userId,
            'ativo' => true
        ]);

        return response()->json([
            'message' => 'Compra registrada! Usuário movido para o fim da fila.',
            'compra' => $compra,
        ], 201);
    });
}
}
?>
