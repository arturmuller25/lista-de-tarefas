<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cria a tabela de tarefas.
     */
    public function up(): void
    {
        Schema::create('tarefas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');                   // Título da tarefa
            $table->text('descricao')->nullable();      // Descrição opcional
            $table->string('imagem')->nullable();       // Caminho da imagem (uma por tarefa)
            $table->boolean('concluida')->default(false); // Concluída ou não
            $table->timestamps();
            $table->softDeletes();                      // Coluna deleted_at (lixeira)
        });
    }

    /**
     * Remove a tabela de tarefas.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarefas');
    }
};
