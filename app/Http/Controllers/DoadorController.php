<?php

namespace App\Http\Controllers;

use App\Models\Doador;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class DoadorController extends Controller
{
    /**
     * Retorna dados dos doadores.
     * 
     * @return JsonResponse json
     */
    public function index(): JsonResponse
    {
        try {
            $doadores = Doador::with('doacoes')->get();
            return response()->json([
                'success' => true,
                'data' => $doadores
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar doadores',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Armazena um doador válido.
     * 
     * @param Request $request
     * @return JsonResponse json
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nome' => 'required|string|max:50',
                'email' => 'required|email|max:50|unique:doadores,email',
                'telefone' => 'required|string|max:30',
                'endereco' => 'required|string|max:50'
            ]);

            $doador = Doador::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Doador cadastrado com sucesso',
                'data' => $doador
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
                'message' => 'Erro ao cadastrar doador',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retorna um doador de acordo com o id especificado.
     * 
     * @param int $id
     * @return JsonResponse json
     */
    public function show(int $id): JsonResponse
    {
        try {
            $doador = Doador::with(['doacoes.produtos'])->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $doador
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Doador não encontrado'
            ], 404);
        }
    }

    /**
     * Atualiza o doador de acordo com o id especificado.
     * 
     * @param Request $request
     * @param int $id
     * @return JsonResponse json
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $doador = Doador::findOrFail($id);

            $validated = $request->validate([
                'nome' => 'sometimes|string|max:50',
                'email' => 'sometimes|email|max:50|unique:doadores,email,' . $id,
                'telefone' => 'sometimes|string|max:30',
                'endereco' => 'sometimes|string|max:50'
            ]);

            $doador->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Doador atualizado com sucesso',
                'data' => $doador
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
                'message' => 'Erro ao atualizar doador'
            ], 500);
        }
    }

    /**
     * Remove um doador pelo id.
     * 
     * @param int $id
     * @return JsonResponse json
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $doador = Doador::findOrFail($id);
            $doador->delete();

            return response()->json([
                'success' => true,
                'message' => 'Doador removido com sucesso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao remover doador'
            ], 500);
        }
    }
}