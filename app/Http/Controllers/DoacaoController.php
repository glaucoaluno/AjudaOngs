<?php

namespace App\Http\Controllers;

use App\Models\DoacaoDoador;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DoacaoController extends Controller
{
    /**
     * Lista todas as doações de doadores
     * 
     * @return JsonResponse Lista de doações com dados dos doadores e produtos
     */
    public function index(): JsonResponse
    {
        try {
            $doacoes = DoacaoDoador::with(['doador', 'produtos'])->get();
            return response()->json([
                'success' => true,
                'data' => $doacoes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar doações',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Registra uma nova doação com produtos
     * 
     * @param Request $request Requisição contendo dados da doação e produtos
     * @return JsonResponse Dados da doação criada ou erro de validação
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'data_doacao' => 'required|date',
                'data_entrada' => 'required|date',
                'data_entrega' => 'nullable|date',
                'id_doador' => 'required|exists:doadores,id',
                'produtos' => 'required|array|min:1',
                'produtos.*.nome' => 'required|string|max:50',
                'produtos.*.unidade' => 'required|integer|min:1',
                'produtos.*.validade' => 'required|string|max:10',
                'produtos.*.descricao' => 'nullable|string',
                'produtos.*.data' => 'required|date'
            ]);

            DB::beginTransaction();

            $doacao = DoacaoDoador::create([
                'data_doacao' => $validated['data_doacao'],
                'data_entrada' => $validated['data_entrada'],
                'data_entrega' => $validated['data_entrega'],
                'id_doador' => $validated['id_doador']
            ]);

            foreach ($validated['produtos'] as $produtoData) {
                Produto::create([
                    'nome' => $produtoData['nome'],
                    'unidade' => $produtoData['unidade'],
                    'validade' => $produtoData['validade'],
                    'descricao' => $produtoData['descricao'] ?? '',
                    'doacao_id_doacao' => $doacao->id_doacao,
                    'data' => $produtoData['data']
                ]);
            }

            DB::commit();

            $doacao->load(['doador', 'produtos']);

            return response()->json([
                'success' => true,
                'message' => 'Doação cadastrada com sucesso',
                'data' => $doacao
            ], 201);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao cadastrar doação',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exibe uma doação específica
     * 
     * @param int $id ID da doação
     * @return JsonResponse Dados da doação ou erro se não encontrada
     */
    public function show(int $id): JsonResponse
    {
        try {
            $doacao = DoacaoDoador::with(['doador', 'produtos'])->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $doacao
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Doação não encontrada'
            ], 404);
        }
    }

    /**
     * Marca uma doação como entregue
     * 
     * @param int $id ID da doação
     * @return JsonResponse Confirmação da atualização ou erro
     */
    public function marcarComoEntregue(int $id): JsonResponse
    {
        try {
            $doacao = DoacaoDoador::findOrFail($id);
            $doacao->update(['data_entrega' => now()]);

            return response()->json([
                'success' => true,
                'message' => 'Doação marcada como entregue',
                'data' => $doacao
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao marcar doação como entregue'
            ], 500);
        }
    }
}