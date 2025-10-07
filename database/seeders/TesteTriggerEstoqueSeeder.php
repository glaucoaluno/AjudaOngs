<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TesteTriggerEstoqueSeeder extends Seeder
{
    /**
     * Seeder para testar os triggers de estoque
     */
    public function run(): void
    {
        $now = Carbon::now();
        
        // Inserir dados de teste apenas se não existirem
        
        // 1. Criar um doador (se não existir)
        $doador = DB::table('doadores')->where('email', 'doador@teste.com')->first();
        if (!$doador) {
            $doadorId = DB::table('doadores')->insertGetId([
                'nome' => 'Doador Teste',
                'email' => 'doador@teste.com',
                'telefone' => '11999999999',
                'endereco' => 'Rua Teste, 123',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        } else {
            $doadorId = $doador->id;
        }

        // 2. Criar uma doação (usando id_doacao como chave primária)
        $doacaoResult = DB::table('doacoes_doadores')->insert([
            'data_doacao' => $now,
            'data_entrada' => $now,
            'id_doador' => $doadorId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        
        // Buscar o id_doacao gerado
        $doacaoId = DB::table('doacoes_doadores')
            ->where('id_doador', $doadorId)
            ->orderBy('id_doacao', 'desc')
            ->value('id_doacao');

        // 3. Criar produtos com estoque
        $produtoIds = [];
        $produtos = [
            ['nome' => 'Arroz 5kg', 'unidade' => 10, 'validade' => '2024-12-31', 'descricao' => 'Arroz branco tipo 1'],
            ['nome' => 'Feijão 1kg', 'unidade' => 15, 'validade' => '2024-11-30', 'descricao' => 'Feijão carioca'],
            ['nome' => 'Óleo 900ml', 'unidade' => 5, 'validade' => '2024-10-15', 'descricao' => 'Óleo de soja'],
        ];

        foreach ($produtos as $produto) {
            $produtoIds[] = DB::table('produtos')->insertGetId([
                'nome' => $produto['nome'],
                'unidade' => $produto['unidade'],
                'validade' => $produto['validade'],
                'descricao' => $produto['descricao'],
                'doacao_id_doacao' => $doacaoId,
                'data' => $now->toDateString(),
                'ativo' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // 4. Criar uma família beneficiada (se não existir)
        $familia = DB::table('familia_beneficiadas')->where('cpf_responsavel', '12345678901')->first();
        if (!$familia) {
            $familiaId = DB::table('familia_beneficiadas')->insertGetId([
                'nome_representante' => 'Maria da Silva',
                'cpf_responsavel' => '12345678901',
                'telefone' => '11888888888',
                'endereco' => 'Rua das Flores, 456',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        } else {
            $familiaId = $familia->id;
        }

        echo "Dados de teste criados:\n";
        echo "- Doador ID: {$doadorId}\n";
        echo "- Doação ID: {$doacaoId}\n";
        echo "- Produtos IDs: " . implode(', ', $produtoIds) . "\n";
        echo "- Família ID: {$familiaId}\n\n";

        echo "Estoque inicial dos produtos:\n";
        $produtos = DB::table('produtos')->whereIn('id', $produtoIds)->get();
        foreach ($produtos as $produto) {
            echo "- {$produto->nome}: {$produto->unidade} unidades (ativo: " . ($produto->ativo ? 'sim' : 'não') . ")\n";
        }

        echo "\n=== TESTANDO TRIGGERS ===\n\n";

        // Teste 1: Registrar doação para família (deve subtrair estoque)
        echo "1. Registrando doação de 3 unidades de Arroz para família...\n";
        DB::table('doacoes_familias')->insert([
            'familia_id_familia' => $familiaId,
            'produtos_id' => $produtoIds[0], // Arroz
            'quantidade' => 3,
            'data' => $now->toDateString(),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $produto = DB::table('produtos')->find($produtoIds[0]);
        echo "   Estoque do Arroz após doação: {$produto->unidade} unidades\n\n";

        // Teste 2: Registrar doação que zera o estoque
        echo "2. Registrando doação de 5 unidades de Óleo (deve zerar e inativar)...\n";
        DB::table('doacoes_familias')->insert([
            'familia_id_familia' => $familiaId,
            'produtos_id' => $produtoIds[2], // Óleo
            'quantidade' => 5,
            'data' => $now->toDateString(),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $produto = DB::table('produtos')->find($produtoIds[2]);
        echo "   Estoque do Óleo após doação: {$produto->unidade} unidades (ativo: " . ($produto->ativo ? 'sim' : 'não') . ")\n\n";

        echo "Estoque final dos produtos:\n";
        $produtos = DB::table('produtos')->whereIn('id', $produtoIds)->get();
        foreach ($produtos as $produto) {
            echo "- {$produto->nome}: {$produto->unidade} unidades (ativo: " . ($produto->ativo ? 'sim' : 'não') . ")\n";
        }
    }
}
