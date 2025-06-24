<?php
namespace App\Http\Controllers;

use App\Models\FamiliaBeneficiada;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class FamiliaBeneficiadaController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $familias = FamiliaBeneficiada::with('doacoesRecebidas')->get();
            return response()->json([
                'success' => true,
                'data' => $familias
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar famílias',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nome_representante' => 'required|string|max:30',
                'cpf_responsavel' => 'required|string|max:15|unique:familia_beneficiadas,cpf_responsavel',
                'telefone' => 'required|string|max:15',
                'endereco' => 'required|string|max:30'
            ]);

            $familia = FamiliaBeneficiada::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Família cadastrada com sucesso',
                'data' => $familia
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
                'message' => 'Erro ao cadastrar família',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $familia = FamiliaBeneficiada::with(['doacoesRecebidas.produto'])->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $familia
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Família não encontrada'
            ], 404);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $familia = FamiliaBeneficiada::findOrFail($id);

            $validated = $request->validate([
                'nome_representante' => 'sometimes|string|max:30',
                'cpf_responsavel' => 'sometimes|string|max:15|unique:familia_beneficiadas,cpf_responsavel,' . $id,
                'telefone' => 'sometimes|string|max:15',
                'endereco' => 'sometimes|string|max:30'
            ]);

            $familia->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Família atualizada com sucesso',
                'data' => $familia
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
                'message' => 'Erro ao atualizar família'
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $familia = FamiliaBeneficiada::findOrFail($id);
            $familia->delete();

            return response()->json([
                'success' => true,
                'message' => 'Família removida com sucesso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao remover família'
            ], 500);
        }
    }
}