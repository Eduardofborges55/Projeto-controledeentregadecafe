<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GerenciamentoDeCompraController;
use App\Http\Controllers\GerenciamentoDeFilaController;

// 🔓 Rotas públicas (não precisam de login)
Route::prefix('user')->group(function () {
    Route::post('/', [UserController::class, 'criarUsuario']); // Criar conta
    Route::post('/login', [UserController::class, 'loginUsuario']); // Login
});

// 🔒 Rotas protegidas (precisam de token Sanctum)
Route::prefix('user')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [UserController::class, 'listarUsuarios']);
    Route::get('/{id}', [UserController::class, 'mostrarUsuario']);
    Route::put('/{id}', [UserController::class, 'atualizarUsuario']);
    Route::delete('/{id}', [UserController::class, 'deletarUsuario']);
});


    Route::prefix('fila')->middleware('auth:sanctum')->group(function () {
    Route::post('/', [GerenciamentoDeFilaController::class, 'criarFila']);
    Route::get('/', [GerenciamentoDeFilaController::class, 'listarFila']);
    Route::get('/primeiro', [GerenciamentoDeFilaController::class, 'primeiro']);
    Route::get('/{id}', [GerenciamentoDeFilaController::class, 'mostrarFila']);
    Route::put('/{id}', [GerenciamentoDeFilaController::class, 'atualizarFila']);
    Route::delete('/{id}', [GerenciamentoDeFilaController::class, 'deletarFila']);
});


    Route::prefix('compra')->middleware('auth:sanctum')->group(function () {
    Route::post('', [GerenciamentoDeCompraController::class, 'criarCompra']);
    Route::get('', [GerenciamentoDeCompraController::class, 'listarCompra']);
    Route::get('{id}', [GerenciamentoDeCompraController::class, 'mostrarCompra']);
    Route::put('{id}', [GerenciamentoDeCompraController::class, 'atualizarCompra']);
    Route::delete('{id}', [GerenciamentoDeCompraController::class, 'deletarCompra']);
    Route::post('/registrar', [GerenciamentoDeCompraController::class, 'RegistrarCompra']);
});


    Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
        Route::post('/', [UserController::class, 'criarAdmin']);
        Route::get('/', [UserController::class, 'listarAdmins']);
        Route::get('/{id}', [UserController::class, 'mostrarAdmin']);
        Route::put('/{id}', [UserController::class, 'atualizarAdmin']);
        Route::delete('/{id}', [UserController::class, 'deletarAdmin']);
        Route::put('/button/{id}', [UserController::class, 'atualizarAdminPorButton']);
    });
?>