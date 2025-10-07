<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Primeiro cria a função do trigger
        DB::unprepared('
            CREATE OR REPLACE FUNCTION atualizar_estoque_produtos_func()
            RETURNS TRIGGER AS $$
            BEGIN
                -- Subtrai a quantidade do produto
                UPDATE produtos 
                SET unidade = unidade - NEW.quantidade
                WHERE id = NEW.produtos_id;
                
                -- Se a quantidade ficar zero ou negativa, inativa o produto
                UPDATE produtos 
                SET ativo = false
                WHERE id = NEW.produtos_id AND unidade <= 0;
                
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ');
        
        // Depois cria o trigger
        DB::unprepared('
            CREATE TRIGGER atualizar_estoque_produtos 
            AFTER INSERT ON doacoes_familias
            FOR EACH ROW
            EXECUTE FUNCTION atualizar_estoque_produtos_func();
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS atualizar_estoque_produtos ON doacoes_familias');
        DB::unprepared('DROP FUNCTION IF EXISTS atualizar_estoque_produtos_func()');
    }
};
