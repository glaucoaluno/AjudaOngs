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
            CREATE OR REPLACE FUNCTION reverter_estoque_produtos_func()
            RETURNS TRIGGER AS $$
            BEGIN
                -- Adiciona de volta a quantidade do produto
                UPDATE produtos 
                SET unidade = unidade + OLD.quantidade
                WHERE id = OLD.produtos_id;
                
                -- Reativa o produto se ele estava inativo
                UPDATE produtos 
                SET ativo = true
                WHERE id = OLD.produtos_id AND ativo = false;
                
                RETURN OLD;
            END;
            $$ LANGUAGE plpgsql;
        ');
        
        // Depois cria o trigger
        DB::unprepared('
            CREATE TRIGGER reverter_estoque_produtos 
            AFTER DELETE ON doacoes_familias
            FOR EACH ROW
            EXECUTE FUNCTION reverter_estoque_produtos_func();
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS reverter_estoque_produtos ON doacoes_familias');
        DB::unprepared('DROP FUNCTION IF EXISTS reverter_estoque_produtos_func()');
    }
};
