<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProdutoController extends Controller
{
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