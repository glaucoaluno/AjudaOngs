<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProdutoController extends Controller
{
    /**
     * Lista todos os produtos cadastrados
     * 
     * @return JsonResponse Lista de produtos com dados das doações e doadores
     */
    public function index(): JsonResponse
    {
        try {
            $produtos = Produto::with(['doacao.doador'])->get();
            return response()->json([
                'success' => true,
                'data' => $produtos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar produtos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lista produtos disponíveis para doação (com data de entrega definida)
     * 
     * @return JsonResponse Lista de produtos disponíveis para doação
     */
    public function disponiveisParaDoacao(): JsonResponse
    {
        try {
            $produtos = Produto::whereHas('doacao', function($query) {
                $query->whereNotNull('data_entrega');
            })->with(['doacao.doador'])->get();

            return response()->json([
                'success' => true,
                'data' => $produtos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar produtos disponíveis',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}