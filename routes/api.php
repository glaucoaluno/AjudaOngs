<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoadorController;
use App\Http\Controllers\FamiliaBeneficiadaController;
use App\Http\Controllers\DoacaoController;
use App\Http\Controllers\ProdutoController;

Route::middleware(['api'])->group(function () {
    Route::apiResource('doadores', DoadorController::class);
    Route::get('/doadores/buscar/{cpf_cnpj}', [DoadorController::class, 'buscarPorCpfCnpj']);
    Route::apiResource('familias', FamiliaBeneficiadaController::class);

    Route::apiResource('doacoes', DoacaoController::class);
    Route::patch('doacoes/{id}/entregar', [DoacaoController::class, 'marcarComoEntregue']);

    Route::get('produtos', [ProdutoController::class, 'index']);
    Route::get('produtos/disponiveis', [ProdutoController::class, 'disponiveisParaDoacao']);

    Route::get('estatisticas', function() {
        return response()->json([
            'success' => true,
            'data' => [
                'total_doadores' => \App\Models\Doador::count(),
                'total_familias' => \App\Models\FamiliaBeneficiada::count(),
                'total_doacoes' => \App\Models\DoacaoDoador::count(),
                'doacoes_entregues' => \App\Models\DoacaoDoador::whereNotNull('data_entrega')->count(),
                'doacoes_pendentes' => \App\Models\DoacaoDoador::whereNull('data_entrega')->count(),
                'total_produtos' => \App\Models\Produto::count()
            ]
        ]);
    });
});