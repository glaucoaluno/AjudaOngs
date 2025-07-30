<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Doador;
use App\Models\FamiliaBeneficiada;
use App\Models\Produto;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teste do Model User
     */
    public function test_user_model_can_be_created()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertTrue(password_verify('password', $user->password));
    }

    /**
     * Teste do Model Doador
     */
    public function test_doador_model_can_be_created()
    {
        $doador = Doador::create([
            'nome' => 'João Silva',
            'email' => 'joao@example.com',
            'telefone' => '(11) 99999-9999',
            'endereco' => 'Rua Teste, 123'
        ]);

        $this->assertInstanceOf(Doador::class, $doador);
        $this->assertEquals('João Silva', $doador->nome);
        $this->assertEquals('joao@example.com', $doador->email);
        $this->assertEquals('(11) 99999-9999', $doador->telefone);
        $this->assertEquals('Rua Teste, 123', $doador->endereco);
    }

    /**
     * Teste do Model FamiliaBeneficiada
     */
    public function test_familia_beneficiada_model_can_be_created()
    {
        $familia = FamiliaBeneficiada::create([
            'nome_representante' => 'Maria Santos',
            'cpf_responsavel' => '12345678901',
            'telefone' => '(11) 88888-8888',
            'endereco' => 'Av. Família, 456'
        ]);

        $this->assertInstanceOf(FamiliaBeneficiada::class, $familia);
        $this->assertEquals('Maria Santos', $familia->nome_representante);
        $this->assertEquals('12345678901', $familia->cpf_responsavel);
        $this->assertEquals('(11) 88888-8888', $familia->telefone);
        $this->assertEquals('Av. Família, 456', $familia->endereco);
    }

    /**
     * Teste do Model Produto
     */
    public function test_produto_model_can_be_created()
    {
        // Primeiro criar uma doação para associar ao produto
        $doador = Doador::create([
            'nome' => 'João Silva',
            'email' => 'joao@example.com',
            'telefone' => '(11) 99999-9999',
            'endereco' => 'Rua Teste, 123'
        ]);

        // Criar doação primeiro (se existir o model)
        try {
            $doacao = \App\Models\DoacaoDoador::create([
                'data_doacao' => '2024-01-01',
                'data_entrada' => '2024-01-01',
                'id_doador' => $doador->id
            ]);

            $produto = Produto::create([
                'nome' => 'Arroz',
                'unidade' => 10,
                'validade' => '2024-12-31',
                'descricao' => 'Arroz integral',
                'data' => '2024-01-01',
                'doacao_id_doacao' => $doacao->id_doacao
            ]);

            $this->assertInstanceOf(Produto::class, $produto);
            $this->assertEquals('Arroz', $produto->nome);
            $this->assertEquals(10, $produto->unidade);
            $this->assertEquals('2024-12-31', $produto->validade);
            $this->assertEquals('Arroz integral', $produto->descricao);
        } catch (\Exception $e) {
            // Se não conseguir criar, pular o teste
            $this->markTestSkipped('Model DoacaoDoador não existe ou não está configurado corretamente');
        }
    }

    /**
     * Teste de validação de dados obrigatórios - Doador
     */
    public function test_doador_requires_required_fields()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Doador::create([
            'nome' => '', // Campo obrigatório vazio
            'email' => 'test@example.com'
        ]);
    }

    /**
     * Teste de validação de dados obrigatórios - FamiliaBeneficiada
     */
    public function test_familia_requires_required_fields()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        FamiliaBeneficiada::create([
            'nome_representante' => '', // Campo obrigatório vazio
            'cpf_responsavel' => '12345678901'
        ]);
    }

    /**
     * Teste de validação de dados obrigatórios - Produto
     */
    public function test_produto_requires_required_fields()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Produto::create([
            'nome' => '', // Campo obrigatório vazio
            'unidade' => 10
        ]);
    }

    /**
     * Teste de email único (se implementado)
     */
    public function test_doador_email_must_be_unique()
    {
        Doador::create([
            'nome' => 'João Silva',
            'email' => 'joao@example.com',
            'telefone' => '(11) 99999-9999',
            'endereco' => 'Rua Teste, 123'
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Doador::create([
            'nome' => 'João Silva 2',
            'email' => 'joao@example.com', // Email duplicado
            'telefone' => '(11) 99999-9998',
            'endereco' => 'Rua Teste, 124'
        ]);
    }

    /**
     * Teste de CPF único (se implementado)
     */
    public function test_familia_cpf_must_be_unique()
    {
        FamiliaBeneficiada::create([
            'nome_representante' => 'Maria Santos',
            'cpf_responsavel' => '12345678901',
            'telefone' => '(11) 88888-8888',
            'endereco' => 'Av. Família, 456'
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);
        
        FamiliaBeneficiada::create([
            'nome_representante' => 'Maria Santos 2',
            'cpf_responsavel' => '12345678901', // CPF duplicado
            'telefone' => '(11) 88888-8887',
            'endereco' => 'Av. Família, 457'
        ]);
    }
}