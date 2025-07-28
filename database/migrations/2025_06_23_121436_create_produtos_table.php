<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 50);
            $table->integer('unidade');
            $table->string('validade', 50);
            $table->text('descricao');
            $table->foreignId('doacao_id_doacao')->constrained('doacoes_doadores', 'id_doacao')->onDelete('cascade');
            $table->date('data');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};
