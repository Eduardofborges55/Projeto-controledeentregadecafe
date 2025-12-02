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
        // Use the correct model for purchases, e.g., Compra
        $compra = new GerenciamentoDeCompra();
        $compra->user_id = $request->input('user_id');
        $compra->purchase_time = $request->input('purchase_time');
        $compra->amount = $request->input('amount');
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
            'amount' => ['sometimes', 'required', 'numeric'],
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
    $amount = $request->input('amount');

    if (empty($userId) || empty($amount)) {
        return response()->json(['message' => 'user_id e amount são obrigatórios.'], 400);
    }

    return DB::transaction(function () use ($userId, $amount) {

        // 1. Registra a compra com o user_id enviado pelo front
        $compra = GerenciamentoDeCompra::create([
            'user_id' => $userId,
            'amount' => $amount,
            'purchase_time' => now()
        ]);

        // 2. Remove o usuário da posição atual na fila (se existir)
        GerenciamentoDeFila::where('user_id', $userId)->delete();

        // 3. Adiciona ele novamente AO FINAL da fila
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
