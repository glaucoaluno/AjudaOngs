<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class DatabaseConnectionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teste de conexão com o banco de dados
     */
    public function test_database_connection_is_working()
    {
        try {
            // Testa se consegue executar uma query simples
            $result = DB::select('SELECT 1 as test');
            $this->assertEquals(1, $result[0]->test);
            
            // Testa se consegue acessar as tabelas principais
            $tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
            $tableNames = collect($tables)->pluck('table_name')->toArray();
            
            // Verificar se as tabelas principais existem
            $expectedTables = [
                'users',
                'doadores',
                'familia_beneficiadas',
                'produtos'
            ];
            
            foreach ($expectedTables as $table) {
                $this->assertContains($table, $tableNames, "Tabela {$table} não encontrada");
            }
            
            // Verificar se há pelo menos 4 tabelas (as principais)
            $this->assertGreaterThanOrEqual(4, count($tableNames), "Deve ter pelo menos 4 tabelas");
            
        } catch (\Exception $e) {
            $this->fail("Erro na conexão com banco: " . $e->getMessage());
        }
    }

    /**
     * Teste de migrações
     */
    public function test_migrations_are_working()
    {
        // Verifica se as migrações podem ser executadas
        $this->artisan('migrate:fresh')->assertExitCode(0);
        
        // Verifica se as tabelas foram criadas corretamente
        $this->assertTrue(DB::getSchemaBuilder()->hasTable('users'));
        $this->assertTrue(DB::getSchemaBuilder()->hasTable('doadores'));
        $this->assertTrue(DB::getSchemaBuilder()->hasTable('familia_beneficiadas'));
        $this->assertTrue(DB::getSchemaBuilder()->hasTable('produtos'));
        
        // Verificar tabelas opcionais
        if (DB::getSchemaBuilder()->hasTable('doacao_doador')) {
            $this->assertTrue(true, 'Tabela doacao_doador existe');
        }
        
        if (DB::getSchemaBuilder()->hasTable('doacao_familia')) {
            $this->assertTrue(true, 'Tabela doacao_familia existe');
        }
    }

    /**
     * Teste de seeders
     */
    public function test_seeders_are_working()
    {
        // Executa os seeders
        $this->artisan('db:seed')->assertExitCode(0);
        
        // Verifica se o usuário admin foi criado
        $this->assertDatabaseHas('users', [
            'email' => 'admin@admin.com'
        ]);
    }
}