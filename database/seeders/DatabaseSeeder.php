<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doador;
use App\Models\FamiliaBeneficiada;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Cria doadores de teste (evita duplicatas)
        Doador::updateOrCreate(
            ['email' => 'joao@email.com'],
            [
                'nome' => 'João Silva',
                'telefone' => '(11) 99999-9999',
                'endereco' => 'Rua das Flores, 123'
            ]
        );

        Doador::updateOrCreate(
            ['email' => 'maria@email.com'],
            [
                'nome' => 'Maria Oliveira',
                'telefone' => '(11) 88888-8888',
                'endereco' => 'Av. Principal, 456'
            ]
        );

        // Cria famílias beneficiadas de teste (evita duplicatas)
        FamiliaBeneficiada::updateOrCreate(
            ['cpf_responsavel' => '123.456.789-00'],
            [
                'nome_representante' => 'Ana Santos',
                'telefone' => '(11) 77777-7777',
                'endereco' => 'Rua da Esperança, 789'
            ]
        );

        FamiliaBeneficiada::updateOrCreate(
            ['cpf_responsavel' => '987.654.321-00'],
            [
                'nome_representante' => 'Carlos Pereira',
                'telefone' => '(11) 66666-6666',
                'endereco' => 'Rua do Futuro, 321'
            ]
        );

        // Cria doadores aleatórios apenas se não existirem muitos
        if (Doador::count() < 10) {
            // Cria 20 doadores aleatórios
            Doador::factory(20)->create();

            // Cria 5 doadores brasileiros
            Doador::factory(5)->brazilian()->create();

            // Cria doadores específicos de Campinas
            Doador::factory(3)->fromCity('Campinas', 'SP')->create();
        }

        User::updateOrCreate(
            [ 'email' => 'admin@admin.com' ],
            [
                'name' => 'admin',
                'password' => Hash::make('admin1234'),
            ]
        );
    }
} 