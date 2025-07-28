<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoadorController;
use App\Http\Controllers\FamiliaBeneficiadaController;
use App\Http\Controllers\DoacaoController;
use App\Http\Controllers\DoacaoFamiliaController;
use App\Http\Controllers\ProdutoController;

Route::middleware(['api'])->group(function () {
    Route::apiResource('doadores', DoadorController::class);
    Route::get('/doadores/buscar/{cpf_cnpj}', [DoadorController::class, 'buscarPorCpfCnpj']);
    Route::apiResource('familias', FamiliaBeneficiadaController::class);

    Route::apiResource('doacoes', DoacaoController::class);
    Route::patch('doacoes/{id}/entregar', [DoacaoController::class, 'marcarComoEntregue']);

    // Rotas para doações de famílias
    Route::apiResource('doacao-familia', DoacaoFamiliaController::class);

    Route::get('produtos', [ProdutoController::class, 'index']);
    Route::get('produtos/disponiveis', [ProdutoController::class, 'disponiveisParaDoacao']);
});