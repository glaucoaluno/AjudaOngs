<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoadorController;
use App\Http\Controllers\FamiliaBeneficiadaController;
use App\Http\Controllers\DoacaoController;
use App\Http\Controllers\DoacaoFamiliaController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\AuthController;

// Rota de login fora do middleware
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['api'])->group(function () {
    Route::apiResource('doadores', DoadorController::class);
    Route::apiResource('familias', FamiliaBeneficiadaController::class);

    Route::apiResource('doacoes', DoacaoController::class);
    // TODO: implementar consumo de rota no front.
    Route::patch('doacoes/{id}/entregar', [DoacaoController::class, 'marcarComoEntregue']);

    // Rotas para doações de famílias
    Route::apiResource('doacao-familia', DoacaoFamiliaController::class);
    Route::get('doacoes-familias', [DoacaoFamiliaController::class, 'index']);

    Route::get('produtos', [ProdutoController::class, 'index']);
});