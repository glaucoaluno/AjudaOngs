<?php

namespace App\Http\Controllers;

use App\Models\DoacaoFamilia;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class DoacaoFamiliaController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $doacoes = DoacaoFamilia::with(['familia', 'produto'])->get();
            return response()->json([
                'success' => true,
                'data' => $doacoes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar doações para famílias',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'familia_id_familia' => 'required|exists:familia_beneficiadas,id',
                'produtos_id' => 'required|exists:produtos,id',
                'quantidade' => 'required|integer|min:1',
                'data' => 'required|date'
            ]);

            $doacao = DoacaoFamilia::create($validated);
            $doacao->load(['familia', 'produto']);

            return response()->json([
                'success' => true,
                'message' => 'Doação para família registrada com sucesso',
                'data' => $doacao
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao registrar doação para família',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $doacao = DoacaoFamilia::with(['familia', 'produto'])->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $doacao
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Doação para família não encontrada'
            ], 404);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $doacao = DoacaoFamilia::findOrFail($id);

            $validated = $request->validate([
                'familia_id_familia' => 'sometimes|exists:familia_beneficiadas,id',
                'produtos_id' => 'sometimes|exists:produtos,id',
                'quantidade' => 'sometimes|integer|min:1',
                'data' => 'sometimes|date'
            ]);

            $doacao->update($validated);
            $doacao->load(['familia', 'produto']);

            return response()->json([
                'success' => true,
                'message' => 'Doação para família atualizada com sucesso',
                'data' => $doacao
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar doação para família'
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $doacao = DoacaoFamilia::findOrFail($id);
            $doacao->delete();

            return response()->json([
                'success' => true,
                'message' => 'Doação para família removida com sucesso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao remover doação para família'
            ], 500);
        }
    }
} 