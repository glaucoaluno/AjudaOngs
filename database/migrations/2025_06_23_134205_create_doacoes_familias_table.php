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
        Schema::create('doacoes_familias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('familia_id_familia')->constrained('familia_beneficiadas', 'id')->onDelete('cascade');
            $table->foreignId('produtos_id')->constrained('produtos', 'id')->onDelete('cascade');
            $table->integer('quantidade');
            $table->date('data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doacoes_familias');
    }
};
