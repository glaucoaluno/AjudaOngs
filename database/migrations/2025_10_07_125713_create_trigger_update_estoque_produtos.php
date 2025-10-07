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
            CREATE OR REPLACE FUNCTION update_estoque_produtos_func()
            RETURNS TRIGGER AS $$
            BEGIN
                -- Se a quantidade ou produto mudou, ajusta o estoque
                IF OLD.quantidade != NEW.quantidade OR OLD.produtos_id != NEW.produtos_id THEN
                    
                    -- Reverte a operação anterior (se produto mudou)
                    IF OLD.produtos_id != NEW.produtos_id THEN
                        UPDATE produtos 
                        SET unidade = unidade + OLD.quantidade
                        WHERE id = OLD.produtos_id;
                        
                        UPDATE produtos 
                        SET ativo = true
                        WHERE id = OLD.produtos_id AND ativo = false;
                    ELSE
                        -- Apenas ajusta a diferença de quantidade
                        UPDATE produtos 
                        SET unidade = unidade + OLD.quantidade - NEW.quantidade
                        WHERE id = NEW.produtos_id;
                    END IF;
                    
                    -- Aplica a nova operação
                    IF OLD.produtos_id != NEW.produtos_id THEN
                        UPDATE produtos 
                        SET unidade = unidade - NEW.quantidade
                        WHERE id = NEW.produtos_id;
                    END IF;
                    
                    -- Verifica se precisa inativar o produto
                    UPDATE produtos 
                    SET ativo = false
                    WHERE id = NEW.produtos_id AND unidade <= 0;
                    
                    -- Reativa produtos que voltaram a ter estoque
                    UPDATE produtos 
                    SET ativo = true
                    WHERE id = NEW.produtos_id AND unidade > 0 AND ativo = false;
                    
                END IF;
                
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ');
        
        DB::unprepared('
            -- Depois cria o trigger
            CREATE TRIGGER update_estoque_produtos 
            AFTER UPDATE ON doacoes_familias
            FOR EACH ROW
            EXECUTE FUNCTION update_estoque_produtos_func();
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS update_estoque_produtos ON doacoes_familias');
        DB::unprepared('DROP FUNCTION IF EXISTS update_estoque_produtos_func()');
    }
};
