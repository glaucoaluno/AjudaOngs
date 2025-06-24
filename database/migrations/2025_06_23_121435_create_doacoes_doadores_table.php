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
        Schema::create('doacoes_doadores', function (Blueprint $table) {
            $table->id('id_doacao');
            $table->dateTime('data_doacao');
            $table->dateTime('data_entrega')->nullable();
            $table->foreignId('id_doador')->constrained('doadores', 'id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doacoes_doadores');
    }
};
