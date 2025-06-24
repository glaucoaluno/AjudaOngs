<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('familia_beneficiadas', function (Blueprint $table) {
            $table->id();
            $table->string('nome_representante', 30);
            $table->string('cpf_responsavel', 15)->unique();
            $table->string('telefone', 15);
            $table->string('endereco', 30);
            $table->timestamp('data_cadastro')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('familia_beneficiadas');
    }
};
