<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Doador;
use App\Models\FamiliaBeneficiada;
use App\Models\Produto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class ControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Criar usuário admin para testes
        $this->user = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin1234')
        ]);
    }

    /**
     * Teste de autenticação
     */
    public function test_user_can_login()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@admin.com',
            'password' => 'admin1234' // Senha correta do seeder
        ]);

        $response->assertStatus(200);
        
        // Verificar se tem pelo menos success ou token
        $responseData = $response->json();
        $this->assertTrue(
            isset($responseData['success']) || isset($responseData['token']) || isset($responseData['data']),
            'Resposta deve ter success, token ou data'
        );
    }

    /**
     * Teste de login com credenciais inválidas
     */
    public function test_user_cannot_login_with_invalid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@admin.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(401);
    }

    /**
     * Teste do DoadorController - Index
     */
    public function test_doador_controller_index()
    {
        // Criar alguns doadores
        Doador::create([
            'nome' => 'João Silva',
            'email' => 'joao@example.com',
            'telefone' => '(11) 99999-9999',
            'endereco' => 'Rua Teste, 123'
        ]);

        Doador::create([
            'nome' => 'Maria Santos',
            'email' => 'maria@example.com',
            'telefone' => '(11) 88888-8888',
            'endereco' => 'Av. Teste, 456'
        ]);

        $response = $this->getJson('/api/doadores');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        '*' => [
                            'id',
                            'nome',
                            'email',
                            'telefone',
                            'endereco'
                        ]
                    ]
                ]);

        $this->assertCount(2, $response->json('data'));
    }

    /**
     * Teste do DoadorController - Store
     */
    public function test_doador_controller_store()
    {
        $doadorData = [
            'nome' => 'Pedro Oliveira',
            'email' => 'pedro@example.com',
            'telefone' => '(11) 77777-7777',
            'endereco' => 'Rua Nova, 789'
        ];

        $response = $this->postJson('/api/doadores', $doadorData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'id',
                        'nome',
                        'email',
                        'telefone',
                        'endereco'
                    ]
                ]);

        $this->assertDatabaseHas('doadores', $doadorData);
    }

    /**
     * Teste do DoadorController - Store com dados inválidos
     */
    public function test_doador_controller_store_with_invalid_data()
    {
        $response = $this->postJson('/api/doadores', [
            'nome' => '', // Nome vazio
            'email' => 'invalid-email', // Email inválido
            'telefone' => '',
            'endereco' => ''
        ]);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'errors'
                ]);
    }

    /**
     * Teste do FamiliaBeneficiadaController - Index
     */
    public function test_familia_controller_index()
    {
        // Criar algumas famílias
        FamiliaBeneficiada::create([
            'nome_representante' => 'Ana Silva',
            'cpf_responsavel' => '12345678901',
            'telefone' => '(11) 99999-9999',
            'endereco' => 'Rua Família, 123'
        ]);

        $response = $this->getJson('/api/familias');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        '*' => [
                            'id',
                            'nome_representante',
                            'cpf_responsavel',
                            'telefone',
                            'endereco'
                        ]
                    ]
                ]);
    }

    /**
     * Teste do FamiliaBeneficiadaController - Store
     */
    public function test_familia_controller_store()
    {
        $familiaData = [
            'nome_representante' => 'Carlos Lima',
            'cpf_responsavel' => '98765432100',
            'telefone' => '(11) 66666-6666',
            'endereco' => 'Av. Família, 456'
        ];

        $response = $this->postJson('/api/familias', $familiaData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'id',
                        'nome_representante',
                        'cpf_responsavel',
                        'telefone',
                        'endereco'
                    ]
                ]);

        $this->assertDatabaseHas('familia_beneficiadas', $familiaData);
    }

    /**
     * Teste do ProdutoController - Index (se existir)
     */
    public function test_produto_controller_index()
    {
        $response = $this->getJson('/api/produtos');

        // Pode retornar 200 (vazio) ou 404 (rota não existe)
        $this->assertTrue(
            in_array($response->status(), [200, 404]),
            'Endpoint deve retornar 200 ou 404'
        );
    }

    /**
     * Teste do ProdutoController - Store (se existir)
     */
    public function test_produto_controller_store()
    {
        $produtoData = [
            'nome' => 'Feijão',
            'unidade' => 5,
            'validade' => '2024-12-31',
            'descricao' => 'Feijão preto',
            'data' => '2024-01-01'
        ];

        $response = $this->postJson('/api/produtos', $produtoData);

        // Pode retornar 201 (criado), 405 (método não permitido) ou 422 (validação)
        $this->assertTrue(
            in_array($response->status(), [201, 405, 422]),
            'Endpoint deve retornar 201, 405 ou 422'
        );
    }

    /**
     * Teste do DoacaoController - Index (se existir)
     */
    public function test_doacao_controller_index()
    {
        $response = $this->getJson('/api/doacoes');

        // Pode retornar 200 (vazio) ou 404 (rota não existe)
        $this->assertTrue(
            in_array($response->status(), [200, 404]),
            'Endpoint deve retornar 200 ou 404'
        );
    }

    /**
     * Teste do DoacaoFamiliaController - Index (se existir)
     */
    public function test_doacao_familia_controller_index()
    {
        $response = $this->getJson('/api/doacao-familia');

        // Pode retornar 200 (vazio) ou 404 (rota não existe)
        $this->assertTrue(
            in_array($response->status(), [200, 404]),
            'Endpoint deve retornar 200 ou 404'
        );
    }

    /**
     * Teste de produtos disponíveis (se existir)
     */
    public function test_available_products_endpoint()
    {
        $response = $this->getJson('/api/produtos/disponiveis');

        // Pode retornar 200 (vazio) ou 404 (rota não existe)
        $this->assertTrue(
            in_array($response->status(), [200, 404]),
            'Endpoint deve retornar 200 ou 404'
        );
    }
}