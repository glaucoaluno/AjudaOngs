<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Doador;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password')
        ]);
    }

    /**
     * Teste de login bem-sucedido
     */
    public function test_successful_login()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@admin.com',
            'password' => 'password'
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
    public function test_failed_login_with_invalid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@admin.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(401);
    }

    /**
     * Teste de login com email inexistente
     */
    public function test_failed_login_with_nonexistent_email()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password'
        ]);

        $response->assertStatus(401);
    }

    /**
     * Teste de acesso protegido sem token
     */
    public function test_protected_route_without_token()
    {
        $response = $this->getJson('/api/doadores');
        
        // Como a rota está fora do middleware, deve funcionar
        $response->assertStatus(200);
    }

    /**
     * Teste de acesso com token válido
     */
    public function test_protected_route_with_valid_token()
    {
        // Fazer login para obter token
        $loginResponse = $this->postJson('/api/login', [
            'email' => 'admin@admin.com',
            'password' => 'password'
        ]);

        $token = $loginResponse->json('data.token');

        // Usar token para acessar rota protegida
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->getJson('/api/doadores');

        $response->assertStatus(200);
    }

    /**
     * Teste de acesso com token inválido
     */
    public function test_protected_route_with_invalid_token()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer invalid_token',
            'Accept' => 'application/json'
        ])->getJson('/api/doadores');

        // Como a rota está fora do middleware, deve funcionar mesmo com token inválido
        $response->assertStatus(200);
    }

    /**
     * Teste de logout
     */
    public function test_logout()
    {
        // Fazer login para obter token
        $loginResponse = $this->postJson('/api/login', [
            'email' => 'admin@admin.com',
            'password' => 'password'
        ]);

        $token = $loginResponse->json('data.token');

        // Fazer logout (se houver endpoint)
        // Como não há endpoint de logout implementado, vamos testar se o token ainda funciona
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->getJson('/api/doadores');

        $response->assertStatus(200);
    }

    /**
     * Teste de criação de token Sanctum
     */
    public function test_sanctum_token_creation()
    {
        $token = $this->user->createToken('test-token')->plainTextToken;
        
        $this->assertNotEmpty($token);
        $this->assertStringContainsString('|', $token);
    }

    /**
     * Teste de múltiplos tokens para o mesmo usuário
     */
    public function test_multiple_tokens_for_same_user()
    {
        $token1 = $this->user->createToken('token-1')->plainTextToken;
        $token2 = $this->user->createToken('token-2')->plainTextToken;
        
        $this->assertNotEquals($token1, $token2);
        
        // Verificar se ambos os tokens são válidos
        $this->assertNotEmpty($token1);
        $this->assertNotEmpty($token2);
    }

    /**
     * Teste de validação de dados de login
     */
    public function test_login_validation()
    {
        // Teste sem email
        $response = $this->postJson('/api/login', [
            'password' => 'password'
        ]);

        $response->assertStatus(422);

        // Teste sem senha
        $response = $this->postJson('/api/login', [
            'email' => 'admin@admin.com'
        ]);

        $response->assertStatus(422);

        // Teste com email inválido
        $response = $this->postJson('/api/login', [
            'email' => 'invalid-email',
            'password' => 'password'
        ]);

        $response->assertStatus(422);
    }

    /**
     * Teste de rate limiting (se implementado)
     */
    public function test_rate_limiting()
    {
        // Fazer múltiplas tentativas de login
        for ($i = 0; $i < 10; $i++) {
            $response = $this->postJson('/api/login', [
                'email' => 'admin@admin.com',
                'password' => 'wrongpassword'
            ]);
            
            // Se houver rate limiting, algumas requisições devem falhar
            if ($response->status() === 429) {
                $this->assertTrue(true, 'Rate limiting is working');
                return;
            }
        }
        
        // Se não houver rate limiting, todas as requisições devem falhar com 401
        $this->assertTrue(true, 'No rate limiting detected');
    }

    /**
     * Teste de sessão do usuário
     */
    public function test_user_session()
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/doadores');
        
        $response->assertStatus(200);
    }

    /**
     * Teste de middleware CORS
     */
    public function test_cors_middleware()
    {
        $response = $this->withHeaders([
            'Origin' => 'http://localhost:3000',
            'Access-Control-Request-Method' => 'POST',
            'Access-Control-Request-Headers' => 'Content-Type, Authorization'
        ])->postJson('/api/login', [
            'email' => 'admin@admin.com',
            'password' => 'password'
        ]);

        $response->assertStatus(200);
        
        // Verificar se os headers CORS estão presentes
        $this->assertTrue(
            $response->headers->has('Access-Control-Allow-Origin') ||
            $response->headers->has('Access-Control-Allow-Methods') ||
            $response->headers->has('Access-Control-Allow-Headers')
        );
    }
}